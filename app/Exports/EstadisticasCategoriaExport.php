<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EstadisticasCategoriaExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithEvents
{
    protected $datos;
    protected $fecha;

    public function __construct($datos)
    {
        $this->datos = $datos;
        $this->fecha = now()->format('d/m/Y H:i:s');
    }

    public function collection()
    {
        return $this->datos->map(function($item) {
            return [
                'Categoría' => $item->categoria,
                'Total Inscritos' => $item->total,
                'Aceptados' => $item->aceptados,
                'Tasa de Aceptación' => $item->total > 0 ? round(($item->aceptados / $item->total) * 100, 2) . '%' : '0%'
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Sistema de Gestión Deportiva y Cultural'],
            ['Reporte: Distribución por Categoría'],
            ['Generado: ' . $this->fecha],
            [], // Línea en blanco
            [
                'Categoría',
                'Total Inscritos',
                'Aceptados',
                'Tasa de Aceptación'
            ]
        ];
    }

    public function title(): string
    {
        return 'Distribución por Categoría';
    }

    public function styles(Worksheet $sheet)
    {
        // Título principal
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');

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
            'A:D' => [
                'alignment' => [
                    'wrapText' => true,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            'A5:D5' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
            'A6:D' . (count($this->datos) + 5) => [
                'borders' => [
                    'outline' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => ['rgb' => '004F6E'],
                    ],
                    'inside' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'DDDDDD'],
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

                // Configurar anchos de columna
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(22);

                // Centrar datos vertical y horizontalmente
                $dataRows = count($this->datos) + 5;
                $event->sheet->getDelegate()
                    ->getStyle('A6:D' . $dataRows)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);
            },
        ];
    }
}
