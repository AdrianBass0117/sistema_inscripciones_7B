<?php

namespace App\Http\Controllers;

use App\Exports\ReporteDisciplinaExport;
use App\Exports\UsuariosPorEstadoExport;
use App\Models\Disciplina;
use App\Models\HistorialDisciplina;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function exportarReporteDisciplina(Request $request, $idDisciplina)
    {
        $request->validate([
            'formato' => 'required|in:excel,pdf,ambos',
            'periodo_id' => 'nullable|string'
        ]);

        $formato = $request->formato;
        $periodoId = $request->periodo_id;

        // Determinar el tipo de datos
        $tipo = ($periodoId === 'actual' || $periodoId === null) ? 'actual' : 'historico';

        // Verificar si hay datos para exportar
        $totalParticipantes = $this->contarParticipantes($idDisciplina, $periodoId, $tipo);

        if ($totalParticipantes === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No hay datos para exportar con los filtros seleccionados.'
            ], 400);
        }

        $disciplina = Disciplina::findOrFail($idDisciplina);
        $nombreArchivo = $this->generarNombreArchivo($disciplina->nombre, $periodoId);

        if ($formato === 'excel') {
            return $this->exportarExcel($idDisciplina, $periodoId, $tipo, $nombreArchivo);
        }

        if ($formato === 'pdf') {
            return $this->exportarPDF($idDisciplina, $periodoId, $tipo, $nombreArchivo);
        }

        // Para "ambos", por defecto devolvemos Excel
        return $this->exportarExcel($idDisciplina, $periodoId, $tipo, $nombreArchivo);
    }

    private function contarParticipantes($idDisciplina, $periodoId, $tipo)
    {
        if ($tipo === 'actual') {
            return \App\Models\Inscripcion::where('id_disciplina', $idDisciplina)
                ->where('estado', \App\Models\Inscripcion::ESTADO_ACEPTADO)
                ->count();
        } else {
            $historial = HistorialDisciplina::withCount('inscripcionesHistorial')
                ->where('id_historial', $periodoId)
                ->first();

            return $historial ? $historial->inscripciones_historial_count : 0;
        }
    }

    private function exportarExcel($idDisciplina, $periodoId, $tipo, $nombreArchivo)
    {
        return Excel::download(
            new ReporteDisciplinaExport($idDisciplina, $periodoId, $tipo),
            $nombreArchivo . '.xlsx'
        );
    }

    private function exportarPDF($idDisciplina, $periodoId, $tipo, $nombreArchivo)
    {
        $datos = $this->obtenerDatosParaPDF($idDisciplina, $periodoId, $tipo);

        // Forzar la descarga como PDF
        $pdf = PDF::loadView('exports.reporte-disciplina-pdf', $datos)
            ->setPaper('a4', 'landscape')
            ->setOption('defaultFont', 'Arial');

        return $pdf->download($nombreArchivo . '.pdf');
    }

    private function obtenerDatosParaPDF($idDisciplina, $periodoId, $tipo)
    {
        $disciplina = Disciplina::findOrFail($idDisciplina);

        if ($tipo === 'actual') {
            $participantes = \App\Models\Inscripcion::with('usuario')
                ->where('id_disciplina', $idDisciplina)
                ->where('estado', \App\Models\Inscripcion::ESTADO_ACEPTADO)
                ->get()
                ->map(function ($inscripcion) {
                    return [
                        'nombre' => $inscripcion->usuario->nombre_completo,
                        'email' => $inscripcion->usuario->email,
                        'estado' => 'Aceptada',
                        'participo' => 'Sí',
                        'fecha_inscripcion' => $inscripcion->fecha_inscripcion->format('d/m/Y'),
                        'telefono' => $inscripcion->usuario->telefono
                    ];
                });
            $cupoMaximo = $disciplina->cupo_maximo ?? 'N/A';
        } else {
            $historial = HistorialDisciplina::with('inscripcionesHistorial.usuario')
                ->where('id_historial', $periodoId)
                ->first();

            $participantes = $historial ? $historial->inscripcionesHistorial->map(function ($inscripcion) {
                return [
                    'nombre' => $inscripcion->nombre_usuario,
                    'email' => $inscripcion->email_usuario,
                    'estado' => $inscripcion->getEstadoFormateado(),
                    'participo' => $inscripcion->participo ? 'Sí' : 'No',
                    'fecha_inscripcion' => $inscripcion->fecha_inscripcion_original ?
                        \Carbon\Carbon::parse($inscripcion->fecha_inscripcion_original)->format('d/m/Y') :
                        'No disponible',
                    'telefono' => $inscripcion->usuario->telefono ?? 'No disponible'
                ];
            }) : collect();

            $cupoMaximo = $historial ? ($historial->cupo_maximo ?? 'N/A') : 'N/A';
        }

        return [
            'disciplina' => $disciplina,
            'participantes' => $participantes,
            'periodo' => $this->obtenerInfoPeriodo($periodoId, $tipo),
            'fechaGeneracion' => now()->format('d/m/Y H:i'),
            'cupo_maximo' => $cupoMaximo
        ];
    }

    private function obtenerCupoMaximo($disciplina, $periodoId, $tipo)
    {
        if ($tipo === 'actual') {
            return $disciplina->cupo_maximo ?? 'N/A';
        } else {
            $historial = HistorialDisciplina::find($periodoId);
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

    private function generarNombreArchivo($nombreDisciplina, $periodoId)
    {
        $nombreBase = 'Reporte_' . str_replace(' ', '_', $nombreDisciplina);

        if ($periodoId === 'actual') {
            return $nombreBase . '_Actual';
        } else {
            return $nombreBase . '_Periodo_' . $periodoId;
        }
    }

    /**
     * Exportar usuarios por estado
     */
    public function exportarUsuariosPorEstado(Request $request, $estado)
    {
        $request->validate([
            'formato' => 'required|in:excel,pdf,ambos'
        ]);

        $formato = $request->formato;

        // Obtener datos de usuarios por estado
        $datosUsuarios = $this->obtenerUsuariosPorEstado($estado);

        if (!$datosUsuarios) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudieron obtener los datos de usuarios.'
            ], 400);
        }

        if ($datosUsuarios['usuarios']->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay usuarios en estado "' . $datosUsuarios['estado_formateado'] . '" para exportar.'
            ], 400);
        }

        $nombreArchivo = $this->generarNombreArchivoUsuarios($estado, $datosUsuarios['estado_formateado']);

        if ($formato === 'excel') {
            return $this->exportarUsuariosExcel($datosUsuarios, $nombreArchivo);
        }

        if ($formato === 'pdf') {
            return $this->exportarUsuariosPDF($datosUsuarios, $nombreArchivo);
        }

        // Para "ambos", por defecto devolvemos Excel
        return $this->exportarUsuariosExcel($datosUsuarios, $nombreArchivo);
    }

    /**
     * Obtener usuarios por estado desde el ReporteController
     */
    private function obtenerUsuariosPorEstado($estado)
    {
        try {
            $reporteController = new ReporteController();
            $response = $reporteController->usuariosPorEstado($estado);

            // Si es una respuesta JSON, obtener los datos
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);

                return [
                    'estado' => $data['estado'],
                    'estado_formateado' => $data['estado_formateado'],
                    'usuarios' => collect($data['usuarios']),
                    'total' => $data['total'],
                    'estadisticas' => $data['estadisticas'] ?? []
                ];
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Exportar usuarios a Excel
     */
    private function exportarUsuariosExcel($datosUsuarios, $nombreArchivo)
    {
        return Excel::download(
            new UsuariosPorEstadoExport(
                $datosUsuarios['estado'],
                $datosUsuarios['usuarios'],
                $datosUsuarios['estado_formateado']
            ),
            $nombreArchivo . '.xlsx'
        );
    }

    /**
     * Exportar usuarios a PDF
     */
    private function exportarUsuariosPDF($datosUsuarios, $nombreArchivo)
    {
        $datos = [
            'estado' => $datosUsuarios['estado_formateado'],
            'usuarios' => $datosUsuarios['usuarios'],
            'total_usuarios' => $datosUsuarios['total'],
            'estadisticas' => $datosUsuarios['estadisticas'],
            'fechaGeneracion' => now()->format('d/m/Y H:i')
        ];

        $pdf = PDF::loadView('exports.usuarios-por-estado-pdf', $datos)
            ->setPaper('a4', 'landscape')
            ->setOption('defaultFont', 'Arial');

        return $pdf->download($nombreArchivo . '.pdf');
    }

    /**
     * Generar nombre de archivo para usuarios
     */
    private function generarNombreArchivoUsuarios($estado, $estadoFormateado)
    {
        $nombreBase = 'Reporte_Usuarios_' . str_replace(' ', '_', $estadoFormateado);
        return $nombreBase . '_' . now()->format('Y_m_d');
    }
}
