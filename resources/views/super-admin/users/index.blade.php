@extends('layouts.app')

@section('title', 'Usuarios del Sistema')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h2 class="mb-0"><i class="bi bi-people-fill"></i> Usuarios del Sistema</h2>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="bi bi-person-plus"></i> <span class="d-none d-sm-inline">Nuevo Usuario</span>
        </button>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats --}}
    <div class="row g-2 mb-3 stats-row">
        <div class="col-6 col-md-3 col-lg">
            <div class="card stat-card">
                <div class="card-body py-2 px-3 text-center">
                    <div class="stat-value text-primary">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg">
            <div class="card stat-card" style="border-left-color: #9b59b6;">
                <div class="card-body py-2 px-3 text-center">
                    <div class="stat-value" style="color:#9b59b6;">{{ $stats['super_admins'] }}</div>
                    <div class="stat-label">Super Admin</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg">
            <div class="card stat-card" style="border-left-color: #e74c3c;">
                <div class="card-body py-2 px-3 text-center">
                    <div class="stat-value" style="color:#e74c3c;">{{ $stats['admins'] }}</div>
                    <div class="stat-label">Admins</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg">
            <div class="card stat-card" style="border-left-color: #2ecc71;">
                <div class="card-body py-2 px-3 text-center">
                    <div class="stat-value" style="color:#2ecc71;">{{ $stats['employees'] }}</div>
                    <div class="stat-label">Empleados</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg">
            <div class="card stat-card" style="border-left-color: #f39c12;">
                <div class="card-body py-2 px-3 text-center">
                    <div class="stat-value" style="color:#f39c12;">{{ $stats['active'] }}</div>
                    <div class="stat-label">Activos</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body py-2 px-3">
            <form method="GET" action="{{ route('super-admin.users.index') }}" class="row g-2 align-items-center">
                <div class="col">
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Buscar nombre, email, teléfono..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <select name="role" class="form-select form-select-sm" style="width:auto;">
                        <option value="">Todos los roles</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Empleado</option>
                    </select>
                </div>
                <div class="col-auto hide-mobile">
                    <select name="business_id" class="form-select form-select-sm" style="width:auto;">
                        <option value="">Todos los negocios</option>
                        @foreach($businesses as $biz)
                            <option value="{{ $biz->id }}" {{ request('business_id') == $biz->id ? 'selected' : '' }}>{{ $biz->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm" style="width:auto;">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activos</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-search"></i></button>
                    @if(request()->hasAny(['search','role','status','business_id']))
                    <a href="{{ route('super-admin.users.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Usuarios --}}
    <div class="card">
        <div class="card-body p-0">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th class="hide-mobile">Email</th>
                                <th class="hide-mobile hide-tablet">Negocio</th>
                                <th class="hide-mobile hide-tablet">Teléfono</th>
                                <th class="text-center">Rol</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white"
                                             style="width:32px;height:32px;font-size:.8rem;
                                             background:{{ $user->role === 'super_admin' ? '#9b59b6' : ($user->role === 'admin' ? '#e74c3c' : '#3498db') }};">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            <span class="d-block d-md-none small text-muted">{{ $user->email }}</span>
                                            @if($user->identification)
                                                <small class="d-block text-muted">CI: {{ $user->identification }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="hide-mobile">{{ $user->email }}</td>
                                <td class="hide-mobile hide-tablet">
                                    @if($user->business)
                                        <span class="badge bg-dark">{{ $user->business->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="hide-mobile hide-tablet">{{ $user->phone ?? '-' }}</td>
                                <td class="text-center">
                                    @if($user->role === 'super_admin')
                                        <span class="badge bg-purple" style="background:#9b59b6!important;">Super Admin</span>
                                    @elseif($user->role === 'admin')
                                        <span class="badge bg-danger">Admin</span>
                                    @else
                                        <span class="badge bg-info">Empleado</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($user->is_active)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editUserModal{{ $user->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('super-admin.users.destroy', $user) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('¿Eliminar usuario {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Editar Usuario --}}
                            <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header py-2">
                                            <h6 class="modal-title"><i class="bi bi-pencil"></i> Editar: {{ $user->name }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('super-admin.users.update', $user) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row g-2">
                                                    <div class="col-12 col-sm-6">
                                                        <label class="form-label small">Nombre *</label>
                                                        <input type="text" name="name" class="form-control form-control-sm"
                                                               value="{{ $user->name }}" required>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <label class="form-label small">Email *</label>
                                                        <input type="email" name="email" class="form-control form-control-sm"
                                                               value="{{ $user->email }}" required>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <label class="form-label small">Teléfono</label>
                                                        <input type="text" name="phone" class="form-control form-control-sm"
                                                               value="{{ $user->phone }}">
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <label class="form-label small">Cédula/RUC</label>
                                                        <input type="text" name="identification" class="form-control form-control-sm"
                                                               value="{{ $user->identification }}" maxlength="13">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">Dirección</label>
                                                        <input type="text" name="address" class="form-control form-control-sm"
                                                               value="{{ $user->address }}">
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <label class="form-label small">Rol *</label>
                                                        <select name="role" class="form-select form-select-sm" required>
                                                            <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrador</option>
                                                            <option value="employee" {{ $user->role === 'employee' ? 'selected' : '' }}>Empleado</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <label class="form-label small">Negocio</label>
                                                        <select name="business_id" class="form-select form-select-sm">
                                                            <option value="">Sin negocio</option>
                                                            @foreach($businesses as $biz)
                                                                <option value="{{ $biz->id }}" {{ $user->business_id == $biz->id ? 'selected' : '' }}>{{ $biz->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <label class="form-label small">Estado</label>
                                                        <select name="is_active" class="form-select form-select-sm">
                                                            <option value="1" {{ $user->is_active ? 'selected' : '' }}>Activo</option>
                                                            <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactivo</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <label class="form-label small">Nueva Contraseña <small class="text-muted">(vacío = no cambiar)</small></label>
                                                        <input type="password" name="password" class="form-control form-control-sm"
                                                               minlength="6" autocomplete="new-password">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer py-2">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-save"></i> Actualizar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $users->withQueryString()->links() }}</div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-people" style="font-size: 3rem;"></i>
                    <p class="mt-2">No se encontraron usuarios</p>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="bi bi-person-plus"></i> Crear Primer Usuario
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Crear Usuario --}}
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title"><i class="bi bi-person-plus"></i> Nuevo Usuario</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('super-admin.users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small">Nombre Completo *</label>
                            <input type="text" name="name" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small">Email *</label>
                            <input type="email" name="email" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small">Contraseña *</label>
                            <input type="password" name="password" class="form-control form-control-sm"
                                   minlength="6" required autocomplete="new-password">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small">Rol *</label>
                            <select name="role" class="form-select form-select-sm" required>
                                <option value="employee">Empleado</option>
                                <option value="admin">Administrador</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small">Negocio</label>
                            <select name="business_id" class="form-select form-select-sm">
                                <option value="">Sin negocio (Super Admin)</option>
                                @foreach($businesses as $biz)
                                    <option value="{{ $biz->id }}">{{ $biz->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small">Teléfono</label>
                            <input type="text" name="phone" class="form-control form-control-sm">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small">Cédula/RUC</label>
                            <input type="text" name="identification" class="form-control form-control-sm" maxlength="13">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small">Dirección</label>
                            <input type="text" name="address" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-save"></i> Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
