@extends('layouts.app')

@section('title', 'Dashboard Super Admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi bi-shield-check"></i> Panel de Super Administrador
        </h2>
        <div class="text-muted">
            <i class="bi bi-calendar3"></i> {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
        </div>
    </div>

    <!-- Global Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="border-left-color: #3498db;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Negocios</div>
                            <div class="stat-value">{{ $stats['total_businesses'] }}</div>
                        </div>
                        <div class="text-primary" style="font-size: 2.5rem;">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="border-left-color: #2ecc71;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Negocios Activos</div>
                            <div class="stat-value">{{ $stats['active_businesses'] }}</div>
                        </div>
                        <div class="text-success" style="font-size: 2.5rem;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="border-left-color: #9b59b6;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Usuarios</div>
                            <div class="stat-value">{{ $stats['total_users'] }}</div>
                        </div>
                        <div class="text-purple" style="font-size: 2.5rem; color: #9b59b6;">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card" style="border-left-color: #f39c12;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Ventas Hoy</div>
                            <div class="stat-value">${{ number_format($stats['total_sales_today'], 2) }}</div>
                        </div>
                        <div style="font-size: 2.5rem; color: #f39c12;">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Businesses -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-building"></i> Negocios Recientes
                </div>
                <div class="card-body">
                    @if($recentBusinesses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Negocio</th>
                                        <th>RUC</th>
                                        <th>Plan</th>
                                        <th>Estado</th>
                                        <th>Fecha Registro</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentBusinesses as $business)
                                    <tr>
                                        <td><strong>{{ $business->name }}</strong></td>
                                        <td><code>{{ $business->ruc }}</code></td>
                                        <td>
                                            <span class="badge bg-info">{{ strtoupper($business->plan) }}</span>
                                        </td>
                                        <td>
                                            @if($business->status === 'active')
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($business->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $business->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-2">No hay negocios registrados aún</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-lightning"></i> Acciones Rápidas
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('super-admin.businesses.index') }}" class="btn btn-primary">
                            <i class="bi bi-building"></i> Gestionar Negocios
                        </a>
                        <a href="{{ route('super-admin.users.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-people"></i> Ver Usuarios
                        </a>
                        <a href="{{ route('super-admin.subscriptions.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-credit-card"></i> Suscripciones
                        </a>
                        <a href="{{ route('super-admin.reports.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-graph-up"></i> Reportes Globales
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <i class="bi bi-info-circle"></i> Estado del Sistema
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Servidor:</span>
                        <span class="badge bg-success">Online</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Base de Datos:</span>
                        <span class="badge bg-success">OK</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Laravel:</span>
                        <span class="badge bg-info">v11.x</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>PHP:</span>
                        <span class="badge bg-info">v{{ PHP_VERSION }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
