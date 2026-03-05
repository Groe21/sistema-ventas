@extends('layouts.app')

@section('title', 'Configuración')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-gear me-2"></i>Configuración</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-gear text-muted" style="font-size: 4rem;"></i>
        <h4 class="mt-3 text-muted">Módulo en Desarrollo</h4>
        <p class="text-muted">La configuración del negocio estará disponible próximamente.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">
            <i class="bi bi-house me-1"></i>Volver al Dashboard
        </a>
    </div>
</div>
@endsection
