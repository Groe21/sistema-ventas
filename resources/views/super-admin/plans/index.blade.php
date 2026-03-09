@extends('layouts.app')
@section('title', 'Gestión de Planes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-collection"></i> Planes del Sistema</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPlanModal">
        <i class="bi bi-plus-lg"></i> <span class="btn-text-mobile">Nuevo Plan</span>
    </button>
</div>

<!-- Stats -->
<div class="row stats-row g-2 mb-3">
    @php $totalPlans = $plans->count(); $activePlans = $plans->where('is_active', true)->count(); @endphp
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="text-muted small">Total Planes</div>
                <div class="fw-bold fs-4">{{ $totalPlans }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="text-muted small">Activos</div>
                <div class="fw-bold fs-4 text-success">{{ $activePlans }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Plans Cards -->
<div class="row g-3">
    @forelse($plans as $plan)
    <div class="col-12 col-md-4">
        <div class="card h-100 {{ !$plan->is_active ? 'opacity-50' : '' }}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">{{ $plan->name }}</h5>
                    <small class="text-muted">{{ $plan->slug }}</small>
                </div>
                @if(!$plan->is_active)
                    <span class="badge bg-secondary">Inactivo</span>
                @endif
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <span class="display-6 fw-bold text-primary">${{ number_format($plan->price, 2) }}</span>
                    <span class="text-muted">/mes</span>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="bi bi-people"></i> Usuarios</span>
                        <strong>{{ $plan->user_limit === 0 ? 'Ilimitados' : $plan->user_limit }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="bi bi-box-seam"></i> Productos</span>
                        <strong>{{ $plan->product_limit === 0 ? 'Ilimitados' : number_format($plan->product_limit) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="bi bi-building"></i> Suscripciones</span>
                        <strong>{{ $plan->subscriptions_count }}</strong>
                    </div>
                </div>

                @if($plan->features)
                <div class="mb-3">
                    <small class="text-muted fw-bold">Características:</small>
                    <ul class="list-unstyled mb-0 mt-1">
                        @foreach($plan->features as $feature)
                        <li><i class="bi bi-check-circle text-success"></i> {{ str_replace('_', ' ', ucfirst($feature)) }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-outline-primary flex-fill" data-bs-toggle="modal" data-bs-target="#editPlanModal{{ $plan->id }}">
                        <i class="bi bi-pencil"></i> Editar
                    </button>
                    @if($plan->subscriptions_count === 0)
                    <form method="POST" action="{{ route('super-admin.plans.destroy', $plan) }}" onsubmit="return confirm('¿Eliminar este plan?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editPlanModal{{ $plan->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('super-admin.plans.update', $plan) }}">
                @csrf @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Plan: {{ $plan->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="name" class="form-control" value="{{ $plan->name }}" required>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label">Precio ($/mes)</label>
                                <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ $plan->price }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Estado</label>
                                <select name="is_active" class="form-select">
                                    <option value="1" {{ $plan->is_active ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ !$plan->is_active ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="row g-2 mt-1">
                            <div class="col-6">
                                <label class="form-label">Límite Usuarios <small class="text-muted">(0=ilimitado)</small></label>
                                <input type="number" name="user_limit" class="form-control" min="0" value="{{ $plan->user_limit }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Límite Productos <small class="text-muted">(0=ilimitado)</small></label>
                                <input type="number" name="product_limit" class="form-control" min="0" value="{{ $plan->product_limit }}" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Características</label>
                            @php
                            $allFeatures = [
                                'pos' => 'Punto de Venta',
                                'products' => 'Gestión de Productos',
                                'inventory' => 'Inventario Básico',
                                'customers' => 'Clientes',
                                'basic_reports' => 'Reportes Básicos',
                                'cash_register' => 'Gestión de Caja',
                                'advanced_reports' => 'Reportes Avanzados',
                                'export_excel' => 'Exportar a Excel',
                                'export_pdf' => 'Exportar a PDF',
                                'advanced_dashboard' => 'Dashboard Avanzado',
                                'low_stock_alerts' => 'Alertas de Stock Bajo',
                                'loyalty_points' => 'Sistema de Puntos',
                                'customer_portal' => 'Portal de Clientes',
                                'promotions' => 'Promociones',
                            ];
                            @endphp
                            @foreach($allFeatures as $key => $label)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="features[]" value="{{ $key }}" id="edit_feat_{{ $plan->id }}_{{ $key }}"
                                    {{ in_array($key, $plan->features ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label" for="edit_feat_{{ $plan->id }}_{{ $key }}">{{ $label }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No hay planes creados. Crea el primer plan para comenzar.
        </div>
    </div>
    @endforelse
</div>

<!-- Create Plan Modal -->
<div class="modal fade" id="createPlanModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('super-admin.plans.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle"></i> Nuevo Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="name" class="form-control" placeholder="Ej: Starter" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Slug (identificador)</label>
                            <input type="text" name="slug" class="form-control" placeholder="Ej: starter" required>
                        </div>
                    </div>
                    <div class="row g-2 mt-1">
                        <div class="col-4">
                            <label class="form-label">Precio ($/mes)</label>
                            <input type="number" name="price" class="form-control" step="0.01" min="0" value="0" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Límite Usuarios</label>
                            <input type="number" name="user_limit" class="form-control" min="0" value="2" required>
                            <small class="text-muted">0 = ilimitado</small>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Límite Productos</label>
                            <input type="number" name="product_limit" class="form-control" min="0" value="500" required>
                            <small class="text-muted">0 = ilimitado</small>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Características</label>
                        @foreach($allFeatures as $key => $label)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="features[]" value="{{ $key }}" id="create_feat_{{ $key }}">
                            <label class="form-check-label" for="create_feat_{{ $key }}">{{ $label }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Crear Plan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
