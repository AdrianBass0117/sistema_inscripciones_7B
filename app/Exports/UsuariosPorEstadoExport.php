<?php

namespace App\Exports;

use App\Models\Usuario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsuariosPorEstadoExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents
{
    protected $estado;
    protected $usuarios;
    protected $estadoFormateado;

    public function __construct($estado, $usuarios, $estadoFormateado)
    {
        $this->estado = $estado;
        $this->usuarios = $usuarios;
        $this->estadoFormateado = $estadoFormateado;
    }

    public function collection()
    {
        return $this->usuarios;
    }

    public function headings(): array
    {
        return [
            ['SISTEMA DE INSCRIPCIONES - REPORTE DE USUARIOS'],
            ['Estado: ' . $this->estadoFormateado],
            ['Total de Usuarios: ' . $this->usuarios->count()],
            ['Fecha de generación: ' . now()->format('d/m/Y H:i')],
            [], // Línea en blanco
            ['#', 'Número Trabajador', 'Nombre Completo', 'Email', 'Teléfono', 'CURP', 'Antigüedad', 'Fecha Nacimiento', 'Fecha Registro', 'Total Inscripciones', 'Inscripciones Aceptadas', 'Documentos Pendientes']
        ];
    }

    public function map($usuario): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $usuario['numero_trabajador'] ?? 'N/A',
            $usuario['nombre_completo'],
            $usuario['email'],
            $usuario['telefono'] ?? 'No disponible',
            $usuario['curp'] ?? 'No disponible',
            $usuario['antiguedad'] ?? '0 años',
            $usuario['fecha_nacimiento'] ?? 'No disponible',
            $usuario['fecha_registro'] ?? 'No disponible',
            $usuario['total_inscripciones'] ?? 0,
            $usuario['inscripciones_aceptadas_count'] ?? 0,
            $usuario['documentos_pendientes_count'] ?? 0
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => 'center']
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center']
            ],
            3 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => 'center']
            ],
            4 => [
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => ['horizontal' => 'center']
            ],
            6 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '004F6E']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
            'A:L' => [
                'alignment' => ['wrapText' => true]
            ],
            'B:B' => ['width' => 15], // Número trabajador
            'C:C' => ['width' => 30], // Nombre completo
            'D:D' => ['width' => 25], // Email
            'E:E' => ['width' => 15], // Teléfono
            'F:F' => ['width' => 20], // CURP
            'G:G' => ['width' => 12], // Antigüedad
            'H:H' => ['width' => 15], // Fecha nacimiento
            'I:I' => ['width' => 15], // Fecha registro
            'J:J' => ['width' => 12], // Total inscripciones
            'K:K' => ['width' => 15], // Inscripciones aceptadas
            'L:L' => ['width' => 15], // Documentos pendientes
        ];
    }

    public function title(): string
    {
        return 'Usuarios ' . substr($this->estadoFormateado, 0, 25);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Combinar celdas para todas las líneas de encabezado
                $event->sheet->mergeCells('A1:L1');
                $event->sheet->mergeCells('A2:L2');
                $event->sheet->mergeCells('A3:L3');
                $event->sheet->mergeCells('A4:L4');

                // Autoajustar todas las columnas al contenido
                $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
                foreach ($columns as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
