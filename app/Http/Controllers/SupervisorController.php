<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use App\Models\Inscripcion;
use App\Models\Usuario;
use App\Models\HistorialDisciplina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EstadisticasGeneroExport;
use App\Exports\MetricasDesempenoExport;
use App\Exports\EstadisticasCategoriaExport;
use App\Exports\ReporteCompletoExport;
use App\Exports\RankingDisciplinasExport;
use Barryvdh\DomPDF\Facade\Pdf;

class SupervisorController extends Controller
{
    /**
     * Mostrar dashboard de estadísticas ejecutivas
     */
    public function estadisticasEjecutivas()
    {
        // Estadísticas generales
        $totalInscritos = Inscripcion::count();
        $inscripcionesAceptadas = Inscripcion::aceptadas()->count();
        $inscripcionesPendientes = Inscripcion::pendientes()->count();
        $disciplinasActivas = Disciplina::activas()->count();

        // Tasa de aceptación
        $tasaAceptacion = $totalInscritos > 0 ? round(($inscripcionesAceptadas / $totalInscritos) * 100, 1) : 0;

        // Distribución por género (basado en disciplinas)
        $distribucionGenero = $this->obtenerDistribucionGenero();

        // Distribución por categoría
        $distribucionCategoria = $this->obtenerDistribucionCategoria();

        // Top disciplinas
        $topDisciplinas = $this->obtenerTopDisciplinas(5);

        // Métricas de desempeño
        $metricasDesempeno = $this->obtenerMetricasDesempeno();

        return view('supervisor.estadisticas', compact(
            'totalInscritos',
            'inscripcionesAceptadas',
            'inscripcionesPendientes',
            'disciplinasActivas',
            'tasaAceptacion',
            'distribucionGenero',
            'distribucionCategoria',
            'topDisciplinas',
            'metricasDesempeno'
        ));
    }

    /**
     * Obtener distribución por género (basado en disciplinas)
     */
    private function obtenerDistribucionGenero()
    {
        return DB::table('disciplinas')
            ->join('inscripciones', 'disciplinas.id_disciplina', '=', 'inscripciones.id_disciplina')
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN disciplinas.genero = "' . Disciplina::GENERO_VARONIL . '" THEN 1 ELSE 0 END) as varonil'),
                DB::raw('SUM(CASE WHEN disciplinas.genero = "' . Disciplina::GENERO_FEMENIL . '" THEN 1 ELSE 0 END) as femenil'),
                DB::raw('SUM(CASE WHEN disciplinas.genero = "' . Disciplina::GENERO_MIXTO . '" THEN 1 ELSE 0 END) as mixto')
            )
            ->whereIn('inscripciones.estado', [Inscripcion::ESTADO_PENDIENTE, Inscripcion::ESTADO_ACEPTADO])
            ->first();
    }

    /**
     * Obtener distribución por categoría
     */
    private function obtenerDistribucionCategoria()
    {
        return DB::table('disciplinas')
            ->join('inscripciones', 'disciplinas.id_disciplina', '=', 'inscripciones.id_disciplina')
            ->select(
                'disciplinas.categoria',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN inscripciones.estado = "' . Inscripcion::ESTADO_ACEPTADO . '" THEN 1 ELSE 0 END) as aceptados')
            )
            ->whereIn('inscripciones.estado', [Inscripcion::ESTADO_PENDIENTE, Inscripcion::ESTADO_ACEPTADO])
            ->groupBy('disciplinas.categoria')
            ->get();
    }

    /**
     * Obtener top disciplinas por inscripciones
     */
    private function obtenerTopDisciplinas($limite = 5)
    {
        return Disciplina::withCount(['inscripciones as total_inscritos' => function ($query) {
            $query->whereIn('estado', [Inscripcion::ESTADO_PENDIENTE, Inscripcion::ESTADO_ACEPTADO]);
        }])
            ->withCount(['inscripciones as inscripciones_aceptadas' => function ($query) {
                $query->where('estado', Inscripcion::ESTADO_ACEPTADO);
            }])
            ->where('activa', true)
            ->orderBy('total_inscritos', 'desc')
            ->limit($limite)
            ->get()
            ->map(function ($disciplina) {
                $disciplina->tasa_aceptacion = $disciplina->total_inscritos > 0
                    ? round(($disciplina->inscripciones_aceptadas / $disciplina->total_inscritos) * 100, 1)
                    : 0;
                return $disciplina;
            });
    }

    /**
     * Descargar reportes en formato seleccionado
     */
    /**
     * Descargar reportes en formato seleccionado
     */
    public function descargarReportes(Request $request)
    {
        $request->validate([
            'tipo_reporte' => 'required|in:genero,categoria,ranking,metricas,todos',
            'formato' => 'required|in:pdf,excel,ambos'
        ]);

        $tipoReporte = $request->tipo_reporte;
        $formato = $request->formato;

        switch ($tipoReporte) {
            case 'genero':
                return $this->descargarReporteGenero($formato);

            case 'categoria':
                return $this->descargarReporteCategoria($formato);

            case 'ranking':
                return $this->descargarReporteRanking($formato);

            case 'metricas':
                return $this->descargarReporteMetricas($formato);

            case 'todos':
                return $this->descargarReporteCompleto($formato);
        }
    }

    /**
     * Descargar reporte de métricas de desempeño
     */
    private function descargarReporteMetricas($formato)
    {
        $datos = $this->obtenerMetricasDesempeno();
        $fecha = now()->format('Y-m-d');

        if ($formato === 'excel' || $formato === 'ambos') {
            return Excel::download(new MetricasDesempenoExport($datos), "reporte-metricas-{$fecha}.xlsx");
        }

        if ($formato === 'pdf') {
            $pdf = Pdf::loadView('exports.metricas', compact('datos', 'fecha'));
            return $pdf->download("reporte-metricas-{$fecha}.pdf");
        }
    }

    /**
     * Descargar reporte de género
     */
    private function descargarReporteGenero($formato)
    {
        $datos = $this->obtenerDistribucionGenero();
        $fecha = now()->format('Y-m-d');

        if ($formato === 'excel' || $formato === 'ambos') {
            return Excel::download(new EstadisticasGeneroExport($datos), "reporte-genero-{$fecha}.xlsx");
        }

        if ($formato === 'pdf') {
            $pdf = Pdf::loadView('exports.genero', compact('datos', 'fecha'));
            return $pdf->download("reporte-genero-{$fecha}.pdf");
        }
    }

    /**
     * Descargar reporte de categoría
     */
    private function descargarReporteCategoria($formato)
    {
        $datos = $this->obtenerDistribucionCategoria();
        $fecha = now()->format('Y-m-d');

        if ($formato === 'excel' || $formato === 'ambos') {
            return Excel::download(new EstadisticasCategoriaExport($datos), "reporte-categoria-{$fecha}.xlsx");
        }

        if ($formato === 'pdf') {
            $pdf = Pdf::loadView('exports.categoria', compact('datos', 'fecha'));
            return $pdf->download("reporte-categoria-{$fecha}.pdf");
        }
    }

    /**
     * Descargar reporte de ranking
     */
    private function descargarReporteRanking($formato)
    {
        $datos = $this->obtenerTopDisciplinas(10); // Top 10 para el reporte
        $fecha = now()->format('Y-m-d');

        if ($formato === 'excel' || $formato === 'ambos') {
            return Excel::download(new RankingDisciplinasExport($datos), "reporte-ranking-{$fecha}.xlsx");
        }

        if ($formato === 'pdf') {
            $pdf = Pdf::loadView('exports.ranking', compact('datos', 'fecha'));
            return $pdf->download("reporte-ranking-{$fecha}.pdf");
        }
    }

    /**
     * Descargar reporte completo
     */
    private function descargarReporteCompleto($formato)
    {
        $datos = [
            'genero' => $this->obtenerDistribucionGenero(),
            'categoria' => $this->obtenerDistribucionCategoria(),
            'ranking' => $this->obtenerTopDisciplinas(10),
            'metricas' => $this->obtenerMetricasDesempeno()
        ];

        $fecha = now()->format('Y-m-d');

        if ($formato === 'excel' || $formato === 'ambos') {
            return Excel::download(new ReporteCompletoExport($datos), "reporte-completo-{$fecha}.xlsx");
        }

        if ($formato === 'pdf') {
            $pdf = Pdf::loadView('exports.completo', compact('datos', 'fecha'));
            return $pdf->download("reporte-completo-{$fecha}.pdf");
        }
    }

    /**
     * Obtener métricas de desempeño
     */
    private function obtenerMetricasDesempeno()
    {
        // Tiempo promedio de validación (en horas)
        $tiempoValidacion = Inscripcion::aceptadas()
            ->whereNotNull('fecha_validacion')
            ->whereNotNull('fecha_inscripcion')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, fecha_inscripcion, fecha_validacion)) as tiempo_promedio')
            ->first();

        // Crecimiento mensual (inscripciones del mes actual vs mes anterior)
        $mesActual = Inscripcion::whereMonth('fecha_inscripcion', now()->month)->count();
        $mesAnterior = Inscripcion::whereMonth('fecha_inscripcion', now()->subMonth()->month)->count();

        $crecimientoMensual = $mesAnterior > 0
            ? round((($mesActual - $mesAnterior) / $mesAnterior) * 100, 1)
            : ($mesActual > 0 ? 100 : 0);

        // Tasa de aceptación general
        $totalInscritos = Inscripcion::count();
        $inscripcionesAceptadas = Inscripcion::aceptadas()->count();
        $tasaAceptacionGeneral = $totalInscritos > 0
            ? round(($inscripcionesAceptadas / $totalInscritos) * 100, 1)
            : 0;

        return [
            'tiempo_validacion' => $tiempoValidacion->tiempo_promedio ?? 0,
            'crecimiento_mensual' => $crecimientoMensual,
            'eficiencia_validacion' => 92, // Simulado - podrías calcularlo basado en tus criterios
            'tasa_aceptacion_general' => $tasaAceptacionGeneral
        ];
    }

    /**
     * Obtener datos para gráficos (AJAX)
     */
    public function obtenerDatosGraficos()
    {
        $distribucionGenero = $this->obtenerDistribucionGenero();
        $distribucionCategoria = $this->obtenerDistribucionCategoria();
        $topDisciplinas = $this->obtenerTopDisciplinas(5);

        return response()->json([
            'genero' => [
                'varonil' => $distribucionGenero->varonil ?? 0,
                'femenil' => $distribucionGenero->femenil ?? 0,
                'mixto' => $distribucionGenero->mixto ?? 0
            ],
            'categoria' => $distribucionCategoria->map(function ($item) {
                return [
                    'categoria' => $item->categoria,
                    'total' => $item->total,
                    'aceptados' => $item->aceptados
                ];
            }),
            'top_disciplinas' => $topDisciplinas->map(function ($disciplina) {
                return [
                    'nombre' => $disciplina->nombre,
                    'total_inscritos' => $disciplina->total_inscritos,
                    'inscripciones_aceptadas' => $disciplina->inscripciones_aceptadas,
                    'tasa_aceptacion' => $disciplina->tasa_aceptacion,
                    'genero' => $disciplina->genero,
                    'categoria' => $disciplina->categoria
                ];
            })
        ]);
    }
}
