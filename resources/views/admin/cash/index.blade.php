@extends('layouts.app')

@section('title', 'Flujo de Caja')

@section('content')
<div class="container-fluid">
    <h2 class="mb-3"><i class="bi bi-cash-stack"></i> Caja</h2>

    {{-- ===================== CAJA ABIERTA ===================== --}}
    @if($openRegister)
    <div class="row g-3">
        {{-- Col principal --}}
        <div class="col-12 col-lg-8">
            {{-- Estado de caja --}}
            <div class="card mb-3">
                <div class="card-header bg-success text-white py-2 px-3">
                    <i class="bi bi-cash-coin"></i> Caja Abierta
                </div>
                <div class="card-body p-3">
                    {{-- Info + Monto esperado --}}
                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <small class="text-muted d-block"><i class="bi bi-person"></i> {{ $openRegister->user->name }}</small>
                            <small class="text-muted d-block"><i class="bi bi-calendar"></i> {{ $openRegister->opened_at->format('d/m/Y H:i') }}</small>
                            <small class="text-muted d-block"><i class="bi bi-wallet2"></i> Inicial: ${{ number_format($openRegister->opening_amount, 2) }}</small>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <small class="text-muted">Efectivo Esperado</small>
                            <h3 class="text-success mb-0">${{ number_format($openRegister->calculateExpectedAmount(), 2) }}</h3>
                        </div>
                    </div>

                    {{-- Cards resumen --}}
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="card bg-light">
                                <div class="card-body text-center py-2 px-1">
                                    <small class="text-muted d-block">Ingresos</small>
                                    <strong class="text-success">${{ number_format($openRegister->getTotalIncome(), 2) }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card bg-light">
                                <div class="card-body text-center py-2 px-1">
                                    <small class="text-muted d-block">Egresos</small>
                                    <strong class="text-danger">${{ number_format($openRegister->getTotalExpenses(), 2) }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card bg-light">
                                <div class="card-body text-center py-2 px-1">
                                    <small class="text-muted d-block">Movim.</small>
                                    <strong>{{ $openRegister->cashMovements->count() }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla movimientos --}}
                    <h6 class="mb-2"><i class="bi bi-list-ul"></i> Movimientos del Día</h6>
                    <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Tipo</th>
                                    <th class="hide-mobile">Descripción</th>
                                    <th class="text-end">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($openRegister->cashMovements as $movement)
                                <tr>
                                    <td>{{ $movement->created_at->format('H:i') }}</td>
                                    <td>
                                        @if($movement->isIncome())
                                            <span class="badge bg-success">Ingreso</span>
                                        @else
                                            <span class="badge bg-danger">Egreso</span>
                                        @endif
                                    </td>
                                    <td class="hide-mobile">{{ $movement->description }}</td>
                                    <td class="text-end">
                                        <strong class="{{ $movement->isIncome() ? 'text-success' : 'text-danger' }}">
                                            {{ $movement->isIncome() ? '+' : '-' }}${{ number_format($movement->amount, 2) }}
                                        </strong>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">Sin movimientos</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#closeModal">
                            <i class="bi bi-lock"></i> Cerrar Caja
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Col lateral: formularios ingreso/egreso --}}
        <div class="col-12 col-lg-4">
            {{-- Registrar Ingreso --}}
            <div class="card mb-3">
                <div class="card-header py-2 px-3">
                    <i class="bi bi-arrow-down-circle text-success"></i> Registrar Ingreso
                </div>
                <div class="card-body p-3">
                    <form method="POST" action="{{ route('cash.movement') }}">
                        @csrf
                        <input type="hidden" name="cash_register_id" value="{{ $openRegister->id }}">
                        <input type="hidden" name="type" value="income">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small">Categoría</label>
                                <select name="category" class="form-select form-select-sm" required>
                                    <option value="deposit">Depósito</option>
                                    <option value="other">Otro</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small">Monto</label>
                                <input type="number" name="amount" class="form-control form-control-sm" step="0.01" min="0.01" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Método</label>
                                <select name="payment_method" class="form-select form-select-sm" required>
                                    <option value="cash">Efectivo</option>
                                    <option value="card">Tarjeta</option>
                                    <option value="transfer">Transferencia</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Descripción</label>
                                <textarea name="description" class="form-control form-control-sm" rows="2" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    <i class="bi bi-plus-circle"></i> Registrar Ingreso
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Registrar Egreso --}}
            <div class="card">
                <div class="card-header py-2 px-3">
                    <i class="bi bi-arrow-up-circle text-danger"></i> Registrar Egreso
                </div>
                <div class="card-body p-3">
                    <form method="POST" action="{{ route('cash.movement') }}">
                        @csrf
                        <input type="hidden" name="cash_register_id" value="{{ $openRegister->id }}">
                        <input type="hidden" name="type" value="expense">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small">Categoría</label>
                                <select name="category" class="form-select form-select-sm" required>
                                    <option value="expense">Gasto</option>
                                    <option value="withdrawal">Retiro</option>
                                    <option value="other">Otro</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small">Monto</label>
                                <input type="number" name="amount" class="form-control form-control-sm" step="0.01" min="0.01" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Método</label>
                                <select name="payment_method" class="form-select form-select-sm" required>
                                    <option value="cash">Efectivo</option>
                                    <option value="card">Tarjeta</option>
                                    <option value="transfer">Transferencia</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Descripción</label>
                                <textarea name="description" class="form-control form-control-sm" rows="2" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-danger btn-sm w-100">
                                    <i class="bi bi-dash-circle"></i> Registrar Egreso
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== SIN CAJA ABIERTA ===================== --}}
    @else
    <div class="card mb-3">
        <div class="card-body text-center py-5">
            <i class="bi bi-cash-stack text-muted" style="font-size: 3rem;"></i>
            <h5 class="mt-3">No hay caja abierta</h5>
            <p class="text-muted small">Debe abrir una caja para registrar movimientos</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#openModal">
                <i class="bi bi-unlock"></i> Abrir Caja
            </button>
        </div>
    </div>
    @endif

    {{-- ===================== HISTORIAL ===================== --}}
    @if($closedRegisters->count() > 0)
    <div class="card mt-3">
        <div class="card-header py-2 px-3">
            <i class="bi bi-clock-history"></i> Historial de Cajas
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th class="hide-mobile">Usuario</th>
                            <th class="text-end hide-tablet">Esperado</th>
                            <th class="text-end">Real</th>
                            <th class="text-end">Diferencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($closedRegisters as $register)
                        <tr>
                            <td>
                                {{ $register->opened_at->format('d/m/Y') }}
                                <small class="d-block d-md-none text-muted">{{ $register->user->name }}</small>
                            </td>
                            <td class="hide-mobile">{{ $register->user->name }}</td>
                            <td class="text-end hide-tablet">${{ number_format($register->expected_amount, 2) }}</td>
                            <td class="text-end">${{ number_format($register->actual_amount, 2) }}</td>
                            <td class="text-end">
                                <strong class="{{ $register->difference >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $register->difference >= 0 ? '+' : '' }}${{ number_format($register->difference, 2) }}
                                </strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Modal Abrir Caja --}}
<div class="modal fade" id="openModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title"><i class="bi bi-unlock"></i> Abrir Caja</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('cash.open') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Monto Inicial (Efectivo) *</label>
                        <input type="number" name="opening_amount" class="form-control"
                               step="0.01" min="0" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notas</label>
                        <textarea name="opening_notes" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-unlock"></i> Abrir Caja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Cerrar Caja --}}
@if($openRegister)
<div class="modal fade" id="closeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title"><i class="bi bi-lock"></i> Cerrar Caja</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('cash.close', $openRegister) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info py-2 mb-3">
                        <div><strong>Efectivo Esperado:</strong> ${{ number_format($openRegister->calculateExpectedAmount(), 2) }}</div>
                        @if(!empty($expectedByMethod))
                        <div class="small mt-1">
                            <span class="me-2">Tarjeta esperada: <strong>${{ number_format($expectedByMethod['card'], 2) }}</strong></span>
                            <span>Transferencia esperada: <strong>${{ number_format($expectedByMethod['transfer'], 2) }}</strong></span>
                        </div>
                        @endif
                    </div>

                    <h6 class="mb-2"><i class="bi bi-calculator"></i> Conteo de Efectivo</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-bordered align-middle mb-0" id="denominationTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Denominación</th>
                                    <th style="width: 110px;">Cantidad</th>
                                    <th class="text-end" style="width: 120px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>Moneda $0.01</td><td><input type="number" min="0" step="1" class="form-control form-control-sm denomination-input" name="denominations[coin_001]" data-value="0.01" value="0"></td><td class="text-end denomination-subtotal">$0.00</td></tr>
                                <tr><td>Moneda $0.05</td><td><input type="number" min="0" step="1" class="form-control form-control-sm denomination-input" name="denominations[coin_005]" data-value="0.05" value="0"></td><td class="text-end denomination-subtotal">$0.00</td></tr>
                                <tr><td>Moneda $0.10</td><td><input type="number" min="0" step="1" class="form-control form-control-sm denomination-input" name="denominations[coin_010]" data-value="0.10" value="0"></td><td class="text-end denomination-subtotal">$0.00</td></tr>
                                <tr><td>Moneda $0.25</td><td><input type="number" min="0" step="1" class="form-control form-control-sm denomination-input" name="denominations[coin_025]" data-value="0.25" value="0"></td><td class="text-end denomination-subtotal">$0.00</td></tr>
                                <tr><td>Moneda $0.50</td><td><input type="number" min="0" step="1" class="form-control form-control-sm denomination-input" name="denominations[coin_050]" data-value="0.50" value="0"></td><td class="text-end denomination-subtotal">$0.00</td></tr>
                                <tr><td>Moneda $1.00</td><td><input type="number" min="0" step="1" class="form-control form-control-sm denomination-input" name="denominations[coin_100]" data-value="1.00" value="0"></td><td class="text-end denomination-subtotal">$0.00</td></tr>
                                <tr><td>Billete $1</td><td><input type="number" min="0" step="1" class="form-control form-control-sm denomination-input" name="denominations[bill_1]" data-value="1.00" value="0"></td><td class="text-end denomination-subtotal">$0.00</td></tr>
                                <tr><td>Billete $5</td><td><input type="number" min="0" step="1" class="form-control form-control-sm denomination-input" name="denominations[bill_5]" data-value="5.00" value="0"></td><td class="text-end denomination-subtotal">$0.00</td></tr>
                                <tr><td>Billete $10</td><td><input type="number" min="0" step="1" class="form-control form-control-sm denomination-input" name="denominations[bill_10]" data-value="10.00" value="0"></td><td class="text-end denomination-subtotal">$0.00</td></tr>
                                <tr><td>Billete $20</td><td><input type="number" min="0" step="1" class="form-control form-control-sm denomination-input" name="denominations[bill_20]" data-value="20.00" value="0"></td><td class="text-end denomination-subtotal">$0.00</td></tr>
                                <tr><td>Billete $50</td><td><input type="number" min="0" step="1" class="form-control form-control-sm denomination-input" name="denominations[bill_50]" data-value="50.00" value="0"></td><td class="text-end denomination-subtotal">$0.00</td></tr>
                                <tr><td>Billete $100</td><td><input type="number" min="0" step="1" class="form-control form-control-sm denomination-input" name="denominations[bill_100]" data-value="100.00" value="0"></td><td class="text-end denomination-subtotal">$0.00</td></tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-end">Total Efectivo Contado:</th>
                                    <th class="text-end" id="cashCountTotal">$0.00</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small">Total Tarjeta (contado)</label>
                            <input type="number" name="counted_card_amount" class="form-control form-control-sm" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Total Transferencia (contado)</label>
                            <input type="number" name="counted_transfer_amount" class="form-control form-control-sm" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Notas de Cierre</label>
                        <textarea name="closing_notes" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                    <div class="alert alert-warning py-2 small">
                        <i class="bi bi-exclamation-triangle"></i>
                        Una vez cerrada la caja no se podrá reabrir.
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-lock"></i> Cerrar Caja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('.denomination-input');
    if (!inputs.length) return;

    const totalEl = document.getElementById('cashCountTotal');

    function recalcCashTotal() {
        let total = 0;
        inputs.forEach((input) => {
            const qty = parseInt(input.value || '0', 10) || 0;
            const value = parseFloat(input.dataset.value || '0');
            const subtotal = qty * value;
            total += subtotal;

            const subtotalCell = input.closest('tr')?.querySelector('.denomination-subtotal');
            if (subtotalCell) {
                subtotalCell.textContent = '$' + subtotal.toFixed(2);
            }
        });

        if (totalEl) {
            totalEl.textContent = '$' + total.toFixed(2);
        }
    }

    inputs.forEach((input) => {
        input.addEventListener('input', recalcCashTotal);
    });

    recalcCashTotal();
});
</script>
@endpush
@endsection
