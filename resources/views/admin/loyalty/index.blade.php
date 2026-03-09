@extends('layouts.app')
@section('title', 'Programa de Fidelización')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-star"></i> Programa de Fidelización</h2>
</div>

@if(!in_array('loyalty_points', $planFeatures ?? []))
<div class="alert alert-warning">
    <i class="bi bi-lock"></i> <strong>Funcionalidad Premium.</strong> Tu plan actual no incluye el sistema de puntos de fidelización. Contacta al administrador para mejorar tu plan.
</div>
@else

<!-- Stats -->
<div class="row stats-row g-2 mb-3">
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="text-muted small">Clientes con Puntos</div>
                <div class="fw-bold fs-4">{{ $stats['total_customers_with_points'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="text-muted small">Puntos Emitidos</div>
                <div class="fw-bold fs-4 text-success">{{ number_format($stats['total_points_issued']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="text-muted small">Puntos Canjeados</div>
                <div class="fw-bold fs-4 text-info">{{ number_format($stats['total_points_redeemed']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="text-muted small">Saldo Total</div>
                <div class="fw-bold fs-4 text-primary">{{ number_format($stats['total_points_balance']) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Info -->
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-info-circle text-primary"></i>
            <span>Regla activa: <strong>$1 = 1 punto</strong>. Los puntos se acumulan automáticamente con cada venta.</span>
        </div>
    </div>
</div>

<!-- Search -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-9 col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar por nombre o identificación..." value="{{ request('search') }}">
            </div>
            <div class="col-3 col-md-2">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th class="hide-mobile">Identificación</th>
                    <th class="text-center">Puntos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customerPoints as $cp)
                <tr>
                    <td>
                        <strong>{{ $cp->customer->name }}</strong>
                        <div class="small text-muted d-md-none">{{ $cp->customer->identification }}</div>
                    </td>
                    <td class="hide-mobile">{{ $cp->customer->identification ?? '—' }}</td>
                    <td class="text-center">
                        <span class="badge bg-primary fs-6">{{ number_format($cp->points_balance) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('loyalty.history', $cp->customer) }}" class="btn btn-sm btn-outline-info" title="Historial">
                            <i class="bi bi-clock-history"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#adjustModal{{ $cp->customer_id }}" title="Ajustar">
                            <i class="bi bi-plus-slash-minus"></i>
                        </button>
                    </td>
                </tr>

                <!-- Adjust Modal -->
                <div class="modal fade" id="adjustModal{{ $cp->customer_id }}" tabindex="-1">
                    <div class="modal-dialog modal-sm">
                        <form method="POST" action="{{ route('loyalty.adjust', $cp->customer) }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title">Ajustar Puntos: {{ $cp->customer->name }}</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="small text-muted">Saldo actual: <strong>{{ number_format($cp->points_balance) }} pts</strong></p>
                                    <div class="mb-2">
                                        <label class="form-label">Tipo</label>
                                        <select name="type" class="form-select form-select-sm">
                                            <option value="add">Agregar puntos</option>
                                            <option value="subtract">Restar puntos</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Cantidad</label>
                                        <input type="number" name="points" class="form-control form-control-sm" min="1" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Descripción</label>
                                        <input type="text" name="description" class="form-control form-control-sm" placeholder="Motivo del ajuste" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-sm">Aplicar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">
                        <i class="bi bi-star fs-3 d-block mb-2"></i>
                        Aún no hay clientes con puntos. Los puntos se generan automáticamente al realizar ventas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customerPoints->hasPages())
    <div class="card-footer">{{ $customerPoints->withQueryString()->links() }}</div>
    @endif
</div>
@endif
@endsection
