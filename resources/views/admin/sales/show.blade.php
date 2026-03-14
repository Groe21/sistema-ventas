@extends('layouts.app')

@section('title', 'Comprobante ' . $sale->invoice_number)

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h2 class="mb-0"><i class="bi bi-receipt"></i> Comprobante</h2>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i> <span class="btn-text-mobile">Imprimir</span></button>
            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i></a>
        </div>
    </div>

    {{-- Alerta de confirmación --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        @if($sale->customer && $sale->customer->email)
            <br>
            <small><i class="bi bi-envelope"></i> Email enviado a <strong>{{ $sale->customer->email }}</strong></small>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card" id="invoiceCard">
        <div class="card-body p-3 p-md-4">
            {{-- Cabecera --}}
            <div class="row mb-3">
                <div class="col-sm-6 mb-2">
                    <h4 class="text-primary fw-bold mb-1">{{ auth()->user()->business->name ?? 'Sistema Comercial Pro' }}</h4>
                    @if(auth()->user()->business)
                        <small class="text-muted">
                            RUC: {{ auth()->user()->business->ruc }}<br>
                            {{ auth()->user()->business->address }}
                            @if(auth()->user()->business->phone)<br>Tel: {{ auth()->user()->business->phone }}@endif
                        </small>
                    @endif
                </div>
                <div class="col-sm-6 text-sm-end">
                    <h5 class="text-muted mb-1">COMPROBANTE</h5>
                    <h5 class="text-primary">{{ $sale->invoice_number }}</h5>
                    <small>
                        <strong>Fecha:</strong> {{ $sale->sale_date->format('d/m/Y H:i') }}<br>
                        <strong>Vendedor:</strong> {{ $sale->user->name ?? 'Usuario no disponible' }}
                    </small>
                </div>
            </div>

            <hr class="my-2">

            {{-- Cliente + Pago --}}
            <div class="row mb-3">
                <div class="col-sm-6 mb-2">
                    <h6 class="text-muted text-uppercase small">Cliente</h6>
                    <strong>{{ $sale->customer->name ?? 'Consumidor Final' }}</strong><br>
                    @if($sale->customer && $sale->customer->identification)
                        <small>{{ strtoupper(str_replace('_', ' ', $sale->customer->identification_type)) }}: {{ $sale->customer->identification }}</small><br>
                    @endif
                    @if($sale->customer && $sale->customer->phone)<small>Tel: {{ $sale->customer->phone }}</small>@endif
                </div>
                <div class="col-sm-6 text-sm-end">
                    <h6 class="text-muted text-uppercase small">Pago</h6>
                    @switch($sale->payment_method)
                        @case('cash') <span class="badge bg-success">Efectivo</span> @break
                        @case('card') <span class="badge bg-info">Tarjeta</span> @break
                        @case('transfer') <span class="badge bg-warning text-dark">Transferencia</span> @break
                    @endswitch
                    @if($sale->status === 'completed')
                        <span class="badge bg-success">Completada</span>
                    @else
                        <span class="badge bg-danger">Anulada</span>
                    @endif
                </div>
            </div>

            {{-- Detalle --}}
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Descripción</th>
                            <th class="text-center">Cant.</th>
                            <th class="text-end hide-mobile">P.Unit</th>
                            <th class="text-center hide-mobile">IVA</th>
                            <th class="text-end">Subt.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                {{ $item->product_name }}
                                <span class="d-md-none"><br><small class="text-muted">${{ number_format($item->unit_price, 2) }} c/u</small></span>
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end hide-mobile">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-center hide-mobile">
                                @if($item->has_iva) <span class="badge bg-success">15%</span> @else <span class="badge bg-light text-dark">0%</span> @endif
                            </td>
                            <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach

                        {{-- Mostrar billetes de 50 y 100 con series como líneas adicionales --}}
                        @if($sale->paymentDetails && count($sale->paymentDetails) > 0)
                            @foreach($sale->paymentDetails as $i => $detail)
                            <tr class="table-info">
                                <td>{{ $sale->items->count() + $loop->iteration }}</td>
                                <td>
                                    Billete de ${{ number_format($detail->denomination_value, 2) }}
                                    <br><small class="text-muted">Serie: <strong>{{ $detail->series }}</strong></small>
                                </td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-end hide-mobile">${{ number_format($detail->denomination_value, 2) }}</td>
                                <td class="text-center hide-mobile"><span class="badge bg-light text-dark">0%</span></td>
                                <td class="text-end">${{ number_format($detail->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-end"><strong>Subtotal:</strong></td>
                            <td class="text-end">${{ number_format($sale->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end"><strong>IVA 15%:</strong></td>
                            <td class="text-end">${{ number_format($sale->iva_amount, 2) }}</td>
                        </tr>
                        @if($sale->discount > 0)
                        <tr>
                            <td colspan="5" class="text-end text-danger"><strong>Descuento:</strong></td>
                            <td class="text-end text-danger">-${{ number_format($sale->discount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="table-success">
                            <td colspan="5" class="text-end"><h6 class="mb-0">TOTAL:</h6></td>
                            <td class="text-end"><h6 class="mb-0">${{ number_format($sale->total, 2) }}</h6></td>
                        </tr>
                        @if($sale->amount_received && $sale->payment_method === 'cash')
                        <tr>
                            <td colspan="5" class="text-end"><strong>Recibido:</strong></td>
                            <td class="text-end">${{ number_format($sale->amount_received, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-end"><strong>Vuelto:</strong></td>
                            <td class="text-end fw-bold text-primary">${{ number_format($sale->change_amount, 2) }}</td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>

            @if($sale->notes)
            <p class="mt-2 small"><strong>Notas:</strong> {{ $sale->notes }}</p>
            @endif

            <div class="text-center mt-3 pt-2 border-top">
                <small class="text-muted">Gracias por su compra &middot; Sistema Comercial Pro</small>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@@media print {
    .sidebar, .top-navbar, .btn, .sidebar-overlay { display: none !important; }
    .main-content { margin: 0 !important; padding: 10px !important; }
    #invoiceCard { box-shadow: none !important; }
    body { background: white !important; }
}
</style>
@endpush
@endsection
