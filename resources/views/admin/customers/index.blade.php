@extends('layouts.app')

@section('title', 'Gestión de Clientes')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h2 class="mb-0"><i class="bi bi-people"></i> Clientes</h2>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#customerModal">
            <i class="bi bi-plus-circle"></i> <span class="d-none d-sm-inline">Nuevo Cliente</span>
        </button>
    </div>

    {{-- Estadísticas rápidas --}}
    <div class="row mb-3">
        <div class="col-4">
            <div class="card stat-card" style="border-left-color: #3498db;">
                <div class="card-body py-2 px-3">
                    <div class="stat-label" style="font-size: .75rem;">Total Clientes</div>
                    <div class="stat-value" style="font-size: 1.3rem;">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card stat-card" style="border-left-color: #2ecc71;">
                <div class="card-body py-2 px-3">
                    <div class="stat-label" style="font-size: .75rem;">Activos</div>
                    <div class="stat-value" style="font-size: 1.3rem;">{{ $stats['activos'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card stat-card" style="border-left-color: #9b59b6;">
                <div class="card-body py-2 px-3">
                    <div class="stat-label" style="font-size: .75rem;">Con Email</div>
                    <div class="stat-value" style="font-size: 1.3rem;">{{ $stats['con_email'] }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Búsqueda --}}
    <div class="card mb-3">
        <div class="card-body py-2 px-3">
            <form method="GET" action="{{ route('customers.index') }}" class="row g-2 align-items-center">
                <div class="col">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm"
                               placeholder="Buscar por cédula, nombre, email o teléfono..."
                               value="{{ request('search') }}" autofocus>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
                    @if(request('search'))
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla de Clientes --}}
    <div class="card">
        <div class="card-body p-0">
            @if($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Identificación</th>
                                <th class="hide-mobile">Email <i class="bi bi-envelope-at text-muted" title="Para factura electrónica"></i></th>
                                <th>Teléfono</th>
                                <th class="hide-mobile hide-tablet">Ciudad</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr>
                                <td>
                                    <strong>{{ $customer->name }}</strong>
                                    {{-- Info extra visible solo en móvil --}}
                                    <span class="d-block d-md-none">
                                        <small class="text-muted">
                                            <span class="badge bg-info" style="font-size:.6rem;">
                                                {{ strtoupper(str_replace('_', ' ', $customer->identification_type)) }}
                                            </span>
                                            {{ $customer->identification ?? '' }}
                                        </small>
                                        @if($customer->email)
                                            <br><small class="text-muted"><i class="bi bi-envelope"></i> {{ $customer->email }}</small>
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info" style="font-size:.65rem;">
                                        {{ strtoupper(str_replace('_', ' ', $customer->identification_type)) }}
                                    </span>
                                    <strong>{{ $customer->identification ?? '—' }}</strong>
                                </td>
                                <td class="hide-mobile">
                                    @if($customer->email)
                                        <i class="bi bi-envelope-check text-success"></i> {{ $customer->email }}
                                    @else
                                        <span class="text-muted"><i class="bi bi-envelope-x text-danger"></i> Sin email</span>
                                    @endif
                                </td>
                                <td>{{ $customer->phone ?? '-' }}</td>
                                <td class="hide-mobile hide-tablet">{{ $customer->city ?? '-' }}</td>
                                <td class="text-center">
                                    @if($customer->is_active)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $customer->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('¿Eliminar cliente {{ $customer->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Editar (por cada cliente) --}}
                            <div class="modal fade" id="editModal{{ $customer->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header py-2">
                                            <h6 class="modal-title"><i class="bi bi-pencil"></i> Editar: {{ $customer->name }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('customers.update', $customer) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row g-2">
                                                    <div class="col-12 col-sm-6">
                                                        <label class="form-label small">Nombre *</label>
                                                        <input type="text" name="name" class="form-control form-control-sm"
                                                               value="{{ $customer->name }}" required>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <label class="form-label small">Tipo ID *</label>
                                                        <select name="identification_type" class="form-select form-select-sm" required>
                                                            <option value="consumidor_final" {{ $customer->identification_type == 'consumidor_final' ? 'selected' : '' }}>Consumidor Final</option>
                                                            <option value="cedula" {{ $customer->identification_type == 'cedula' ? 'selected' : '' }}>Cédula</option>
                                                            <option value="ruc" {{ $customer->identification_type == 'ruc' ? 'selected' : '' }}>RUC</option>
                                                            <option value="pasaporte" {{ $customer->identification_type == 'pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <label class="form-label small">N° Identificación</label>
                                                        <input type="text" name="identification" class="form-control form-control-sm"
                                                               value="{{ $customer->identification }}" maxlength="13">
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <label class="form-label small">Teléfono</label>
                                                        <input type="text" name="phone" class="form-control form-control-sm"
                                                               value="{{ $customer->phone }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">
                                                            <i class="bi bi-envelope-at text-primary"></i> Email
                                                            <small class="text-muted">(para factura electrónica)</small>
                                                        </label>
                                                        <input type="email" name="email" class="form-control form-control-sm"
                                                               value="{{ $customer->email }}"
                                                               placeholder="cliente@ejemplo.com — la factura se envía a este correo">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">Dirección</label>
                                                        <input type="text" name="address" class="form-control form-control-sm"
                                                               value="{{ $customer->address }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small">Ciudad</label>
                                                        <input type="text" name="city" class="form-control form-control-sm"
                                                               value="{{ $customer->city }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small">Provincia</label>
                                                        <input type="text" name="province" class="form-control form-control-sm"
                                                               value="{{ $customer->province }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small">Límite Crédito</label>
                                                        <input type="number" name="credit_limit" class="form-control form-control-sm"
                                                               step="0.01" min="0" value="{{ $customer->credit_limit }}">
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label small">Días Crédito</label>
                                                        <input type="number" name="credit_days" class="form-control form-control-sm"
                                                               min="0" value="{{ $customer->credit_days }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small">Notas</label>
                                                        <textarea name="notes" class="form-control form-control-sm" rows="2">{{ $customer->notes }}</textarea>
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
                <div class="p-3">{{ $customers->withQueryString()->links() }}</div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-people" style="font-size: 3rem;"></i>
                    <p class="mt-2">No hay clientes registrados</p>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#customerModal">
                        <i class="bi bi-plus-circle"></i> Crear Primer Cliente
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Crear Cliente --}}
<div class="modal fade" id="customerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title"><i class="bi bi-person-plus"></i> Nuevo Cliente</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('customers.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-12 col-sm-6">
                            <label class="form-label small">Nombre / Razón Social *</label>
                            <input type="text" name="name" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small">Tipo de Identificación *</label>
                            <select name="identification_type" class="form-select form-select-sm" required id="createIdType">
                                <option value="consumidor_final">Consumidor Final</option>
                                <option value="cedula">Cédula</option>
                                <option value="ruc">RUC</option>
                                <option value="pasaporte">Pasaporte</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6" id="createIdField">
                            <label class="form-label small">N° Identificación <span id="createIdRequired" style="display:none" class="text-danger">*</span></label>
                            <input type="text" name="identification" class="form-control form-control-sm" maxlength="13" id="createIdInput"
                                   placeholder="Ingrese cédula, RUC o pasaporte">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label small">Teléfono</label>
                            <input type="text" name="phone" class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="form-label small">
                                <i class="bi bi-envelope-at text-primary"></i> Email
                                <small class="text-muted">(para factura electrónica)</small>
                            </label>
                            <input type="email" name="email" class="form-control form-control-sm"
                                   placeholder="cliente@ejemplo.com — la factura se envía a este correo">
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Dirección</label>
                            <input type="text" name="address" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Ciudad</label>
                            <input type="text" name="city" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Provincia</label>
                            <input type="text" name="province" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Límite de Crédito</label>
                            <input type="number" name="credit_limit" class="form-control form-control-sm" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Días de Crédito</label>
                            <input type="number" name="credit_days" class="form-control form-control-sm" min="0" value="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Notas</label>
                            <textarea name="notes" class="form-control form-control-sm" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-save"></i> Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle identificación requerida en modal crear
    const createIdType = document.getElementById('createIdType');
    const createIdInput = document.getElementById('createIdInput');
    const createIdRequired = document.getElementById('createIdRequired');

    if (createIdType) {
        createIdType.addEventListener('change', function() {
            const required = this.value !== 'consumidor_final';
            createIdInput.required = required;
            createIdRequired.style.display = required ? '' : 'none';
            if (this.value === 'cedula') {
                createIdInput.placeholder = 'Ej: 1712345678';
                createIdInput.maxLength = 10;
            } else if (this.value === 'ruc') {
                createIdInput.placeholder = 'Ej: 1712345678001';
                createIdInput.maxLength = 13;
            } else if (this.value === 'pasaporte') {
                createIdInput.placeholder = 'Número de pasaporte';
                createIdInput.maxLength = 13;
            } else {
                createIdInput.placeholder = 'Opcional para consumidor final';
                createIdInput.maxLength = 13;
            }
        });
    }
});
</script>
@endpush
