<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\CashRegister;
use App\Models\CashMovement;
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
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_method' => 'required|in:cash,card,transfer,credit',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $businessId = auth()->user()->business_id;

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

            DB::commit();

            return redirect()->route('sales.show', $sale)->with('success', "Venta {$invoiceNumber} registrada por \${$total}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar la venta: ' . $e->getMessage())->withInput();
        }
    }
}
