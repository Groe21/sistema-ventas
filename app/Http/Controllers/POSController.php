<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\CashRegister;
use App\Models\CashMovement;
use App\Models\CustomerPoint;
use App\Models\PointTransaction;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $businessId = auth()->user()->business_id;

        $products = Product::where('business_id', $businessId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $customers = Customer::where('business_id', $businessId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Verificar si hay caja abierta
        $openRegister = CashRegister::where('business_id', $businessId)
            ->where('status', 'open')
            ->first();

        return view('admin.pos.index', compact('products', 'customers', 'openRegister'));
    }

    public function store(Request $request)
    {
        $businessId = auth()->user()->business_id;

        // Determinar métodos de pago permitidos según el plan
        $allowedMethods = ['cash']; // Efectivo siempre disponible
        try {
            $planService = app(PlanService::class);
            $business = auth()->user()->business;
            if ($planService->hasFeature($business, 'payment_card')) {
                $allowedMethods[] = 'card';
            }
            if ($planService->hasFeature($business, 'payment_transfer')) {
                $allowedMethods[] = 'transfer';
            }
            if ($planService->hasFeature($business, 'payment_credit')) {
                $allowedMethods[] = 'credit';
            }
        } catch (\Exception $e) {
            // Si falla la verificación, solo permitir efectivo
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_method' => 'required|in:' . implode(',', $allowedMethods),
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'redeem_points' => 'nullable|integer|min:0',
        ]);


        try {
            DB::beginTransaction();

            $subtotal = 0;
            $ivaAmount = 0;
            $items = [];

            // Calcular y validar stock
            foreach ($validated['items'] as $item) {
                $product = Product::where('id', $item['product_id'])
                    ->where('business_id', $businessId)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($product->stock_type === 'product' && $product->stock < $item['quantity']) {
                    DB::rollBack();
                    return back()->with('error', "Stock insuficiente para {$product->name}. Disponible: {$product->stock}")->withInput();
                }

                $itemSubtotal = $product->sale_price * $item['quantity'];
                $itemIva = $product->has_iva ? round($itemSubtotal * 0.15, 2) : 0;

                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                    'iva' => $itemIva,
                ];

                $subtotal += $itemSubtotal;
                $ivaAmount += $itemIva;
            }

            $discount = $validated['discount'] ?? 0;
            $total = round($subtotal + $ivaAmount - $discount, 2);

            // Generar numero de factura
            $lastNumber = Sale::where('business_id', $businessId)->max('id') ?? 0;
            $invoiceNumber = 'FAC-' . str_pad($lastNumber + 1, 8, '0', STR_PAD_LEFT);

            // Verificar caja abierta
            $cashRegister = CashRegister::where('business_id', $businessId)
                ->where('status', 'open')
                ->first();

            $sale = Sale::create([
                'business_id' => $businessId,
                'user_id' => auth()->id(),
                'customer_id' => $validated['customer_id'],
                'cash_register_id' => $cashRegister?->id,
                'invoice_number' => $invoiceNumber,
                'sale_date' => now(),
                'subtotal' => $subtotal,
                'iva_amount' => $ivaAmount,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'credit' ? 'pending' : 'paid',
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Crear items y descontar stock
            foreach ($items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'product_code' => $item['product']->code,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['product']->sale_price,
                    'subtotal' => $item['subtotal'],
                    'has_iva' => $item['product']->has_iva,
                    'iva_amount' => $item['iva'],
                    'total' => $item['subtotal'] + $item['iva'],
                ]);

                if ($item['product']->stock_type === 'product') {
                    $item['product']->decrement('stock', $item['quantity']);
                }
            }

            // Registrar movimiento de caja
            if ($cashRegister) {
                CashMovement::create([
                    'business_id' => $businessId,
                    'cash_register_id' => $cashRegister->id,
                    'user_id' => auth()->id(),
                    'sale_id' => $sale->id,
                    'type' => 'income',
                    'category' => 'sale',
                    'amount' => $total,
                    'description' => "Venta {$invoiceNumber}",
                    'payment_method' => $validated['payment_method'],
                ]);
            }

            // Loyalty points: award points if plan supports it
            try {
                $planService = app(PlanService::class);
                $business = auth()->user()->business;
                if ($planService->hasFeature($business, 'loyalty_points') && $total > 0) {
                    $pointsEarned = $planService->calculatePoints($total);
                    if ($pointsEarned > 0) {
                        $pointRecord = CustomerPoint::firstOrCreate(
                            ['business_id' => $businessId, 'customer_id' => $validated['customer_id']],
                            ['points_balance' => 0]
                        );
                        $pointRecord->increment('points_balance', $pointsEarned);

                        PointTransaction::create([
                            'business_id' => $businessId,
                            'customer_id' => $validated['customer_id'],
                            'sale_id' => $sale->id,
                            'points_earned' => $pointsEarned,
                            'points_used' => 0,
                            'description' => "Puntos por venta {$invoiceNumber}",
                        ]);
                    }
                }

                // Redeem points if requested
                $redeemPoints = (int) ($request->input('redeem_points', 0));
                if ($redeemPoints > 0 && $planService->hasFeature($business, 'loyalty_points')) {
                    $pointRecord = CustomerPoint::where('business_id', $businessId)
                        ->where('customer_id', $validated['customer_id'])
                        ->first();

                    if ($pointRecord && $pointRecord->points_balance >= $redeemPoints) {
                        $pointRecord->decrement('points_balance', $redeemPoints);

                        PointTransaction::create([
                            'business_id' => $businessId,
                            'customer_id' => $validated['customer_id'],
                            'sale_id' => $sale->id,
                            'points_earned' => 0,
                            'points_used' => $redeemPoints,
                            'description' => "Canje de puntos en venta {$invoiceNumber}",
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Plan/points tables may not exist yet, skip loyalty
            }

            DB::commit();

            return redirect()->route('sales.show', $sale)->with('success', "Venta {$invoiceNumber} registrada por \${$total}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar la venta: ' . $e->getMessage())->withInput();
        }
    }
}
