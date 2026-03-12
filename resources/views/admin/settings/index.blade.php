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
                        Configure su cuenta de correo para enviar facturas automáticamente a sus clientes.
                        <strong>Se recomienda usar Gmail</strong> con una
                        <a href="https://myaccount.google.com/apppasswords" target="_blank">contraseña de aplicación</a>.
                    </div>

                    <form method="POST" action="{{ route('settings.mail') }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label small fw-bold">Proveedor de Correo</label>
                                <select id="mailPreset" class="form-select form-select-sm" onchange="applyPreset()">
                                    <option value="">-- Seleccionar proveedor --</option>
                                    <option value="gmail">Gmail</option>
                                    <option value="outlook">Outlook / Hotmail</option>
                                    <option value="yahoo">Yahoo</option>
                                    <option value="custom">Otro (personalizado)</option>
                                </select>
                            </div>

                            <div class="col-sm-8">
                                <label class="form-label small fw-bold">Servidor SMTP *</label>
                                <input type="text" name="mail_host" id="mailHost" class="form-control form-control-sm"
                                       value="{{ old('mail_host', $settings['mail_host'] ?? '') }}"
                                       placeholder="smtp.gmail.com" required>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label small fw-bold">Puerto *</label>
                                <input type="number" name="mail_port" id="mailPort" class="form-control form-control-sm"
                                       value="{{ old('mail_port', $settings['mail_port'] ?? '587') }}"
                                       placeholder="587" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Correo (usuario SMTP) *</label>
                                <input type="email" name="mail_username" id="mailUsername" class="form-control form-control-sm"
                                       value="{{ old('mail_username', $settings['mail_username'] ?? '') }}"
                                       placeholder="tucorreo@gmail.com" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">
                                    Contraseña de Aplicación *
                                    @if($settings['mail_password_set'] ?? false)
                                        <span class="badge bg-success">Configurada</span>
                                    @endif
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="password" name="mail_password" id="mailPassword" class="form-control"
                                           placeholder="{{ ($settings['mail_password_set'] ?? false) ? '••••••••••••••••' : 'Contraseña de aplicación' }}">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                                @if(!($settings['mail_password_set'] ?? false))
                                    <small class="text-danger">Debe configurar la contraseña para enviar correos</small>
                                @else
                                    <small class="text-muted">Deje vacío para mantener la contraseña actual</small>
                                @endif
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label small fw-bold">Encriptación *</label>
                                <select name="mail_encryption" id="mailEncryption" class="form-select form-select-sm" required>
                                    <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS (recomendado)</option>
                                    <option value="ssl" {{ ($settings['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="none" {{ ($settings['mail_encryption'] ?? '') === 'none' ? 'selected' : '' }}>Ninguna</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold">Nombre del Remitente</label>
                                <input type="text" name="mail_from_name" class="form-control form-control-sm"
                                       value="{{ old('mail_from_name', $settings['mail_from_name'] ?? $business->name) }}"
                                       placeholder="{{ $business->name }}">
                            </div>

                            <div class="col-12 mt-2 d-flex gap-2">
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
function applyPreset() {
    const preset = document.getElementById('mailPreset').value;
    const presets = {
        gmail: { host: 'smtp.gmail.com', port: '587', encryption: 'tls' },
        outlook: { host: 'smtp-mail.outlook.com', port: '587', encryption: 'tls' },
        yahoo: { host: 'smtp.mail.yahoo.com', port: '587', encryption: 'tls' },
        custom: { host: '', port: '587', encryption: 'tls' }
    };
    if (presets[preset]) {
        document.getElementById('mailHost').value = presets[preset].host;
        document.getElementById('mailPort').value = presets[preset].port;
        document.getElementById('mailEncryption').value = presets[preset].encryption;
    }
}

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
