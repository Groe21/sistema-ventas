@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h2 class="mb-0"><i class="bi bi-speedometer2"></i> Dashboard</h2>
            <small class="text-muted d-none d-md-inline">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</small>
            <small class="text-muted d-md-none">{{ now()->locale('es')->isoFormat('ddd, D MMM YYYY') }}</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if($openCash)
                <span class="badge bg-success d-flex align-items-center"><i class="bi bi-unlock me-1"></i> <span class="d-none d-sm-inline">Caja </span>Abierta</span>
            @else
                <span class="badge bg-secondary d-flex align-items-center"><i class="bi bi-lock me-1"></i> <span class="d-none d-sm-inline">Caja </span>Cerrada</span>
            @endif
            <a href="{{ route('pos.index') }}" class="btn btn-primary btn-sm"><i class="bi bi-cart-plus"></i> <span class="d-none d-sm-inline">Nueva </span>Venta</a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4 stats-row">
        <div class="col-xl-3 col-md-6 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase">Ventas Hoy</p>
                            <h3 class="fw-bold mb-0">${{ number_format($stats['today_sales'], 2) }}</h3>
                            <small class="text-muted">{{ $stats['today_invoices'] }} factura(s)</small>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-cash-coin text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase">Ventas del Mes</p>
                            <h3 class="fw-bold mb-0">${{ number_format($stats['month_sales'], 2) }}</h3>
                            <small class="text-muted">{{ now()->locale('es')->isoFormat('MMMM YYYY') }}</small>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="bi bi-graph-up-arrow text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase">Valor Inventario</p>
                            <h3 class="fw-bold mb-0">${{ number_format($stats['inventory_value'], 2) }}</h3>
                            <small class="text-muted">{{ $stats['total_products'] }} productos activos</small>
                        </div>
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="bi bi-box-seam text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 col-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase">Alertas Stock</p>
                            <h3 class="fw-bold mb-0 {{ $stats['low_stock_products'] > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $stats['low_stock_products'] }}
                            </h3>
                            <small class="text-muted">{{ $stats['total_customers'] }} clientes</small>
                        </div>
                        <div class="rounded-circle {{ $stats['low_stock_products'] > 0 ? 'bg-danger' : 'bg-success' }} bg-opacity-10 p-3">
                            <i class="bi bi-exclamation-triangle {{ $stats['low_stock_products'] > 0 ? 'text-danger' : 'text-success' }} fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Productos del Mes --}}
    <div class="row mb-4">
        <div class="col-lg-5 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-trophy"></i> Más Vendidos (Mes)</h6>
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary">Ver ventas</a>
                </div>
                <div class="card-body p-0">
                    @if($topProducts->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($topProducts as $i => $tp)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary rounded-circle me-2" style="width:28px;height:28px;line-height:20px;">{{ $i + 1 }}</span>
                                    <div>
                                        <div class="fw-semibold">{{ Str::limit($tp->product_name, 25) }}</div>
                                        <small class="text-muted">{{ $tp->qty }} uds vendidas</small>
                                    </div>
                                </div>
                                <span class="text-success fw-bold">${{ number_format($tp->revenue, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mt-2 mb-0">Sin datos este mes</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Resumen Ventas 7 Días --}}
        <div class="col-lg-7 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0"><i class="bi bi-bar-chart"></i> Ventas Últimos 7 Días</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @for($i = 6; $i >= 0; $i--)
                            @php $val = $chartData[6 - $i] ?? 0; @endphp
                            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <span>{{ $chartLabels[6 - $i] ?? '' }}</span>
                                <span class="fw-bold {{ $val > 0 ? 'text-success' : 'text-muted' }}">${{ number_format($val, 2) }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ventas Recientes + Stock Bajo + Accesos Rápidos --}}
    <div class="row">
        <div class="col-lg-7 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-receipt"></i> Ventas Recientes</h6>
                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary">Historial</a>
                </div>
                <div class="card-body p-0">
                    @if($recentSales->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Factura</th>
                                        <th class="hide-mobile">Cliente</th>
                                        <th class="text-end">Total</th>
                                        <th class="hide-mobile">Pago</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSales as $sale)
                                    <tr style="cursor:pointer" onclick="window.location='{{ route('sales.show', $sale) }}'">
                                        <td><code>{{ $sale->invoice_number }}</code></td>
                                        <td class="hide-mobile">{{ Str::limit($sale->customer->name ?? 'Consumidor Final', 20) }}</td>
                                        <td class="text-end fw-bold">${{ number_format($sale->total, 2) }}</td>
                                        <td class="hide-mobile">
                                            @switch($sale->payment_method)
                                                @case('cash') <span class="badge bg-success">Efectivo</span> @break
                                                @case('card') <span class="badge bg-info">Tarjeta</span> @break
                                                @case('transfer') <span class="badge bg-warning text-dark">Transfer.</span> @break
                                            @endswitch
                                        </td>
                                        <td><small class="text-muted">{{ $sale->sale_date->diffForHumans() }}</small></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-inbox" style="font-size:3rem;"></i>
                            <p class="mt-2 mb-0">No hay ventas registradas aún</p>
                            <a href="{{ route('pos.index') }}" class="btn btn-primary mt-3">Realizar primera venta</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-3">
            {{-- Stock Bajo --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-exclamation-triangle text-danger"></i> Stock Bajo</h6>
                    <a href="{{ route('products.index', ['stock_filter' => 'low']) }}" class="btn btn-sm btn-outline-danger">Ver todos</a>
                </div>
                <div class="card-body p-0">
                    @if($lowStockProducts->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($lowStockProducts as $p)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $p->name }}</div>
                                    <small class="text-muted">{{ $p->code }} &middot; Min: {{ $p->min_stock }}</small>
                                </div>
                                @if($p->stock == 0)
                                    <span class="badge bg-danger">Agotado</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $p->stock }} uds</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-success py-3">
                            <i class="bi bi-check-circle fs-3"></i>
                            <p class="mb-0 mt-1">Todo el stock en orden</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Accesos Rápidos --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0"><i class="bi bi-lightning"></i> Accesos Rápidos</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('pos.index') }}" class="btn btn-primary">
                            <i class="bi bi-cart3"></i> Punto de Venta
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-box-seam"></i> Productos e Inventario
                        </a>
                        <a href="{{ route('customers.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-people"></i> Clientes
                        </a>
                        <a href="{{ route('cash.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-cash-stack"></i> Caja Registradora
                        </a>
                        <a href="{{ route('sales.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-receipt"></i> Historial de Ventas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
