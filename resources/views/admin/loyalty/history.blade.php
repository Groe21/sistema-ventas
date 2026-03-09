@extends('layouts.app')
@section('title', 'Historial de Puntos - ' . $customer->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2><i class="bi bi-clock-history"></i> Historial de Puntos</h2>
        <p class="text-muted mb-0">{{ $customer->name }} — {{ $customer->identification ?? 'Sin ID' }}</p>
    </div>
    <a href="{{ route('loyalty.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<!-- Balance Card -->
<div class="row g-2 mb-3">
    <div class="col-12 col-md-4">
        <div class="card text-center bg-primary text-white">
            <div class="card-body py-3">
                <div class="small opacity-75">Saldo Actual</div>
                <div class="display-5 fw-bold">{{ number_format($points->points_balance ?? 0) }}</div>
                <div class="small opacity-75">puntos disponibles</div>
            </div>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="card">
    <div class="card-header">
        <i class="bi bi-list-ul"></i> Movimientos de Puntos
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th class="hide-mobile">Venta</th>
                    <th class="text-center">Ganados</th>
                    <th class="text-center">Usados</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                <tr>
                    <td>{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $tx->description ?? '—' }}</td>
                    <td class="hide-mobile">
                        @if($tx->sale)
                            <a href="{{ route('sales.show', $tx->sale) }}">{{ $tx->sale->invoice_number }}</a>
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-center">
                        @if($tx->points_earned > 0)
                            <span class="text-success fw-bold">+{{ number_format($tx->points_earned) }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-center">
                        @if($tx->points_used > 0)
                            <span class="text-danger fw-bold">-{{ number_format($tx->points_used) }}</span>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No hay movimientos registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
    <div class="card-footer">{{ $transactions->links() }}</div>
    @endif
</div>
@endsection
