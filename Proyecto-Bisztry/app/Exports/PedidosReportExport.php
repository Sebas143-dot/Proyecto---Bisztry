<?php

namespace App\Exports;

use App\Models\Pedido;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class PedidosReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents, WithColumnFormatting, WithColumnWidths
{
    protected $fechaInicio;
    protected $fechaFin;
    protected $periodo;
    protected $totalProductos = 0;
    protected $totalEnvio = 0;

    public function __construct($fechaInicio, $fechaFin, $periodo)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->periodo = $periodo;
    }

    /**
     * Mejora 1: La colección ahora calcula los totales para la fila de resumen.
     */
    public function collection()
    {
        $pedidos = Pedido::with([
                'cliente', 'estado', 'metodoPago', 
                'detalles.variante.producto', 'detalles.variante.talla', 'detalles.variante.color'
            ])
            ->whereBetween('pedi_fecha', [$this->fechaInicio, $this->fechaFin])
            ->where('esta_cod', 'ENT')
            ->get();

        // Calculamos los totales para usarlos en la fila de resumen
        $this->totalProductos = $pedidos->sum('pedi_total');
        $this->totalEnvio = $pedidos->sum('pedi_costo_envio');

        return $pedidos;
    }

    /**
     * Mejora 2: Encabezados de varias filas para un reporte más estructurado.
     */
    public function headings(): array
    {
        // --- INICIO DE LA MEJORA DEL TÍTULO ---
        $periodoTexto = '';
        switch ($this->periodo) {
            case 'ultimos_30_dias':
                $periodoTexto = 'Últimos 30 Días';
                break;
            case 'este_anio':
                $periodoTexto = 'Año ' . $this->fechaInicio->year;
                break;
            case 'mes_actual':
            default:
                // Genera el nombre del mes actual en español (ej. "Junio 2025")
                $periodoTexto = ucfirst(Carbon::now('America/Guayaquil')->locale('es')->monthName) . ' ' . $this->fechaInicio->year;
                break;
        }
        // --- FIN DE LA MEJORA DEL TÍTULO ---

        return [
            ['Reporte de Ventas - BIZSTRY'],
            ['Periodo: ' . $periodoTexto], // Usamos el nuevo texto descriptivo
            ['Generado el: ' . Carbon::now('America/Guayaquil')->format('d/m/Y H:i:s')],
            [], // Fila en blanco
            [
                'ID Pedido', 'Fecha', 'ID Cliente', 'Nombre Cliente', 'Email',
                'Método de Pago', 'Productos Comprados', 'Costo Envío', 'Total Productos'
            ]
        ];
    }

    /**
     * Mejora 3: Mapeo de datos a las nuevas columnas.
     */
    public function map($pedido): array
    {
        $productosComprados = $pedido->detalles->map(function ($detalle) {
            $nombreProducto = $detalle->variante->producto->prod_nombre ?? 'N/A';
            return "- {$nombreProducto} (x{$detalle->cantidad})";
        })->implode(PHP_EOL); // Usamos PHP_EOL para saltos de línea compatibles

        return [
            'PED-' . $pedido->pedi_id,
            Carbon::parse($pedido->pedi_fecha)->format('Y-m-d'),
            $pedido->cliente->clie_id ?? 'N/A',
            ($pedido->cliente->clie_nombre ?? '') . ' ' . ($pedido->cliente->clie_apellido ?? ''),
            $pedido->cliente->clie_email ?? 'N/A',
            $pedido->metodoPago->medo_detale ?? 'N/A',
            $productosComprados,
            (float) $pedido->pedi_costo_envio,
            (float) $pedido->pedi_total,
        ];
    }

    /**
     * Mejora 4: Formato de columnas para fechas y moneda.
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'H' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'I' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }

    /**
     * Mejora 5: Anchos de columna personalizados para mejor visualización.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 12, 'B' => 15, 'C' => 12, 'D' => 30, 'E' => 35,
            'F' => 20, 'G' => 45, 'H' => 15, 'I' => 15,
        ];
    }

    /**
     * Mejora 6: Estilos avanzados para encabezados.
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo para la fila de encabezados de la tabla (fila 5)
        $sheet->getStyle('A5:I5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4338CA']]
        ]);

        // Estilo para el título principal (fila 1)
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(20)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('4F46E5'));
    }

    /**
     * Mejora 7 y 8: Eventos para fusionar celdas, congelar paneles y añadir totales.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Fusionar celdas para los títulos
                $sheet->mergeCells('A1:I1');
                $sheet->mergeCells('A2:I2');
                $sheet->mergeCells('A3:I3');

                // Centrar títulos
                $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');

                // Mejora 9: Congelar la fila de encabezados para que siempre sea visible
                $sheet->freezePane('A6');

                // Mejora 10: Fila de Resumen con Totales
                $lastRow = $sheet->getHighestRow();
                $summaryRow = $lastRow + 2;
                
                $sheet->setCellValue("G{$summaryRow}", 'TOTALES:');
                $sheet->setCellValue("H{$summaryRow}", $this->totalEnvio);
                $sheet->setCellValue("I{$summaryRow}", $this->totalProductos);
                
                // Estilo para la fila de resumen
                $sheet->getStyle("G{$summaryRow}:I{$summaryRow}")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'borders' => ['top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK]]
                ]);
                $sheet->getStyle("H{$summaryRow}:I{$summaryRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);

                // Habilitar ajuste de texto en la columna de productos
                $sheet->getStyle('G6:G'.$lastRow)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
