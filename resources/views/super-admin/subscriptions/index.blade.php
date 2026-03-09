@extends('layouts.app')
@section('title', 'Suscripciones')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-credit-card"></i> Suscripciones</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubModal">
        <i class="bi bi-plus-lg"></i> <span class="btn-text-mobile">Asignar Plan</span>
    </button>
</div>

<!-- Stats -->
<div class="row stats-row g-2 mb-3">
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="text-muted small">Total</div>
                <div class="fw-bold fs-4">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="text-muted small">Activas</div>
                <div class="fw-bold fs-4 text-success">{{ $stats['active'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="text-muted small">Prueba</div>
                <div class="fw-bold fs-4 text-warning">{{ $stats['trial'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="text-muted small">Expiradas</div>
                <div class="fw-bold fs-4 text-danger">{{ $stats['expired'] }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Buscar negocio..." value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Estado</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activa</option>
                    <option value="trial" {{ request('status') === 'trial' ? 'selected' : '' }}>Prueba</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expirada</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="plan_id" class="form-select form-select-sm">
                    <option value="">Plan</option>
                    @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4 d-flex gap-1">
                <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Filtrar</button>
                <a href="{{ route('super-admin.subscriptions.index') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
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
                    <th>Negocio</th>
                    <th class="hide-mobile">Plan</th>
                    <th>Estado</th>
                    <th class="hide-mobile">Inicio</th>
                    <th>Vence</th>
                    <th class="hide-mobile">Días Rest.</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $sub)
                <tr>
                    <td>
                        <strong>{{ $sub->business->name ?? 'N/A' }}</strong>
                        <div class="small text-muted d-md-none">{{ $sub->plan->name ?? 'N/A' }}</div>
                    </td>
                    <td class="hide-mobile">
                        <span class="badge bg-info">{{ $sub->plan->name ?? 'N/A' }}</span>
                    </td>
                    <td>
                        @if($sub->isActive() && $sub->status === 'active')
                            <span class="badge bg-success">Activa</span>
                        @elseif($sub->isTrial() && !$sub->isExpired())
                            <span class="badge bg-warning text-dark">Prueba</span>
                        @else
                            <span class="badge bg-danger">Expirada</span>
                        @endif
                    </td>
                    <td class="hide-mobile">{{ $sub->starts_at->format('d/m/Y') }}</td>
                    <td>{{ $sub->ends_at->format('d/m/Y') }}</td>
                    <td class="hide-mobile">
                        @if($sub->isExpired())
                            <span class="text-danger fw-bold">Vencida</span>
                        @else
                            <span class="{{ $sub->daysRemaining() <= 7 ? 'text-warning fw-bold' : '' }}">{{ $sub->daysRemaining() }} días</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            @if(!$sub->isActive())
                            <form method="POST" action="{{ route('super-admin.subscriptions.activate', $sub) }}">
                                @csrf
                                <button class="btn btn-sm btn-success" title="Activar"><i class="bi bi-check-circle"></i></button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('super-admin.subscriptions.deactivate', $sub) }}">
                                @csrf
                                <button class="btn btn-sm btn-warning" title="Desactivar"><i class="bi bi-pause-circle"></i></button>
                            </form>
                            @endif
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#renewModal{{ $sub->id }}" title="Renovar">
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Renew Modal -->
                <div class="modal fade" id="renewModal{{ $sub->id }}" tabindex="-1">
                    <div class="modal-dialog modal-sm">
                        <form method="POST" action="{{ route('super-admin.subscriptions.renew', $sub) }}">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title">Renovar: {{ $sub->business->name }}</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">Duración (días)</label>
                                    <input type="number" name="duration_days" class="form-control" value="30" min="1" max="730" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-sm">Renovar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>No hay suscripciones registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($subscriptions->hasPages())
    <div class="card-footer">{{ $subscriptions->withQueryString()->links() }}</div>
    @endif
</div>

<!-- Create Subscription Modal -->
<div class="modal fade" id="createSubModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('super-admin.subscriptions.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Asignar Plan a Negocio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Negocio</label>
                        <select name="business_id" class="form-select" required>
                            <option value="">Seleccionar negocio...</option>
                            @foreach($businesses as $biz)
                            <option value="{{ $biz->id }}">{{ $biz->name }} ({{ $biz->ruc }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Plan</label>
                        <select name="plan_id" class="form-select" required>
                            <option value="">Seleccionar plan...</option>
                            @foreach($plans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ number_format($plan->price, 2) }}/mes</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Estado</label>
                            <select name="status" class="form-select">
                                <option value="active">Activa</option>
                                <option value="trial">Prueba</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Duración (días)</label>
                            <input type="number" name="duration_days" class="form-control" value="30" min="1" max="730" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Asignar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
