<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Puntos - Sistema Comercial Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .portal-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }
        .portal-header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 24px;
            text-align: center;
        }
        .portal-body { padding: 24px; }
        .points-display {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            margin-bottom: 20px;
        }
        .points-number {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
        }
        .transaction-item {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .transaction-item:last-child { border-bottom: none; }
    </style>
</head>
<body>

<div class="portal-card mx-3">
    <div class="portal-header">
        <i class="bi bi-star-fill fs-1"></i>
        <h3 class="mt-2 mb-1">Programa de Fidelización</h3>
        <p class="mb-0 opacity-75">Consulta tu saldo de puntos</p>
    </div>

    <div class="portal-body">
        <!-- Search Form -->
        <form method="POST" action="{{ route('customer-points.lookup') }}" class="mb-3">
            @csrf
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                <input type="text" name="identification" class="form-control" 
                       placeholder="Ingresa tu cédula o RUC" 
                       value="{{ $identification ?? '' }}" 
                       required maxlength="13" autofocus>
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i> Consultar
                </button>
            </div>
        </form>

        @if(isset($searched) && $searched)
            @if($customer)
                <!-- Customer Found -->
                <div class="text-center mb-3">
                    <h5 class="mb-1">{{ $customer->name }}</h5>
                    <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $customer->identification_type)) }}: {{ $customer->identification }}</small>
                </div>

                <div class="points-display">
                    <div class="small opacity-75">Tu saldo de puntos</div>
                    <div class="points-number">{{ number_format($points->points_balance ?? 0) }}</div>
                    <div class="small opacity-75 mt-1">puntos disponibles</div>
                </div>

                @if(isset($transactions) && $transactions->count() > 0)
                <div>
                    <h6 class="text-muted mb-2"><i class="bi bi-clock-history"></i> Últimos movimientos</h6>
                    @foreach($transactions as $tx)
                    <div class="transaction-item">
                        <div>
                            <div class="small">{{ $tx->description ?? 'Transacción' }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $tx->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div>
                            @if($tx->points_earned > 0)
                                <span class="badge bg-success">+{{ $tx->points_earned }}</span>
                            @endif
                            @if($tx->points_used > 0)
                                <span class="badge bg-danger">-{{ $tx->points_used }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

            @else
                <!-- Customer Not Found -->
                <div class="text-center py-3">
                    <i class="bi bi-person-x text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">No se encontró un cliente con la identificación <strong>{{ $identification }}</strong>.</p>
                    <p class="text-muted small">Verifica el número e intenta nuevamente.</p>
                </div>
            @endif
        @endif

        <hr>
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-muted small text-decoration-none">
                <i class="bi bi-box-arrow-in-right"></i> Acceso al sistema
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
