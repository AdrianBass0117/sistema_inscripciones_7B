<?php

namespace App\Exports;

use App\Models\Disciplina;
use App\Models\HistorialDisciplina;
use App\Models\Inscripcion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteDisciplinaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents
{
    protected $idDisciplina;
    protected $periodoId;
    protected $tipo;
    protected $disciplina;
    protected $infoPeriodo;

    public function __construct($idDisciplina, $periodoId = null, $tipo = 'actual')
    {
        $this->idDisciplina = $idDisciplina;
        $this->periodoId = $periodoId;
        $this->tipo = $tipo;
        $this->disciplina = Disciplina::find($idDisciplina);
        $this->infoPeriodo = $this->obtenerInfoPeriodo($periodoId, $tipo);
    }

    public function collection()
    {
        if ($this->tipo === 'actual') {
            return Inscripcion::with(['usuario', 'disciplina'])
                ->where('id_disciplina', $this->idDisciplina)
                ->where('estado', Inscripcion::ESTADO_ACEPTADO)
                ->get();
        } else {
            $historial = HistorialDisciplina::with('inscripcionesHistorial.usuario')
                ->where('id_historial', $this->periodoId)
                ->first();

            return $historial ? $historial->inscripcionesHistorial : collect();
        }
    }

    public function map($participante): array
    {
        static $index = 0;
        $index++;

        $fechaInscripcion = $participante->fecha_inscripcion_original ??
            $participante->fecha_inscripcion ??
            'No disponible';

        if ($fechaInscripcion !== 'No disponible') {
            $fechaInscripcion = \Carbon\Carbon::parse($fechaInscripcion)->format('d/m/Y');
        }

        // Determinar si participó
        $participo = 'Sí'; // Por defecto para disciplinas actuales
        if ($this->tipo === 'historico') {
            $participo = $participante->participo ? 'Sí' : 'No';
        }

        return [
            $index,
            $participante->nombre_usuario ?? $participante->usuario->nombre_completo,
            $participante->email_usuario ?? $participante->usuario->email,
            $participante->estado_inscripcion_formateado ?? 'Aceptada',
            $participo, // Usar la variable corregida
            $fechaInscripcion,
            $participante->usuario->telefono ?? 'No disponible'
        ];
    }

    public function headings(): array
    {
        $cupoMaximo = $this->obtenerCupoMaximoParaExcel();

        return [
            ['REPORTE DE DISCIPLINA - ' . $this->disciplina->nombre],
            ['Categoría: ' . $this->disciplina->getCategoriaFormateada() . ' | Género: ' . $this->disciplina->getGeneroFormateado()],
            ['Período: ' . $this->infoPeriodo],
            ['Participantes: ' . $this->collection()->count() . '/' . $cupoMaximo],
            [], // Línea en blanco
            ['#', 'Nombre Completo', 'Email', 'Estado Inscripción', 'Participó', 'Fecha Inscripción', 'Teléfono']
        ];
    }

    private function obtenerCupoHistorico()
    {
        if ($this->tipo === 'historico' && $this->periodoId) {
            $historial = HistorialDisciplina::find($this->periodoId);
            return $historial ? $historial->cupo_maximo : null;
        }
        return null;
    }

    public function title(): string
    {
        return 'Reporte ' . substr($this->disciplina->nombre, 0, 25);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => 'center']
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => 'center']
            ],
            3 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => 'center']
            ],
            4 => [
                'font' => ['bold' => true, 'size' => 12],
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
            'A:G' => [
                'alignment' => ['wrapText' => true]
            ],
            'B:B' => ['width' => 30],
            'C:C' => ['width' => 25],
            'F:F' => ['width' => 15],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Combinar celdas para todas las líneas de encabezado
                $event->sheet->mergeCells('A1:G1');
                $event->sheet->mergeCells('A2:G2');
                $event->sheet->mergeCells('A3:G3');
                $event->sheet->mergeCells('A4:G4');

                // Autoajustar todas las columnas al contenido
                $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
                foreach ($columns as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }

    private function obtenerCupoMaximoParaExcel()
    {
        if ($this->tipo === 'actual') {
            return $this->disciplina->cupo_maximo ?? 'N/A';
        } else {
            $historial = HistorialDisciplina::find($this->periodoId);
            return $historial ? ($historial->cupo_maximo ?? 'N/A') : 'N/A';
        }
    }

    private function obtenerInfoPeriodo($periodoId, $tipo)
    {
        if ($tipo === 'actual') {
            return 'Período Actual';
        }

        $historial = HistorialDisciplina::find($periodoId);
        if ($historial) {
            $inicio = \Carbon\Carbon::parse($historial->periodo_inicio)->format('d/m/Y');
            $fin = \Carbon\Carbon::parse($historial->periodo_fin)->format('d/m/Y');
            return "{$inicio} - {$fin}";
        }

        return 'Período no especificado';
    }
}
