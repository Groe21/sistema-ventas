@extends('layouts.app')
@section('title', 'Reportes Globales')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-graph-up"></i> Reportes Globales
        </h2>
        <div class="text-muted">
            <i class="bi bi-calendar3"></i> {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('super-admin.reports.index') }}" class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small mb-1">Período rápido</label>
                    <select name="period" class="form-select form-select-sm" id="periodSelect">
                        <option value="7" {{ $period == '7' ? 'selected' : '' }}>Últimos 7 días</option>
                        <option value="30" {{ $period == '30' ? 'selected' : '' }}>Últimos 30 días</option>
                        <option value="90" {{ $period == '90' ? 'selected' : '' }}>Últimos 90 días</option>
                        <option value="365" {{ $period == '365' ? 'selected' : '' }}>Último año</option>
                        <option value="custom" {{ request('start_date') ? 'selected' : '' }}>Personalizado</option>
                    </select>
                </div>
                <div class="col-md-2" id="customStartDate" style="{{ request('start_date') ? '' : 'display:none' }}">
                    <label class="form-label small mb-1">Desde</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-2" id="customEndDate" style="{{ request('start_date') ? '' : 'display:none' }}">
                    <label class="form-label small mb-1">Hasta</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card stat-card" style="border-left-color: #2ecc71;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Ventas</div>
                            <div class="stat-value">${{ number_format($stats['total_ventas'], 2) }}</div>
                        </div>
                        <div style="font-size: 2rem; color: #2ecc71;">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card stat-card" style="border-left-color: #3498db;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Nº Transacciones</div>
                            <div class="stat-value">{{ number_format($stats['num_ventas']) }}</div>
                        </div>
                        <div style="font-size: 2rem; color: #3498db;">
                            <i class="bi bi-receipt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card stat-card" style="border-left-color: #f39c12;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Ticket Promedio</div>
                            <div class="stat-value">${{ number_format($stats['ticket_promedio'], 2) }}</div>
                        </div>
                        <div style="font-size: 2rem; color: #f39c12;">
                            <i class="bi bi-tag"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card stat-card" style="border-left-color: #e74c3c;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">IVA Recaudado</div>
                            <div class="stat-value">${{ number_format($stats['total_iva'], 2) }}</div>
                        </div>
                        <div style="font-size: 2rem; color: #e74c3c;">
                            <i class="bi bi-percent"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de ventas diarias + Métodos de pago -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-bar-chart"></i> Ventas Diarias
                </div>
                <div class="card-body">
                    <canvas id="ventasDiariasChart" height="280"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-pie-chart"></i> Métodos de Pago
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    @if($ventasPorMetodo->count() > 0)
                        <canvas id="metodosPagoChart" height="250"></canvas>
                    @else
                        <p class="text-muted">Sin datos en este período</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ventas por Negocio + Top Productos -->
    <div class="row mb-4">
        <div class="col-lg-7 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-building"></i> Ventas por Negocio</span>
                    <span class="badge bg-secondary">{{ $ventasPorNegocio->count() }} negocios</span>
                </div>
                <div class="card-body p-0">
                    @if($ventasPorNegocio->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Negocio</th>
                                    <th class="text-center">Ventas</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end pe-3">Promedio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ventasPorNegocio as $i => $negocio)
                                <tr>
                                    <td class="ps-3">{{ $i + 1 }}</td>
                                    <td><i class="bi bi-shop text-primary"></i> {{ $negocio->business_name }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ number_format($negocio->num_ventas) }}</span>
                                    </td>
                                    <td class="text-end fw-bold">${{ number_format($negocio->total_ventas, 2) }}</td>
                                    <td class="text-end pe-3">${{ number_format($negocio->ticket_promedio, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr class="fw-bold">
                                    <td colspan="2" class="ps-3">Total</td>
                                    <td class="text-center">{{ number_format($ventasPorNegocio->sum('num_ventas')) }}</td>
                                    <td class="text-end">${{ number_format($ventasPorNegocio->sum('total_ventas'), 2) }}</td>
                                    <td class="text-end pe-3">—</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <p class="mt-2">Sin ventas en este período</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-trophy"></i> Top 10 Productos</span>
                </div>
                <div class="card-body p-0">
                    @if($topProductos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Producto</th>
                                    <th class="text-center">Vendidos</th>
                                    <th class="text-end pe-3">Ingresos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProductos as $i => $producto)
                                <tr>
                                    <td class="ps-3">
                                        @if($i < 3)
                                            <span class="badge {{ $i === 0 ? 'bg-warning text-dark' : ($i === 1 ? 'bg-secondary' : 'bg-danger') }}">
                                                {{ $i + 1 }}
                                            </span>
                                        @else
                                            {{ $i + 1 }}
                                        @endif
                                    </td>
                                    <td>{{ $producto->product_name }}</td>
                                    <td class="text-center">{{ number_format($producto->total_cantidad) }}</td>
                                    <td class="text-end pe-3 fw-bold">${{ number_format($producto->total_ingresos, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-box" style="font-size: 2rem;"></i>
                        <p class="mt-2">Sin productos vendidos en este período</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ranking de Negocios + Suscripciones por Plan -->
    <div class="row mb-4">
        <div class="col-lg-7 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-bar-chart-steps"></i> Ranking de Negocios por Ingresos
                </div>
                <div class="card-body">
                    @if($rankingNegocios->count() > 0)
                        <canvas id="rankingNegociosChart" height="300"></canvas>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">Sin datos en este período</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <i class="bi bi-collection"></i> Suscripciones Activas por Plan
                </div>
                <div class="card-body">
                    @if($suscripcionesPorPlan->count() > 0)
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <canvas id="suscripcionesChart" height="200"></canvas>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($suscripcionesPorPlan as $sub)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class="bi bi-gem text-primary"></i> {{ $sub->plan_name }}</span>
                            <span class="badge bg-primary rounded-pill">{{ $sub->cantidad }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-collection" style="font-size: 2rem;"></i>
                        <p class="mt-2">Sin suscripciones activas</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detalle por Método de Pago -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-credit-card"></i> Detalle por Método de Pago
                </div>
                <div class="card-body p-0">
                    @if($ventasPorMetodo->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Método</th>
                                    <th class="text-center">Transacciones</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end pe-3">% del Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalMetodos = $ventasPorMetodo->sum('total'); @endphp
                                @foreach($ventasPorMetodo as $metodo)
                                <tr>
                                    <td class="ps-3">
                                        @switch($metodo->payment_method)
                                            @case('cash')
                                                <i class="bi bi-cash text-success"></i> Efectivo
                                                @break
                                            @case('card')
                                                <i class="bi bi-credit-card text-primary"></i> Tarjeta
                                                @break
                                            @case('transfer')
                                                <i class="bi bi-bank text-info"></i> Transferencia
                                                @break
                                            @case('credit')
                                                <i class="bi bi-receipt text-warning"></i> Crédito
                                                @break
                                            @default
                                                <i class="bi bi-question-circle"></i> {{ $metodo->payment_method }}
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ number_format($metodo->cantidad) }}</span>
                                    </td>
                                    <td class="text-end fw-bold">${{ number_format($metodo->total, 2) }}</td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px; max-width: 100px;">
                                                <div class="progress-bar" style="width: {{ $totalMetodos > 0 ? round(($metodo->total / $totalMetodos) * 100) : 0 }}%"></div>
                                            </div>
                                            <span>{{ $totalMetodos > 0 ? number_format(($metodo->total / $totalMetodos) * 100, 1) : 0 }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <p>Sin transacciones en este período</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle fechas personalizadas
    document.getElementById('periodSelect').addEventListener('change', function() {
        const show = this.value === 'custom';
        document.getElementById('customStartDate').style.display = show ? '' : 'none';
        document.getElementById('customEndDate').style.display = show ? '' : 'none';
    });

    // Gráfico ventas diarias
    const ventasDiarias = @json($ventasDiarias);
    if (ventasDiarias.length > 0) {
        new Chart(document.getElementById('ventasDiariasChart'), {
            type: 'bar',
            data: {
                labels: ventasDiarias.map(v => {
                    const d = new Date(v.fecha + 'T12:00:00');
                    return d.toLocaleDateString('es-EC', { day: '2-digit', month: 'short' });
                }),
                datasets: [{
                    label: 'Ventas ($)',
                    data: ventasDiarias.map(v => parseFloat(v.total)),
                    backgroundColor: 'rgba(52, 152, 219, 0.7)',
                    borderColor: '#3498db',
                    borderWidth: 1,
                    borderRadius: 4,
                }, {
                    label: 'Transacciones',
                    data: ventasDiarias.map(v => v.cantidad),
                    type: 'line',
                    borderColor: '#e74c3c',
                    backgroundColor: 'rgba(231, 76, 60, 0.1)',
                    tension: 0.3,
                    yAxisID: 'y1',
                    pointRadius: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '$' + v.toLocaleString() }
                    },
                    y1: {
                        position: 'right',
                        beginAtZero: true,
                        grid: { drawOnChartArea: false },
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }

    // Gráfico métodos de pago
    const metodos = @json($ventasPorMetodo);
    const metodoLabels = { cash: 'Efectivo', card: 'Tarjeta', transfer: 'Transferencia', credit: 'Crédito' };
    const metodoColors = { cash: '#2ecc71', card: '#3498db', transfer: '#00bcd4', credit: '#f39c12' };
    if (metodos.length > 0 && document.getElementById('metodosPagoChart')) {
        new Chart(document.getElementById('metodosPagoChart'), {
            type: 'doughnut',
            data: {
                labels: metodos.map(m => metodoLabels[m.payment_method] || m.payment_method),
                datasets: [{
                    data: metodos.map(m => parseFloat(m.total)),
                    backgroundColor: metodos.map(m => metodoColors[m.payment_method] || '#95a5a6'),
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.label + ': $' + ctx.parsed.toLocaleString(undefined, {minimumFractionDigits: 2})
                        }
                    }
                }
            }
        });
    }

    // Gráfico ranking de negocios
    const ranking = @json($rankingNegocios);
    if (ranking.length > 0 && document.getElementById('rankingNegociosChart')) {
        new Chart(document.getElementById('rankingNegociosChart'), {
            type: 'bar',
            data: {
                labels: ranking.map(r => r.name),
                datasets: [{
                    label: 'Ingresos ($)',
                    data: ranking.map(r => parseFloat(r.ventas_total || 0)),
                    backgroundColor: [
                        '#f39c12', '#3498db', '#2ecc71', '#e74c3c', '#9b59b6',
                        '#1abc9c', '#e67e22', '#34495e', '#16a085', '#c0392b'
                    ],
                    borderRadius: 4,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { callback: v => '$' + v.toLocaleString() }
                    }
                }
            }
        });
    }

    // Gráfico suscripciones
    const subs = @json($suscripcionesPorPlan);
    if (subs.length > 0 && document.getElementById('suscripcionesChart')) {
        new Chart(document.getElementById('suscripcionesChart'), {
            type: 'doughnut',
            data: {
                labels: subs.map(s => s.plan_name),
                datasets: [{
                    data: subs.map(s => s.cantidad),
                    backgroundColor: ['#3498db', '#2ecc71', '#f39c12', '#e74c3c', '#9b59b6'],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
});
</script>
@endpush
