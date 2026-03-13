<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura {{ $sale->invoice_number }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #333; margin: 0; padding: 0; background: #f5f5f5; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: #198754; color: #fff; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 5px 0 0; opacity: 0.9; font-size: 14px; }
        .body { padding: 20px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .info-box { width: 48%; }
        .info-box h3 { font-size: 12px; text-transform: uppercase; color: #888; margin: 0 0 5px; }
        .info-box p { margin: 2px 0; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #f8f9fa; text-align: left; padding: 8px; font-size: 12px; text-transform: uppercase; color: #555; border-bottom: 2px solid #dee2e6; }
        td { padding: 8px; font-size: 13px; border-bottom: 1px solid #eee; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .total-row td { font-weight: bold; font-size: 16px; background: #d1e7dd; border: none; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .badge-success { background: #d1e7dd; color: #0f5132; }
        .badge-info { background: #cff4fc; color: #055160; }
        .badge-warning { background: #fff3cd; color: #664d03; }
        .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="font-size: 26px; letter-spacing: 1px;">{{ $sale->business->name ?? 'Nombre de Negocio No Configurado' }}</h1>
            <p style="font-size: 15px; margin-top: 8px;">Factura electrónica emitida por {{ $sale->business->name ?? 'su negocio' }}</p>
        </div>

        <div class="body">
            <table style="margin-bottom: 15px; border: none;">
                <tr>
                    <td style="border: none; padding: 5px; vertical-align: top; width: 50%;">
                        <strong style="font-size: 12px; color: #888; text-transform: uppercase;">Datos del Negocio</strong><br>
                        @if($sale->business)
                            <span style="font-size: 13px;">
                                RUC: {{ $sale->business->ruc }}<br>
                                {{ $sale->business->address }}<br>
                                @if($sale->business->phone)Tel: {{ $sale->business->phone }}<br>@endif
                            </span>
                        @endif
                    </td>
                    <td style="border: none; padding: 5px; vertical-align: top; width: 50%; text-align: right;">
                        <strong style="font-size: 12px; color: #888; text-transform: uppercase;">Factura</strong><br>
                        <span style="font-size: 18px; font-weight: bold; color: #198754;">{{ $sale->invoice_number }}</span><br>
                        <span style="font-size: 13px;">Fecha: {{ $sale->sale_date->format('d/m/Y') }}<br>
                        Vendedor: {{ $sale->user->name ?? 'N/A' }}</span>
                    </td>
                </tr>
            </table>

            <table style="margin-bottom: 15px; border: none;">
                <tr>
                    <td style="border: none; padding: 5px; vertical-align: top; width: 50%;">
                        <strong style="font-size: 12px; color: #888; text-transform: uppercase;">Cliente</strong><br>
                        <span style="font-size: 13px;">
                            <strong>{{ $sale->customer->name }}</strong><br>
                            @if($sale->customer->identification)
                                {{ strtoupper(str_replace('_', ' ', $sale->customer->identification_type)) }}: {{ $sale->customer->identification }}<br>
                            @endif
                            @if($sale->customer->phone)Tel: {{ $sale->customer->phone }}@endif
                        </span>
                    </td>
                    <td style="border: none; padding: 5px; vertical-align: top; width: 50%; text-align: right;">
                        <strong style="font-size: 12px; color: #888; text-transform: uppercase;">Método de Pago</strong><br>
                        @switch($sale->payment_method)
                            @case('cash') <span class="badge badge-success">Efectivo</span> @break
                            @case('card') <span class="badge badge-info">Tarjeta</span> @break
                            @case('transfer') <span class="badge badge-warning">Transferencia</span> @break
                            @case('credit') <span class="badge badge-warning">Crédito</span> @break
                        @endswitch
                    </td>
                </tr>
            </table>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Descripción</th>
                        <th class="text-center">Cant.</th>
                        <th class="text-end">P.Unit</th>
                        <th class="text-center">IVA</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-center">
                            @if($item->has_iva) 15% @else 0% @endif
                        </td>
                        <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
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
                        <td colspan="5" class="text-end" style="color: #dc3545;"><strong>Descuento:</strong></td>
                        <td class="text-end" style="color: #dc3545;">-${{ number_format($sale->discount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="5" class="text-end">TOTAL:</td>
                        <td class="text-end">${{ number_format($sale->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            @if($sale->notes)
            <p style="font-size: 13px; color: #555;"><strong>Notas:</strong> {{ $sale->notes }}</p>
            @endif
        </div>

        <div class="footer">
            <p style="margin: 0;">Gracias por su compra</p>
            <p style="margin: 5px 0 0;">{{ $sale->business->name ?? 'Sistema Comercial Pro' }} &middot; {{ $sale->sale_date->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
