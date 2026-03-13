<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\CashMovement;
use Illuminate\Http\Request;

class CashController extends Controller
{
    /**
     * Display cash register management.
     */
    public function index()
    {
        $businessId = auth()->user()->business_id;

        // Get current open cash register
        $openRegister = CashRegister::where('business_id', $businessId)
            ->where('status', 'open')
            ->with('cashMovements')
            ->first();

        $expectedByMethod = null;
        if ($openRegister) {
            $expectedByMethod = $openRegister->calculateExpectedByMethod();
        }

        // Get recent closed registers
        $closedRegisters = CashRegister::where('business_id', $businessId)
            ->where('status', 'closed')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.cash.index', compact('openRegister', 'closedRegisters', 'expectedByMethod'));
    }

    /**
     * Open a new cash register.
     */
    public function open(Request $request)
    {
        $validated = $request->validate([
            'opening_amount' => 'required|numeric|min:0',
            'opening_notes' => 'nullable|string',
        ]);

        $businessId = auth()->user()->business_id;

        // Check if there's already an open register
        $existingOpen = CashRegister::where('business_id', $businessId)
            ->where('status', 'open')
            ->exists();

        if ($existingOpen) {
            return back()->with('error', 'Ya existe una caja abierta.');
        }

        $cashRegister = CashRegister::create([
            'business_id' => $businessId,
            'user_id' => auth()->id(),
            'name' => 'Caja Principal',
            'opened_at' => now(),
            'opening_amount' => $validated['opening_amount'],
            'opening_notes' => $validated['opening_notes'],
            'status' => 'open',
        ]);

        // Register initial movement
        CashMovement::create([
            'business_id' => $businessId,
            'cash_register_id' => $cashRegister->id,
            'user_id' => auth()->id(),
            'type' => 'initial',
            'category' => 'initial_balance',
            'amount' => $validated['opening_amount'],
            'description' => 'Saldo inicial de caja',
            'payment_method' => 'cash',
        ]);

        return redirect()->route('cash.index')
            ->with('success', 'Caja abierta exitosamente.');
    }

    /**
     * Close the cash register.
     */
    public function close(Request $request, CashRegister $cashRegister)
    {
        $validated = $request->validate([
            'denominations' => 'required|array',
            'denominations.*' => 'nullable|integer|min:0',
            'counted_card_amount' => 'nullable|numeric|min:0',
            'counted_transfer_amount' => 'nullable|numeric|min:0',
            'closing_notes' => 'nullable|string',
        ]);

        // Check business access
        if ($cashRegister->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        if ($cashRegister->status !== 'open') {
            return back()->with('error', 'Esta caja ya está cerrada.');
        }

        $denominationValues = [
            'coin_001' => 0.01,
            'coin_005' => 0.05,
            'coin_010' => 0.10,
            'coin_025' => 0.25,
            'coin_050' => 0.50,
            'coin_100' => 1.00,
            'bill_1' => 1.00,
            'bill_5' => 5.00,
            'bill_10' => 10.00,
            'bill_20' => 20.00,
            'bill_50' => 50.00,
            'bill_100' => 100.00,
        ];

        $breakdown = [];
        $actualAmount = 0;
        foreach ($denominationValues as $key => $value) {
            $qty = (int) ($validated['denominations'][$key] ?? 0);
            $subtotal = round($qty * $value, 2);
            $breakdown[$key] = [
                'qty' => $qty,
                'value' => $value,
                'subtotal' => $subtotal,
            ];
            $actualAmount += $subtotal;
        }

        $actualAmount = round($actualAmount, 2);
        $expectedAmount = $cashRegister->calculateExpectedAmount();
        $difference = round($actualAmount - $expectedAmount, 2);

        $cashRegister->update([
            'closed_at' => now(),
            'expected_amount' => $expectedAmount,
            'actual_amount' => $actualAmount,
            'counted_card_amount' => $validated['counted_card_amount'] ?? 0,
            'counted_transfer_amount' => $validated['counted_transfer_amount'] ?? 0,
            'difference' => $difference,
            'cash_breakdown' => $breakdown,
            'closing_notes' => $validated['closing_notes'],
            'status' => 'closed',
        ]);

        // Register closing movement
        CashMovement::create([
            'business_id' => $cashRegister->business_id,
            'cash_register_id' => $cashRegister->id,
            'user_id' => auth()->id(),
            'type' => 'closing',
            'category' => 'closing_balance',
            'amount' => $actualAmount,
            'description' => 'Cierre de caja - Diferencia: $' . number_format($difference, 2),
            'payment_method' => 'cash',
        ]);

        return redirect()->route('cash.index')
            ->with('success', 'Caja cerrada exitosamente.');
    }

    /**
     * Register a cash movement.
     */
    public function movement(Request $request)
    {
        $validated = $request->validate([
            'cash_register_id' => 'required|exists:cash_registers,id',
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'payment_method' => 'required|in:cash,card,transfer,other',
        ]);

        $validated['business_id'] = auth()->user()->business_id;
        $validated['user_id'] = auth()->id();

        CashMovement::create($validated);

        return redirect()->route('cash.index')
            ->with('success', 'Movimiento registrado exitosamente.');
    }
}
