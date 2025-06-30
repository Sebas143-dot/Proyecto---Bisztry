<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de Ventas - Bizstry</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; font-size: 11px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #4f46e5; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #4f46e5; font-size: 24px; }
        .header p { margin: 5px 0; font-size: 14px; }
        .bizstry-logo { font-size: 28px; font-weight: bold; color: #4f46e5; margin-bottom: 5px; }
        .info { margin-bottom: 20px; border: 1px solid #eee; padding: 15px; border-radius: 8px; }
        .info-grid { display: inline-block; width: 48%; }
        .info-grid span { display: block; padding: 5px 0; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th, td { border-bottom: 1px solid #ccc; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #4f46e5; color: white; font-weight: bold; text-align: left; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { position: fixed; bottom: -30px; left: 0px; right: 0px; text-align: center; font-size: 10px; color: #777; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        ul.product-list { margin: 0; padding-left: 15px; }
    </style>
</head>
<body>
    <div class="header"><div class="bizstry-logo">BIZSTRY</div><h1>Reporte de Ventas</h1><p>Generado el: {{ $fechaGeneracion }}</p></div>
    <div class="info">
        <div class="info-grid"><span><strong>Periodo:</strong> {{ ucfirst(str_replace('_', ' ', $periodo)) }}</span><span><strong>Pedidos:</strong> {{ $kpis['pedidosCompletados'] }}</span></div>
        <div class="info-grid"><span><strong>Ventas (Productos):</strong> ${{ number_format($kpis['ventasTotales'], 2) }}</span><span><strong>Ticket Promedio:</strong> ${{ number_format($kpis['ticketPromedio'], 2) }}</span></div>
    </div>
    <h3>Desglose de Pedidos Entregados</h3>
    <table>
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Productos Comprados</th>
                <th class="text-right">Total Productos</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $pedido)
            <tr>
                <td>PED-{{ $pedido->pedi_id }}</td>
                <td>{{ $pedido->cliente->clie_nombre ?? 'N/A' }} {{ $pedido->cliente->clie_apellido ?? '' }}</td>
                <td>{{ \Carbon\Carbon::parse($pedido->pedi_fecha)->format('d/m/Y') }}</td>
                <td>
                    <ul class="product-list">
                        @foreach($pedido->detalles as $detalle)
                            <li>{{ $detalle->variante->producto->prod_nombre }} (x{{ $detalle->cantidad }})</li>
                        @endforeach
                    </ul>
                </td>
                <td class="text-right font-bold">${{ number_format($pedido->pedi_total, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align: center; padding: 20px;">No hay pedidos en este periodo.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">Reporte generado por BIZSTRY - Sistema de Gestión</div>
</body>
</html>
