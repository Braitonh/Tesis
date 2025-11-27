<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Factura - {{ $pedido->numero_pedido }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #f6f8fb;
            --card: #ffffff;
            --muted: #6b7280;
            --accent: #0f172a;
        }

        @page {
            margin: 20mm;
            size: A4 portrait;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--bg);
            margin: 0;
            padding: 32px;
            color: var(--accent);
            font-size: 13px;
        }

        .sheet {
            max-width: 900px;
            margin: 0 auto;
            background: var(--card);
            padding: 28px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(12, 15, 30, 0.08);
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .brand {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .logo {
            width: 84px;
            height: 84px;
            border-radius: 8px;
            background: linear-gradient(135deg, #f97316, #ea580c);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 32px;
            flex-shrink: 0;
        }

        .logo i {
            font-size: 36px;
        }

        .company {
            line-height: 1.5;
        }

        .company h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: var(--accent);
        }

        .company p {
            margin: 4px 0 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .meta {
            text-align: right;
        }

        .meta .title {
            font-weight: 700;
            font-size: 16px;
            color: #f97316;
            margin-bottom: 8px;
        }

        .meta .small {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 4px;
        }

        .meta .small strong {
            color: var(--accent);
        }

        .addresses {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin: 22px 0;
        }

        .addr {
            background: #fbfcff;
            padding: 14px;
            border-radius: 8px;
            flex: 1;
        }

        .addr h4 {
            margin: 0 0 6px 0;
            font-size: 13px;
            font-weight: 600;
            color: var(--accent);
        }

        .addr p {
            margin: 4px 0;
            color: var(--muted);
            font-size: 13px;
        }

        .addr p strong {
            color: var(--accent);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        th, td {
            padding: 12px 10px;
            border-bottom: 1px solid #eef2f7;
            text-align: left;
            font-size: 13px;
        }

        th {
            background: transparent;
            font-weight: 700;
            color: var(--muted);
            font-size: 13px;
        }

        td.right {
            text-align: right;
        }

        th.right {
            text-align: right;
        }

        tfoot td {
            border-top: 2px solid #e6eef8;
            font-weight: 700;
        }

        tfoot td.right {
            text-align: right;
        }

        .badge-promocion {
            background: #fee2e2;
            color: #dc2626;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            display: inline-block;
            margin-left: 6px;
        }

        .notes {
            margin-top: 18px;
            color: var(--muted);
            font-size: 13px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .sheet {
                box-shadow: none;
                border-radius: 0;
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="sheet" id="invoice">
        <header>
            <div class="brand">
                <div class="logo">
                    <i class="fas fa-utensils"></i>
                </div>
                <div class="company">
                    <h2>FoodDesk</h2>
                    <p>Cuil: 20-35454545-6 · Dirección: Av. Principal 123, Posadas, Misiones, Argentina</p>
                    <p>Email: contacto@fooddesk.com.ar · Tel: 0981-123456</p>
                </div>
            </div>
            <div class="meta">
                <div class="title">FACTURA</div>
                <div class="small">Nº: <strong>{{ $pedido->numero_pedido }}</strong></div>
                <div class="small">Fecha: <strong>{{ $pedido->created_at->format('d/m/Y') }}</strong></div>
            </div>
        </header>

        <div class="addresses">
            <div class="addr">
                <h4>Factura a:</h4>
                <p><strong>{{ $pedido->user->name }}</strong></p>
                @if($pedido->user->dni)
                    <p>DNI: {{ $pedido->user->dni }}</p>
                @endif
                <p>{{ $pedido->direccion_entrega }}</p>
                <p>Teléfono: {{ $pedido->telefono_contacto }}</p>
                <p>{{ $pedido->user->email }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width:6%">#</th>
                    <th>Descripción</th>
                    <th style="width:12%" class="right">Cantidad</th>
                    <th style="width:14%" class="right">Precio unitario</th>
                    <th style="width:14%" class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedido->detalles as $index => $detalle)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($detalle->promocion_id)
                                {{ $detalle->promocion->nombre }}
                                <span class="badge-promocion">PROMOCIÓN</span>
                            @else
                                {{ $detalle->producto->nombre }}
                            @endif
                        </td>
                        <td class="right">{{ $detalle->cantidad }}</td>
                        <td class="right">${{ number_format($detalle->precio_unitario, 2, ',', '.') }}</td>
                        <td class="right">${{ number_format($detalle->subtotal, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="right">Subtotal</td>
                    <td class="right">${{ number_format($pedido->subtotal, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="right">Total</td>
                    <td class="right">${{ number_format($pedido->total, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        @if($pedido->metodo_pago_preferido === 'efectivo' && $pedido->monto_recibido)
        <div style="background: #f0fdf4; border: 2px solid #86efac; border-radius: 8px; padding: 14px; margin-top: 18px;">
            <h4 style="margin: 0 0 10px 0; font-size: 13px; font-weight: 600; color: #166534;">Información de Pago en Efectivo</h4>
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                <span style="color: #6b7280; font-size: 13px;">Monto Recibido:</span>
                <span style="font-weight: 600; color: #1f2937; font-size: 13px;">${{ number_format($pedido->monto_recibido, 2, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                <span style="color: #6b7280; font-size: 13px;">Total del Pedido:</span>
                <span style="font-weight: 600; color: #1f2937; font-size: 13px;">${{ number_format($pedido->total, 2, ',', '.') }}</span>
            </div>
            @if($pedido->vuelto > 0)
            <div style="display: flex; justify-content: space-between; margin-top: 10px; padding-top: 10px; border-top: 2px solid #86efac;">
                <span style="font-weight: 700; color: #166534; font-size: 14px;">Vuelto a Devolver:</span>
                <span style="font-weight: 700; color: #166534; font-size: 16px;">${{ number_format($pedido->vuelto, 2, ',', '.') }}</span>
            </div>
            @else
            <div style="margin-top: 10px; padding-top: 10px; border-top: 2px solid #86efac; text-align: center;">
                <span style="font-weight: 600; color: #166534; font-size: 13px;">✓ Pago exacto - No hay vuelto</span>
            </div>
            @endif
        </div>
        @endif
    </div>
</body>
</html>
