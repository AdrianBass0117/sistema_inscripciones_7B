<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EstadisticasGeneroExport implements FromArray, WithHeadings, WithTitle, WithStyles, WithEvents
{
    protected $datos;
    protected $fecha;

    public function __construct($datos)
    {
        $this->datos = $datos;
        $this->fecha = now()->format('d/m/Y H:i:s');
    }

    public function array(): array
    {
        $total = $this->datos->total;

        return [
            [
                'Varonil',
                $this->datos->varonil ?? 0,
                $total > 0 ? round(($this->datos->varonil / $total) * 100, 2) . '%' : '0%'
            ],
            [
                'Femenil',
                $this->datos->femenil ?? 0,
                $total > 0 ? round(($this->datos->femenil / $total) * 100, 2) . '%' : '0%'
            ],
            [
                'Mixto',
                $this->datos->mixto ?? 0,
                $total > 0 ? round(($this->datos->mixto / $total) * 100, 2) . '%' : '0%'
            ],
            [
                'TOTAL',
                $total,
                '100%'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            ['Sistema de Gestión Deportiva y Cultural'],
            ['Reporte: Distribución por Género'],
            ['Generado: ' . $this->fecha],
            [], // Línea en blanco
            [
                'Género de Disciplina',
                'Total Inscritos',
                'Porcentaje'
            ]
        ];
    }

    public function title(): string
    {
        return 'Distribución por Género';
    }

    public function styles(Worksheet $sheet)
    {
        // Título principal
        $sheet->mergeCells('A1:C1');
        $sheet->mergeCells('A2:C2');
        $sheet->mergeCells('A3:C3');

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => ['rgb' => '004F6E']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
            2 => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ],
            3 => [
                'font' => [
                    'italic' => true,
                    'size' => 10,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ],
            5 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '004F6E']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ],
            9 => [ // Fila del TOTAL
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D4AF37']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ],
            'A:C' => [
                'alignment' => [
                    'wrapText' => true,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            'A5:C9' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Configurar altos de fila
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(30);
                $event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(25);
                $event->sheet->getDelegate()->getRowDimension(5)->setRowHeight(25);
                $event->sheet->getDelegate()->getRowDimension(9)->setRowHeight(25);

                // Configurar anchos de columna
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(28);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(18);

                // Centrar datos vertical y horizontalmente
                $event->sheet->getDelegate()
                    ->getStyle('A6:C9')
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
            },
        ];
    }
}
