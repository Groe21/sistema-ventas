@extends('layouts.app')

@section('title', 'Configuración')

@section('content')
<div class="container-fluid">
    <h2 class="mb-3"><i class="bi bi-gear"></i> Configuración</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        {{-- Datos del Negocio --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header py-2">
                    <i class="bi bi-building"></i> Datos del Negocio
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('settings.business') }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label small fw-bold">Nombre del Negocio *</label>
                                <input type="text" name="name" class="form-control form-control-sm"
                                       value="{{ old('name', $business->name) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Nombre Comercial</label>
                                <input type="text" name="commercial_name" class="form-control form-control-sm"
                                       value="{{ old('commercial_name', $business->commercial_name) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">RUC</label>
                                <input type="text" class="form-control form-control-sm bg-light" value="{{ $business->ruc }}" disabled>
                                <small class="text-muted">El RUC no se puede modificar</small>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold">Email *</label>
                                <input type="email" name="email" class="form-control form-control-sm"
                                       value="{{ old('email', $business->email) }}" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold">Teléfono</label>
                                <input type="text" name="phone" class="form-control form-control-sm"
                                       value="{{ old('phone', $business->phone) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Dirección</label>
                                <input type="text" name="address" class="form-control form-control-sm"
                                       value="{{ old('address', $business->address) }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold">Ciudad</label>
                                <input type="text" name="city" class="form-control form-control-sm"
                                       value="{{ old('city', $business->city) }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold">Provincia</label>
                                <input type="text" name="province" class="form-control form-control-sm"
                                       value="{{ old('province', $business->province) }}">
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-check-circle"></i> Guardar Datos
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Configuración de Correo --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header py-2">
                    <i class="bi bi-envelope"></i> Correo para Facturas Electrónicas
                </div>
                <div class="card-body">
                    <div class="alert alert-info py-2 small mb-3">
                        <i class="bi bi-info-circle"></i>
                        Las facturas se enviarán automáticamente al correo de cada cliente.
                        Necesita una cuenta de <strong>Gmail</strong> y una
                        <a href="https://myaccount.google.com/apppasswords" target="_blank" class="fw-bold">contraseña de aplicación</a>.
                    </div>

                    <div class="alert alert-warning py-2 small mb-3">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>¿Cómo obtener la contraseña de aplicación?</strong><br>
                        1. Ir a <a href="https://myaccount.google.com/security" target="_blank">Seguridad de Google</a><br>
                        2. Activar <strong>Verificación en 2 pasos</strong><br>
                        3. Buscar <strong>Contraseñas de aplicación</strong><br>
                        4. Crear una para "Correo" y copiar la clave generada
                    </div>

                    <form method="POST" action="{{ route('settings.mail') }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label small fw-bold">
                                    <i class="bi bi-google"></i> Correo Gmail *
                                </label>
                                <input type="email" name="mail_username" class="form-control form-control-sm"
                                       value="{{ old('mail_username', $settings['mail_username'] ?? '') }}"
                                       placeholder="tucorreo@gmail.com" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">
                                    <i class="bi bi-key"></i> Contraseña de Aplicación *
                                    @if($settings['mail_password_set'] ?? false)
                                        <span class="badge bg-success"><i class="bi bi-check"></i> Configurada</span>
                                    @endif
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="password" name="mail_password" id="mailPassword" class="form-control"
                                           placeholder="{{ ($settings['mail_password_set'] ?? false) ? '••••••••••••••••' : 'Pegar contraseña de aplicación' }}">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                @if(!($settings['mail_password_set'] ?? false))
                                    <small class="text-danger">Debe configurar la contraseña para enviar facturas</small>
                                @else
                                    <small class="text-muted">Deje vacío para mantener la actual</small>
                                @endif
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Nombre del Remitente</label>
                                <input type="text" name="mail_from_name" class="form-control form-control-sm"
                                       value="{{ old('mail_from_name', $settings['mail_from_name'] ?? $business->name) }}"
                                       placeholder="{{ $business->name }}">
                                <small class="text-muted">Nombre que verá el cliente al recibir la factura</small>
                            </div>

                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-check-circle"></i> Guardar Correo
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($settings['mail_password_set'] ?? false)
                    <hr class="my-2">
                    <form method="POST" action="{{ route('settings.mail.test') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-send"></i> Enviar Correo de Prueba
                        </button>
                        <small class="text-muted ms-2">Se enviará a {{ $settings['mail_username'] ?? '' }}</small>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('mailPassword');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endpush
@endsection
