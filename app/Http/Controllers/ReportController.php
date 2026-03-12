<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $businessId = auth()->user()->business_id;
        $period = $request->get('period', '30');
        $startDate = now()->subDays((int) $period)->startOfDay();
        $endDate = now()->endOfDay();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
        }

        // Verificar features del plan
        $planService = app(PlanService::class);
        $business = auth()->user()->business;
        $hasAdvanced = $planService->hasFeature($business, 'advanced_reports');
        $hasExportExcel = $planService->hasFeature($business, 'export_excel');
        $hasExportPdf = $planService->hasFeature($business, 'export_pdf');

        // === REPORTES BÁSICOS (todos los planes) ===
        $salesQuery = Sale::where('business_id', $businessId)
            ->where('status', 'completed')
            ->whereBetween('sale_date', [$startDate, $endDate]);

        $stats = [
            'total_ventas' => (clone $salesQuery)->sum('total'),
            'num_ventas' => (clone $salesQuery)->count(),
            'ticket_promedio' => (clone $salesQuery)->count() > 0
                ? (clone $salesQuery)->sum('total') / (clone $salesQuery)->count()
                : 0,
            'total_iva' => (clone $salesQuery)->sum('iva_amount'),
            'total_descuentos' => (clone $salesQuery)->sum('discount'),
        ];

        // Ventas por método de pago
        $ventasPorMetodo = Sale::where('business_id', $businessId)
            ->where('status', 'completed')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->select('payment_method', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(total) as total'))
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        // Ventas diarias
        $ventasDiarias = Sale::where('business_id', $businessId)
            ->where('status', 'completed')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->select(
                DB::raw("TO_CHAR(sale_date, 'YYYY-MM-DD') as fecha"),
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy(DB::raw("TO_CHAR(sale_date, 'YYYY-MM-DD')"))
            ->orderBy('fecha')
            ->get();

        // === REPORTES AVANZADOS (solo planes con advanced_reports) ===
        $topProductos = collect();
        $topClientes = collect();
        $ventasPorHora = collect();
        $comparativa = null;

        if ($hasAdvanced) {
            // Top 10 productos
            $topProductos = DB::table('sale_items')
                ->select(
                    'sale_items.product_name',
                    DB::raw('SUM(sale_items.quantity) as total_cantidad'),
                    DB::raw('SUM(sale_items.total) as total_ingresos')
                )
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->where('sales.business_id', $businessId)
                ->where('sales.status', 'completed')
                ->whereBetween('sales.sale_date', [$startDate, $endDate])
                ->whereNull('sales.deleted_at')
                ->groupBy('sale_items.product_name')
                ->orderByDesc('total_cantidad')
                ->limit(10)
                ->get();

            // Top 10 clientes
            $topClientes = Sale::where('sales.business_id', $businessId)
                ->where('sales.status', 'completed')
                ->whereBetween('sales.sale_date', [$startDate, $endDate])
                ->join('customers', 'sales.customer_id', '=', 'customers.id')
                ->select(
                    'customers.name',
                    DB::raw('COUNT(sales.id) as num_compras'),
                    DB::raw('SUM(sales.total) as total_gastado')
                )
                ->groupBy('customers.name')
                ->orderByDesc('total_gastado')
                ->limit(10)
                ->get();

            // Ventas por hora del día
            $ventasPorHora = Sale::where('business_id', $businessId)
                ->where('status', 'completed')
                ->whereBetween('sale_date', [$startDate, $endDate])
                ->select(
                    DB::raw("EXTRACT(HOUR FROM created_at) as hora"),
                    DB::raw('COUNT(*) as cantidad'),
                    DB::raw('SUM(total) as total')
                )
                ->groupBy(DB::raw("EXTRACT(HOUR FROM created_at)"))
                ->orderBy('hora')
                ->get();

            // Comparativa con período anterior
            $daysDiff = $startDate->diffInDays($endDate);
            $prevStart = (clone $startDate)->subDays($daysDiff);
            $prevEnd = (clone $startDate)->subSecond();

            $prevQuery = Sale::where('business_id', $businessId)
                ->where('status', 'completed')
                ->whereBetween('sale_date', [$prevStart, $prevEnd]);

            $prevTotal = $prevQuery->sum('total');
            $prevCount = $prevQuery->count();

            $comparativa = [
                'prev_total' => $prevTotal,
                'prev_count' => $prevCount,
                'cambio_total' => $prevTotal > 0
                    ? round((($stats['total_ventas'] - $prevTotal) / $prevTotal) * 100, 1)
                    : ($stats['total_ventas'] > 0 ? 100 : 0),
                'cambio_count' => $prevCount > 0
                    ? round((($stats['num_ventas'] - $prevCount) / $prevCount) * 100, 1)
                    : ($stats['num_ventas'] > 0 ? 100 : 0),
            ];
        }

        return view('admin.reports.index', compact(
            'stats', 'ventasPorMetodo', 'ventasDiarias',
            'topProductos', 'topClientes', 'ventasPorHora', 'comparativa',
            'hasAdvanced', 'hasExportExcel', 'hasExportPdf',
            'startDate', 'endDate', 'period'
        ));
    }
}
