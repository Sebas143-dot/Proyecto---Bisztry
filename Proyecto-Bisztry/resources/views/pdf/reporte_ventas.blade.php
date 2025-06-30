<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de Ventas - Bizstry</title>
    <style>
        @page { margin: 35px 35px 50px; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", "Arial", sans-serif; color: #374151; font-size: 11px; }
        header { position: fixed; top: -35px; left: 0px; right: 0px; height: 60px; }
        .bizstry-logo { font-size: 24px; font-weight: bold; color: #4f46e5; }
        footer { position: fixed; bottom: -35px; left: 0px; right: 0px; height: 40px; border-top: 1px solid #e5e7eb; text-align: center; line-height: 35px; font-size: 10px; color: #9ca3af; }
        .pagenum:before { content: counter(page); }
        .report-title { text-align: center; margin-top: 20px; margin-bottom: 25px; }
        .report-title h1 { margin: 0; color: #111827; font-size: 26px; }
        .report-title p { margin: 5px 0; font-size: 12px; color: #6b7280; }
        .info-cards { margin-bottom: 25px; width: 100%; border-spacing: 10px 0; border-collapse: separate; }
        .card { background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 15px; text-align: center; }
        .card-title { font-size: 10px; color: #6b7280; font-weight: bold; margin-bottom: 8px; text-transform: uppercase; }
        .card-value { font-size: 22px; font-weight: bold; color: #1f2937; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 10px 8px; text-align: left; vertical-align: top; }
        th { background-color: #f9fafb; font-weight: 600; text-transform: uppercase; color: #6b7280; font-size: 9px; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        ul.product-list { margin: 0; padding: 0; list-style-type: none; }

        /* --- INICIO DE ESTILOS MEJORADOS PARA EL FOOTER DE LA TABLA --- */
        tfoot tr td {
            border-bottom: none;
            font-weight: 600;
            font-size: 11px;
        }
        tfoot .summary-label {
            text-align: right;
            color: var(--text-secondary);
        }
        tfoot .summary-value {
            text-align: right;
            color: var(--text-primary);
        }
        tfoot tr.grand-total td {
            font-size: 16px;
            color: var(--primary-color);
            border-top: 2px solid #4f46e5;
            padding-top: 10px;
        }
        /* --- FIN DE ESTILOS MEJORADOS --- */
    </style>
</head>
<body>
    <header><div class="bizstry-logo">BIZSTRY</div></header>
    <footer>Reporte generado por BIZSTRY - Página <span class="pagenum"></span></footer>

    <main>
        <div class="report-title">
            <h1>Reporte de Ventas</h1>
            <p><strong>Periodo:</strong> {{ $periodoDescriptivo }} | <strong>Generado el:</strong> {{ $fechaGeneracion }}</p>
        </div>

        <table class="info-cards"><tr>
            <td><div class="card"><div class="card-title">Ventas (Productos)</div><div class="card-value">${{ number_format($kpis['totalProductos'], 2) }}</div></div></td>
            <td><div class="card"><div class="card-title">Pedidos Completados</div><div class="card-value">{{ $kpis['pedidosCompletados'] }}</div></div></td>
            <td><div class="card"><div class="card-title">Ticket Promedio</div><div class="card-value">${{ number_format($kpis['ticketPromedio'], 2) }}</div></div></td>
        </tr></table>

        <table>
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Productos Comprados</th>
                    <th class="text-right">Total Productos</th>
                    <th class="text-right">Costo Envío</th>
                    <th class="text-right">Total General</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pedidos as $pedido)
                <tr>
                    <td>PED-{{ $pedido->pedi_id }}</td>
                    <td>{{ $pedido->cliente->clie_nombre ?? 'N/A' }} {{ $pedido->cliente->clie_apellido ?? '' }}</td>
                    <td><ul class="product-list">@foreach($pedido->detalles as $detalle)<li>- {{ $detalle->variante->producto->prod_nombre }} (x{{ $detalle->cantidad }})</li>@endforeach</ul></td>
                    <td class="text-right">${{ number_format($pedido->pedi_total, 2) }}</td>
                    <td class="text-right">${{ number_format($pedido->pedi_costo_envio, 2) }}</td>
                    <td class="text-right font-bold">${{ number_format($pedido->pedi_total + $pedido->pedi_costo_envio, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align: center; padding: 20px;">No hay pedidos en este periodo.</td></tr>
                @endforelse
            </tbody>
            
            {{-- ======================================================= --}}
            {{--         INICIO DE LA MEJORA DEL FOOTER DE LA TABLA      --}}
            {{-- ======================================================= --}}
            <tfoot>
                <tr>
                    <td colspan="4" class="summary-label">Subtotal de Productos:</td>
                    <td colspan="2" class="summary-value">${{ number_format($kpis['totalProductos'], 2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="summary-label">Total Costos de Envío:</td>
                    <td colspan="2" class="summary-value">${{ number_format($kpis['totalEnvios'], 2) }}</td>
                </tr>
                <tr class="grand-total">
                    <td colspan="4" class="summary-label">GRAN TOTAL:</td>
                    <td colspan="2" class="summary-value">${{ number_format($kpis['ventasTotales'], 2) }}</td>
                </tr>
            </tfoot>
            {{-- ======================================================= --}}
            {{--              FIN DE LA MEJORA                           --}}
            {{-- ======================================================= --}}
        </table>
    </main>
</body>
</html>
