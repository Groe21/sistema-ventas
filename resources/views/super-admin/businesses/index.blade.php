@extends('layouts.app')

@section('title', 'Gestión de Negocios')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-building"></i> Gestión de Negocios
        </h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#businessModal">
            <i class="bi bi-plus-circle"></i> Nuevo Negocio
        </button>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle"></i> <strong>Error al crear negocio:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Businesses Table -->
    <div class="card">
        <div class="card-body">
            @if($businesses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Negocio</th>
                                <th>RUC</th>
                                <th>Email</th>
                                <th>Plan</th>
                                <th>Usuarios</th>
                                <th>Estado</th>
                                <th>Suscripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($businesses as $business)
                            <tr>
                                <td><strong>#{{ $business->id }}</strong></td>
                                <td>
                                    <strong>{{ $business->name }}</strong><br>
                                    <small class="text-muted">{{ $business->commercial_name }}</small>
                                </td>
                                <td><code>{{ $business->ruc }}</code></td>
                                <td>{{ $business->email }}</td>
                                <td>
                                    <span class="badge 
                                        @if(in_array($business->plan, ['premium', 'enterprise'])) bg-primary
                                        @elseif(in_array($business->plan, ['business', 'pro'])) bg-success
                                        @elseif(in_array($business->plan, ['starter', 'basic'])) bg-info
                                        @else bg-secondary
                                        @endif">
                                        {{ strtoupper($business->plan) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-dark">{{ $business->users_count }} usuarios</span>
                                </td>
                                <td>
                                    @if($business->status === 'active')
                                        <span class="badge bg-success">Activo</span>
                                    @elseif($business->status === 'suspended')
                                        <span class="badge bg-warning">Suspendido</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    @if($business->subscription_end)
                                        @if($business->hasActiveSubscription())
                                            <span class="text-success">
                                                <i class="bi bi-check-circle"></i>
                                                Hasta {{ $business->subscription_end->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-danger">
                                                <i class="bi bi-x-circle"></i>
                                                Expiró {{ $business->subscription_end->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">Sin suscripción</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-warning" title="Editar" data-bs-toggle="modal" data-bs-target="#editBusinessModal{{ $business->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" title="Eliminar" data-bs-toggle="modal" data-bs-target="#deleteBusinessModal{{ $business->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Business Modal -->
                            <div class="modal fade" id="editBusinessModal{{ $business->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><i class="bi bi-pencil"></i> Editar: {{ $business->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('super-admin.businesses.update', $business) }}">
                                            @csrf @method('PUT')
                                            <div class="modal-body">
                                                <h6 class="text-muted mb-3"><i class="bi bi-building"></i> Datos del Negocio</h6>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nombre del Negocio *</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $business->name }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">RUC *</label>
                                                        <input type="text" name="ruc" class="form-control" maxlength="13" value="{{ $business->ruc }}" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Email *</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $business->email }}" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Teléfono</label>
                                                        <input type="text" name="phone" class="form-control" value="{{ $business->phone }}">
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Dirección</label>
                                                    <input type="text" name="address" class="form-control" value="{{ $business->address }}">
                                                </div>

                                                <hr>
                                                <h6 class="text-muted mb-3"><i class="bi bi-star"></i> Plan y Estado</h6>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Plan *</label>
                                                        <select name="plan_id" class="form-select" required>
                                                            @foreach($plans as $plan)
                                                                <option value="{{ $plan->id }}" {{ $business->plan === $plan->slug ? 'selected' : '' }}>
                                                                    {{ $plan->name }} - ${{ number_format($plan->price, 2) }}/mes
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Estado *</label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="active" {{ $business->status === 'active' ? 'selected' : '' }}>Activo</option>
                                                            <option value="inactive" {{ $business->status === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                                            <option value="suspended" {{ $business->status === 'suspended' ? 'selected' : '' }}>Suspendido</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Guardar Cambios</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Business Modal -->
                            <div class="modal fade" id="deleteBusinessModal{{ $business->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Eliminar Negocio</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Está seguro de eliminar el negocio <strong>{{ $business->name }}</strong>?</p>
                                            <div class="alert alert-danger">
                                                <i class="bi bi-exclamation-triangle"></i> Esta acción eliminará también todos los usuarios ({{ $business->users_count }}) y suscripciones asociadas. No se puede deshacer.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <form method="POST" action="{{ route('super-admin.businesses.destroy', $business) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Eliminar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $businesses->links() }}
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-building" style="font-size: 4rem;"></i>
                    <p class="mt-3">No hay negocios registrados</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#businessModal">
                        <i class="bi bi-plus-circle"></i> Crear Primer Negocio
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Create Business Modal -->
<div class="modal fade" id="businessModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-building"></i> Nuevo Negocio
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('super-admin.businesses.store') }}">
                @csrf
                <div class="modal-body">
                    <!-- Datos del Negocio -->
                    <h6 class="text-muted mb-3"><i class="bi bi-building"></i> Datos del Negocio</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Negocio *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">RUC *</label>
                            <input type="text" name="ruc" class="form-control" maxlength="13" required>
                            <small class="text-muted">13 dígitos</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email del Negocio *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="address" class="form-control">
                    </div>

                    <hr>

                    <!-- Datos del Administrador -->
                    <h6 class="text-muted mb-3"><i class="bi bi-person-badge"></i> Administrador del Negocio</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Administrador *</label>
                            <input type="text" name="admin_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email del Administrador *</label>
                            <input type="email" name="admin_email" class="form-control" required>
                            <small class="text-muted">Se usará para iniciar sesión</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contraseña *</label>
                            <input type="password" name="admin_password" class="form-control" minlength="6" required>
                            <small class="text-muted">Mínimo 6 caracteres</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirmar Contraseña *</label>
                            <input type="password" name="admin_password_confirmation" class="form-control" minlength="6" required>
                        </div>
                    </div>

                    <hr>

                    <!-- Plan y Estado -->
                    <h6 class="text-muted mb-3"><i class="bi bi-star"></i> Plan y Estado</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Plan *</label>
                            <select name="plan_id" class="form-select" required>
                                <option value="">Seleccionar plan...</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">
                                        {{ $plan->name }} - ${{ number_format($plan->price, 2) }}/mes
                                        ({{ $plan->user_limit ?? '∞' }} usuarios, {{ $plan->product_limit ?? '∞' }} productos)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <select name="status" class="form-select">
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                                <option value="suspended">Suspendido</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Nota:</strong> Se creará el negocio con su administrador y una suscripción de 30 días de prueba gratis.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Crear Negocio
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
