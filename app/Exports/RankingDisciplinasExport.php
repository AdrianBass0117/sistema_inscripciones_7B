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

class RankingDisciplinasExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithEvents
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
        return $this->datos->map(function($disciplina, $index) {
            return [
                'Posición' => $index + 1,
                'Disciplina' => $disciplina->nombre,
                'Categoría' => $disciplina->categoria,
                'Género' => $disciplina->genero,
                'Total Inscritos' => $disciplina->total_inscritos,
                'Inscripciones Aceptadas' => $disciplina->inscripciones_aceptadas,
                'Tasa de Aceptación' => $disciplina->tasa_aceptacion . '%',
                'Cupo Máximo' => $disciplina->cupo_maximo,
                'Cupos Disponibles' => max(0, $disciplina->cupo_maximo - $disciplina->inscripciones_aceptadas)
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Sistema de Gestión Deportiva y Cultural'],
            ['Reporte: Ranking de Disciplinas'],
            ['Generado: ' . $this->fecha],
            [], // Línea en blanco
            [
                'Posición',
                'Disciplina',
                'Categoría',
                'Género',
                'Total Inscritos',
                'Inscripciones Aceptadas',
                'Tasa de Aceptación',
                'Cupo Máximo',
                'Cupos Disponibles'
            ]
        ];
    }

    public function title(): string
    {
        return 'Ranking de Disciplinas';
    }

    public function styles(Worksheet $sheet)
    {
        // Título principal
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');

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
            'A:I' => [
                'alignment' => [
                    'wrapText' => true,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            'A5:I5' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color' => ['rgb' => '2C3E50'],
                    ],
                ],
            ],
            'A6:I' . (count($this->datos) + 5) => [
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
                $event->sheet->getDelegate()->getRowDimension(5)->setRowHeight(28);

                // Configurar anchos de columna
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(12);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(28);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(28);
                $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(20);
                $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(18);
                $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(20);

                // Centrar datos vertical y horizontalmente
                $dataRows = count($this->datos) + 5;
                $event->sheet->getDelegate()
                    ->getStyle('A6:I' . $dataRows)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Resaltar las primeras 3 posiciones
                $styles = [
                    6 => ['rgb' => 'FFD700'], // Oro - 1er lugar
                    7 => ['rgb' => 'C0C0C0'], // Plata - 2do lugar
                    8 => ['rgb' => 'CD7F32'], // Bronce - 3er lugar
                ];

                foreach ($styles as $row => $color) {
                    if ($row <= $dataRows) {
                        $event->sheet->getDelegate()
                            ->getStyle("A{$row}:I{$row}")
                            ->applyFromArray([
                                'fill' => [
                                    'fillType' => Fill::FILL_SOLID,
                                    'startColor' => $color,
                                ],
                                'font' => [
                                    'bold' => true,
                                ]
                            ]);
                    }
                }
            },
        ];
    }
}
