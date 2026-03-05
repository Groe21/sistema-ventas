<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\CashRegister;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $businessId = $user->business_id;

        // Stats principales
        $stats = [
            'today_sales' => Sale::where('business_id', $businessId)
                ->whereDate('sale_date', today())
                ->where('status', 'completed')
                ->sum('total'),

            'month_sales' => Sale::where('business_id', $businessId)
                ->whereMonth('sale_date', now()->month)
                ->whereYear('sale_date', now()->year)
                ->where('status', 'completed')
                ->sum('total'),

            'total_products' => Product::where('business_id', $businessId)
                ->where('is_active', true)
                ->count(),

            'total_customers' => Customer::where('business_id', $businessId)
                ->where('is_active', true)
                ->count(),

            'low_stock_products' => Product::where('business_id', $businessId)
                ->where('is_active', true)
                ->whereColumn('stock', '<=', 'min_stock')
                ->count(),

            'inventory_value' => Product::where('business_id', $businessId)
                ->where('is_active', true)
                ->selectRaw('COALESCE(SUM(cost_price * stock), 0) as total')
                ->value('total'),

            'today_invoices' => Sale::where('business_id', $businessId)
                ->whereDate('sale_date', today())
                ->where('status', 'completed')
                ->count(),
        ];

        // Ventas últimos 7 días para gráfico
        $salesChart = Sale::where('business_id', $businessId)
            ->where('status', 'completed')
            ->whereDate('sale_date', '>=', now()->subDays(6))
            ->selectRaw("DATE(sale_date) as date, SUM(total) as total, COUNT(*) as count")
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->locale('es')->isoFormat('ddd D');
            $chartData[] = (float) ($salesChart[$date]->total ?? 0);
        }

        // Ventas recientes
        $recentSales = Sale::where('business_id', $businessId)
            ->with(['customer', 'user'])
            ->latest('sale_date')
            ->take(8)
            ->get();

        // Productos más vendidos (mes actual)
        $topProducts = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->where('sales.business_id', $businessId)
            ->where('sales.status', 'completed')
            ->whereMonth('sales.sale_date', now()->month)
            ->whereYear('sales.sale_date', now()->year)
            ->selectRaw('sale_items.product_name, SUM(sale_items.quantity) as qty, SUM(sale_items.subtotal) as revenue')
            ->groupBy('sale_items.product_name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        // Productos con stock bajo
        $lowStockProducts = Product::where('business_id', $businessId)
            ->where('is_active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->take(5)
            ->get();

        // Estado de caja
        $openCash = CashRegister::where('business_id', $businessId)
            ->where('status', 'open')
            ->first();

        return view('admin.dashboard', compact(
            'stats', 'recentSales', 'topProducts', 'lowStockProducts',
            'chartLabels', 'chartData', 'openCash'
        ));
    }
}
