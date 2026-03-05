<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $businessId = auth()->user()->business_id;
        $query = Sale::where('business_id', $businessId)->with(['customer', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'ilike', "%{$search}%")
                  ->orWhereHas('customer', fn($c) => $c->where('name', 'ilike', "%{$search}%"));
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sale_date', '<=', $request->date_to);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sales = $query->latest('sale_date')->paginate(20);

        // Totales del periodo filtrado
        $totalsQuery = Sale::where('business_id', $businessId)->where('status', 'completed');
        if ($request->filled('date_from')) $totalsQuery->whereDate('sale_date', '>=', $request->date_from);
        if ($request->filled('date_to')) $totalsQuery->whereDate('sale_date', '<=', $request->date_to);

        $totalSales = $totalsQuery->sum('total');
        $totalCount = $totalsQuery->count();
        $todaySales = Sale::where('business_id', $businessId)
            ->where('status', 'completed')
            ->whereDate('sale_date', today())->sum('total');

        return view('admin.sales.index', compact('sales', 'totalSales', 'totalCount', 'todaySales'));
    }

    public function show(Sale $sale)
    {
        if ($sale->business_id !== auth()->user()->business_id) {
            abort(403);
        }

        $sale->load(['customer', 'user', 'items.product']);

        return view('admin.sales.show', compact('sale'));
    }
}
