@extends('layouts.app')

@section('title', 'Historial de Ventas')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h2 class="mb-0"><i class="bi bi-receipt"></i> <span class="d-none d-sm-inline">Historial de </span>Ventas</h2>
        <a href="{{ route('pos.index') }}" class="btn btn-primary btn-sm"><i class="bi bi-cart-plus"></i> <span class="btn-text-mobile">Nueva Venta</span></a>
    </div>

    {{-- Stats --}}
    <div class="row mb-3 stats-row">
        <div class="col-md-4 col-6 mb-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2 px-3">
                    <small class="text-muted text-uppercase">Hoy</small>
                    <h5 class="mb-0 text-success">${{ number_format($todaySales, 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-6 mb-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2 px-3">
                    <small class="text-muted text-uppercase">Período</small>
                    <h5 class="mb-0">${{ number_format($totalSales, 2) }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12 mb-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-2 px-3">
                    <small class="text-muted text-uppercase">Facturas</small>
                    <h5 class="mb-0">{{ $totalCount }}</h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('sales.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar factura, cliente..." value="{{ request('search') }}">
                </div>
                <div class="col-6 col-md-2">
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-6 col-md-2">
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>
                <div class="col-6 col-md-2">
                    <select name="payment_method" class="form-select form-select-sm">
                        <option value="">Pago</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Efectivo</option>
                        <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Tarjeta</option>
                        <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer.</option>
                    </select>
                </div>
                <div class="col-6 col-md-1">
                    <button type="submit" class="btn btn-secondary btn-sm w-100"><i class="bi bi-search"></i></button>
                </div>
                @if(request()->hasAny(['search','date_from','date_to','payment_method','status']))
                <div class="col-auto">
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
                </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card">
        <div class="card-body p-0 p-md-3">
            @if($sales->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Factura</th>
                                <th class="hide-mobile">Fecha</th>
                                <th class="hide-tablet">Cliente</th>
                                <th class="text-end">Total</th>
                                <th class="text-center hide-mobile">Pago</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                            <tr style="cursor:pointer" onclick="window.location='{{ route('sales.show', $sale) }}'">
                                <td>
                                    <code>{{ $sale->invoice_number }}</code>
                                    <span class="d-md-none d-block"><small class="text-muted">{{ $sale->sale_date->format('d/m/y H:i') }}</small></span>
                                </td>
                                <td class="hide-mobile"><small>{{ $sale->sale_date->format('d/m/Y H:i') }}</small></td>
                                <td class="hide-tablet">{{ Str::limit($sale->customer->name ?? '-', 20) }}</td>
                                <td class="text-end fw-bold">${{ number_format($sale->total, 2) }}</td>
                                <td class="text-center hide-mobile">
                                    @switch($sale->payment_method)
                                        @case('cash') <span class="badge bg-success">Efect.</span> @break
                                        @case('card') <span class="badge bg-info">Tarjeta</span> @break
                                        @case('transfer') <span class="badge bg-warning text-dark">Transf.</span> @break
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    @if($sale->status === 'completed')
                                        <span class="badge bg-success">OK</span>
                                    @else
                                        <span class="badge bg-danger">Anulada</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-2">{{ $sales->withQueryString()->links() }}</div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox" style="font-size:3rem;"></i>
                    <p class="mt-2">No hay ventas</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
