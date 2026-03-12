@extends('layouts.app')
@section('title', 'Reportes')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-graph-up"></i> Reportes
        </h2>
        <div class="text-muted">
            <i class="bi bi-calendar3"></i> {{ $startDate->format('d/m/Y') }} — {{ $endDate->format('d/m/Y') }}
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('reports.index') }}" class="row g-2 align-items-end">
                <div class="col-md-2 col-6">
                    <label class="form-label small mb-1">Período</label>
                    <select name="period" class="form-select form-select-sm" id="periodSelect">
                        <option value="7" {{ $period == '7' ? 'selected' : '' }}>Últimos 7 días</option>
                        <option value="30" {{ $period == '30' ? 'selected' : '' }}>Últimos 30 días</option>
                        <option value="90" {{ $period == '90' ? 'selected' : '' }}>Últimos 90 días</option>
                        <option value="365" {{ $period == '365' ? 'selected' : '' }}>Último año</option>
                        <option value="custom" {{ request('start_date') ? 'selected' : '' }}>Personalizado</option>
                    </select>
                </div>
                <div class="col-md-2 col-6" id="customStartDate" style="{{ request('start_date') ? '' : 'display:none' }}">
                    <label class="form-label small mb-1">Desde</label>
                    <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-2 col-6" id="customEndDate" style="{{ request('start_date') ? '' : 'display:none' }}">
                    <label class="form-label small mb-1">Hasta</label>
                    <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                </div>
                <div class="col-md-auto col-6">
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
                            @if($hasAdvanced && $comparativa)
                                <small class="{{ $comparativa['cambio_total'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="bi bi-{{ $comparativa['cambio_total'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ abs($comparativa['cambio_total']) }}% vs período anterior
                                </small>
                            @endif
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
                            @if($hasAdvanced && $comparativa)
                                <small class="{{ $comparativa['cambio_count'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="bi bi-{{ $comparativa['cambio_count'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ abs($comparativa['cambio_count']) }}% vs anterior
                                </small>
                            @endif
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

    <!-- Gráfico ventas diarias + Métodos de pago -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-3">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-bar-chart"></i> Ventas Diarias</div>
                <div class="card-body">
                    <canvas id="ventasDiariasChart" height="280"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-pie-chart"></i> Métodos de Pago</div>
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

    <!-- Detalle Métodos de Pago -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="bi bi-credit-card"></i> Detalle por Método de Pago</div>
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
                                                <i class="bi bi-cash text-success"></i> Efectivo @break
                                            @case('card')
                                                <i class="bi bi-credit-card text-primary"></i> Tarjeta @break
                                            @case('transfer')
                                                <i class="bi bi-bank text-info"></i> Transferencia @break
                                            @case('credit')
                                                <i class="bi bi-receipt text-warning"></i> Crédito @break
                                            @default
                                                <i class="bi bi-question-circle"></i> {{ $metodo->payment_method }}
                                        @endswitch
                                    </td>
                                    <td class="text-center"><span class="badge bg-secondary">{{ number_format($metodo->cantidad) }}</span></td>
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
                    <div class="text-center py-4 text-muted"><p>Sin transacciones en este período</p></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== REPORTES AVANZADOS ==================== --}}
    @if($hasAdvanced)
    <hr class="my-4">
    <div class="d-flex align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-stars text-warning"></i> Reportes Avanzados</h4>
        <span class="badge bg-warning text-dark ms-2">Plan Avanzado</span>
    </div>

    <!-- Top Productos + Top Clientes -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-trophy text-warning"></i> Top 10 Productos</span>
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
                                            <span class="badge {{ $i === 0 ? 'bg-warning text-dark' : ($i === 1 ? 'bg-secondary' : 'bg-danger') }}">{{ $i + 1 }}</span>
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
                        <p class="mt-2">Sin productos vendidos</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people text-primary"></i> Top 10 Clientes</span>
                </div>
                <div class="card-body p-0">
                    @if($topClientes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Cliente</th>
                                    <th class="text-center">Compras</th>
                                    <th class="text-end pe-3">Total Gastado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topClientes as $i => $cliente)
                                <tr>
                                    <td class="ps-3">
                                        @if($i < 3)
                                            <span class="badge {{ $i === 0 ? 'bg-warning text-dark' : ($i === 1 ? 'bg-secondary' : 'bg-danger') }}">{{ $i + 1 }}</span>
                                        @else
                                            {{ $i + 1 }}
                                        @endif
                                    </td>
                                    <td><i class="bi bi-person"></i> {{ $cliente->name }}</td>
                                    <td class="text-center"><span class="badge bg-info">{{ $cliente->num_compras }}</span></td>
                                    <td class="text-end pe-3 fw-bold">${{ number_format($cliente->total_gastado, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                        <p class="mt-2">Sin clientes en este período</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico ventas por hora -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-3">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-clock"></i> Ventas por Hora del Día</div>
                <div class="card-body">
                    @if($ventasPorHora->count() > 0)
                        <canvas id="ventasPorHoraChart" height="250"></canvas>
                    @else
                        <div class="text-center py-4 text-muted"><p>Sin datos</p></div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-arrow-left-right"></i> Comparativa</div>
                <div class="card-body">
                    @if($comparativa)
                    <div class="mb-4">
                        <p class="text-muted small mb-1">Ventas período anterior</p>
                        <h4>${{ number_format($comparativa['prev_total'], 2) }}</h4>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge {{ $comparativa['cambio_total'] >= 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                                <i class="bi bi-{{ $comparativa['cambio_total'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ abs($comparativa['cambio_total']) }}%
                            </span>
                            <small class="text-muted">en ingresos</small>
                        </div>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Transacciones anterior</p>
                        <h4>{{ number_format($comparativa['prev_count']) }}</h4>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge {{ $comparativa['cambio_count'] >= 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                                <i class="bi bi-{{ $comparativa['cambio_count'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ abs($comparativa['cambio_count']) }}%
                            </span>
                            <small class="text-muted">en transacciones</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @else
    {{-- Banner para planes sin reportes avanzados --}}
    <div class="card border-warning mb-4">
        <div class="card-body text-center py-4">
            <i class="bi bi-lock text-warning" style="font-size: 2.5rem;"></i>
            <h5 class="mt-2">Reportes Avanzados</h5>
            <p class="text-muted mb-3">
                Mejora tu plan para ver: Top productos, Top clientes, Ventas por hora,
                Comparativas con períodos anteriores y más.
            </p>
            <span class="badge bg-warning text-dark">Disponible en planes superiores</span>
        </div>
    </div>
    @endif
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
                    y: { beginAtZero: true, ticks: { callback: v => '$' + v.toLocaleString() } },
                    y1: { position: 'right', beginAtZero: true, grid: { drawOnChartArea: false }, ticks: { stepSize: 1 } }
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
                    tooltip: { callbacks: { label: ctx => ctx.label + ': $' + ctx.parsed.toLocaleString(undefined, {minimumFractionDigits: 2}) } }
                }
            }
        });
    }

    @if($hasAdvanced)
    // Gráfico ventas por hora
    const porHora = @json($ventasPorHora);
    if (porHora.length > 0 && document.getElementById('ventasPorHoraChart')) {
        // Crear array de 24 horas
        const horasData = Array(24).fill(0);
        const horasCount = Array(24).fill(0);
        porHora.forEach(h => {
            horasData[Math.floor(h.hora)] = parseFloat(h.total);
            horasCount[Math.floor(h.hora)] = h.cantidad;
        });

        new Chart(document.getElementById('ventasPorHoraChart'), {
            type: 'bar',
            data: {
                labels: Array.from({length: 24}, (_, i) => i.toString().padStart(2, '0') + ':00'),
                datasets: [{
                    label: 'Ingresos ($)',
                    data: horasData,
                    backgroundColor: 'rgba(155, 89, 182, 0.6)',
                    borderColor: '#9b59b6',
                    borderWidth: 1,
                    borderRadius: 3,
                }, {
                    label: 'Transacciones',
                    data: horasCount,
                    type: 'line',
                    borderColor: '#f39c12',
                    tension: 0.3,
                    yAxisID: 'y1',
                    pointRadius: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => '$' + v.toLocaleString() } },
                    y1: { position: 'right', beginAtZero: true, grid: { drawOnChartArea: false }, ticks: { stepSize: 1 } }
                }
            }
        });
    }
    @endif
});
</script>
@endpush
