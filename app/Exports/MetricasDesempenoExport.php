<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MetricasDesempenoExport implements FromArray, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $metricas;
    protected $fecha;

    public function __construct($metricas)
    {
        $this->metricas = $metricas;
        $this->fecha = now()->format('d/m/Y H:i:s');
    }

    public function array(): array
    {
        return [
            [
                'Tiempo Promedio de Validación',
                $this->metricas['tiempo_validacion'] ?? 0,
                'horas',
                $this->metricas['tiempo_validacion'] < 24 ? 'Excelente' : ($this->metricas['tiempo_validacion'] < 48 ? 'Bueno' : 'Regular')
            ],
            [
                'Crecimiento Mensual',
                $this->metricas['crecimiento_mensual'] ?? 0,
                '%',
                $this->metricas['crecimiento_mensual'] > 10 ? 'Alto' : ($this->metricas['crecimiento_mensual'] > 5 ? 'Moderado' : 'Bajo')
            ],
            [
                'Eficiencia de Validación',
                $this->metricas['eficiencia_validacion'] ?? 0,
                '%',
                $this->metricas['eficiencia_validacion'] > 90 ? 'Excelente' : ($this->metricas['eficiencia_validacion'] > 80 ? 'Bueno' : 'Regular')
            ],
            [
                'Tasa de Aceptación General',
                $this->metricas['tasa_aceptacion_general'] ?? 0,
                '%',
                $this->metricas['tasa_aceptacion_general'] > 85 ? 'Excelente' : ($this->metricas['tasa_aceptacion_general'] > 75 ? 'Bueno' : 'Regular')
            ]
        ];
    }

    public function headings(): array
    {
        return [
            ['Sistema de Gestión Deportiva y Cultural'],
            ['Reporte: Métricas de Desempeño'],
            ['Generado: ' . $this->fecha],
            [], // Línea en blanco
            [
                'Métrica',
                'Valor',
                'Unidad',
                'Evaluación'
            ]
        ];
    }

    public function title(): string
    {
        return 'Métricas de Desempeño';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 18,
            'C' => 15,
            'D' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Título principal
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');

        // Estilo para el encabezado principal
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '004F6E'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2:D2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A3:D3')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Estilo para el encabezado de la tabla
        $sheet->getStyle('A5:D5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '34495E'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '2C3E50'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Estilo para los datos
        $lastRow = count($this->array()) + 5;
        $sheet->getStyle("A6:D{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Estilo para la columna de evaluación con colores condicionales
        $evaluacionStyle = $sheet->getStyle("D6:D{$lastRow}");
        $evaluacionStyle->getFont()->setBold(true);

        // Colores para diferentes evaluaciones
        foreach (range(6, $lastRow) as $row) {
            $value = $sheet->getCell("D{$row}")->getValue();
            $color = 'FFFFFF'; // Blanco por defecto

            if (strpos($value, 'Excelente') !== false || strpos($value, 'Alto') !== false) {
                $color = '27AE60'; // Verde
            } elseif (strpos($value, 'Bueno') !== false || strpos($value, 'Moderado') !== false) {
                $color = 'F39C12'; // Naranja
            } elseif (strpos($value, 'Regular') !== false || strpos($value, 'Bajo') !== false) {
                $color = 'E74C3C'; // Rojo
            }

            if ($color !== 'FFFFFF') {
                $sheet->getStyle("D{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $color],
                    ],
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                ]);
            }
        }

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Configurar altos de fila
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(30);
                $event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(25);
                $event->sheet->getDelegate()->getRowDimension(5)->setRowHeight(28);

                // Ajustar altura de filas de datos
                for ($i = 6; $i <= count($this->array()) + 5; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(22);
                }
            },
        ];
    }
}
