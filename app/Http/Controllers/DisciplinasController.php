<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Models\Disciplina;
use App\Models\Inscripcion;
use App\Models\HistorialDisciplina;
use App\Models\HistorialInscripcionDisciplina;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisciplinasController extends Controller
{
    /**
     * Display a listing of the disciplines.
     */
    public function index()
    {
        // Obtener todas las disciplinas con información de inscripciones
        $disciplinas = Disciplina::withCount(['inscripciones as inscripciones_aceptadas_count' => function ($query) {
            $query->where('estado', 'aceptado');
        }])->get();

        // Preparar datos para la vista
        $totalDisciplinas = $disciplinas->count();
        $disciplinasActivas = $disciplinas->where('activa', true)->count();
        $totalInscripciones = $disciplinas->sum('inscripciones_aceptadas_count');

        // Calcular ocupación promedio
        $ocupacionPromedio = 0;
        if ($totalDisciplinas > 0) {
            $totalOcupacion = 0;
            $disciplinasConCupo = 0;

            foreach ($disciplinas as $disciplina) {
                if ($disciplina->cupo_maximo > 0) {
                    $ocupacion = ($disciplina->inscripciones_aceptadas_count / $disciplina->cupo_maximo) * 100;
                    $totalOcupacion += min($ocupacion, 100); // Máximo 100%
                    $disciplinasConCupo++;
                }
            }

            $ocupacionPromedio = $disciplinasConCupo > 0 ? round($totalOcupacion / $disciplinasConCupo) : 0;
        }

        return view('comite.disciplinas', compact(
            'disciplinas',
            'totalDisciplinas',
            'disciplinasActivas',
            'totalInscripciones',
            'ocupacionPromedio'
        ));
    }

    /**
     * Toggle discipline status (active/inactive).
     */
    public function toggleStatus($id)
    {
        $disciplina = Disciplina::findOrFail($id);

        $disciplina->update([
            'activa' => !$disciplina->activa,
            'updated_at' => now(),
        ]);

        $status = $disciplina->activa ? 'habilitada' : 'deshabilitada';

        return response()->json([
            'success' => true,
            'message' => "Disciplina {$status} exitosamente",
            'is_active' => $disciplina->activa
        ]);
    }

    /**
     * Get discipline statistics for AJAX requests.
     */
    public function getStatistics()
    {
        $disciplinas = Disciplina::withCount(['inscripciones as inscripciones_aceptadas_count' => function ($query) {
            $query->where('estado', 'aceptado');
        }])->get();

        $totalDisciplinas = $disciplinas->count();
        $disciplinasActivas = $disciplinas->where('activa', true)->count();
        $totalInscripciones = $disciplinas->sum('inscripciones_aceptadas_count');

        // Calcular disciplinas activas esta semana
        $disciplinasActivasEstaSemana = $disciplinas->filter(function ($disciplina) {
            return $disciplina->activa &&
                $disciplina->updated_at &&
                $disciplina->updated_at->gte(now()->subWeek());
        })->count();

        return response()->json([
            'total_disciplinas' => $totalDisciplinas,
            'disciplinas_activas' => $disciplinasActivas,
            'total_inscripciones' => $totalInscripciones,
            'disciplinas_activas_semana' => $disciplinasActivasEstaSemana,
        ]);
    }

    public function edit($id)
    {
        $disciplina = Disciplina::findOrFail($id);
        return view('comite.disciplinas-editar', compact('disciplina'));
    }

    /**
     * Update the specified discipline.
     */
    public function update(Request $request, $id)
    {
        $disciplina = Disciplina::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'categoria' => 'required|in:Deporte,Cultural',
            'genero' => 'required|in:Femenil,Varonil,Mixto',
            'cupo_maximo' => 'required|integer|min:1',
            'descripcion' => 'required|string',
            'instrucciones' => 'nullable|string',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'activa' => 'sometimes|boolean',
        ]);

        // Validar automáticamente el estado activo
        $activa = $request->has('activa') ? (bool)$request->activa : $disciplina->activa;

        // Si intentan activar pero no tiene fechas válidas, forzar inactiva
        if ($activa && (!$validated['fecha_inicio'] || !$validated['fecha_fin'])) {
            $activa = false;
        }

        $disciplina->update([
            ...$validated,
            'activa' => $activa,
            'updated_at' => now(),
        ]);

        return redirect()->route('comite.disciplinas')
            ->with('success', 'Disciplina actualizada exitosamente');
    }

    /**
     * Show the form for creating a new discipline.
     */
    public function create()
    {
        return view('comite.disciplinas-crear');
    }

    /**
     * Store a newly created discipline.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50',
            'categoria' => 'required|in:Deporte,Cultural',
            'genero' => 'required|in:Femenil,Varonil,Mixto',
            'cupo_maximo' => 'required|integer|min:1',
            'descripcion' => 'required|string',
            'instrucciones' => 'nullable|string',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        Disciplina::create([
            ...$validated,
            'activa' => true,
            'updated_at' => now(),
        ]);

        return redirect()->route('comite.disciplinas')
            ->with('success', 'Disciplina creada exitosamente');
    }

    /**
     * Mostrar inscritos en una disciplina
     */
    public function showInscritos($id)
    {
        $disciplina = Disciplina::with(['inscripcionesAceptadas.usuario'])
            ->findOrFail($id);

        $inscritos = $disciplina->inscripcionesAceptadas;

        return view('comite.disciplinas-inscritos', compact('disciplina', 'inscritos'));
    }

    /**
     * Obtener estadísticas de inscritos para AJAX
     */
    public function getInscritosData($id)
    {
        $disciplina = Disciplina::with(['inscripcionesAceptadas.usuario'])
            ->findOrFail($id);

        $inscritos = $disciplina->inscripcionesAceptadas->map(function ($inscripcion) {
            return [
                'id_usuario' => $inscripcion->usuario->id_usuario,
                'nombre_completo' => $inscripcion->usuario->nombre_completo,
                'email' => $inscripcion->usuario->email,
                'numero_trabajador' => $inscripcion->usuario->numero_trabajador,
                'fecha_inscripcion' => $inscripcion->fecha_inscripcion->format('d/m/Y H:i'),
                'telefono' => $inscripcion->usuario->telefono,
            ];
        });

        return response()->json([
            'success' => true,
            'disciplina' => [
                'nombre' => $disciplina->nombre,
                'cupo_maximo' => $disciplina->cupo_maximo,
                'inscritos_count' => $disciplina->contarInscripcionesAceptadas(),
                'cupos_disponibles' => $disciplina->getCuposDisponibles(),
            ],
            'inscritos' => $inscritos
        ]);
    }

    /**
     * Mostrar vista para finalizar disciplina
     */
    public function showFinalizar($id)
    {
        $disciplina = Disciplina::with(['inscripcionesAceptadas.usuario'])
            ->findOrFail($id);

        $inscritos = $disciplina->inscripcionesAceptadas;

        return view('comite.disciplinas-finalizar', compact('disciplina', 'inscritos'));
    }

    /**
     * Procesar finalización de disciplina
     */
    public function finalizarDisciplina(Request $request, $id)
    {
        $disciplina = Disciplina::with(['inscripcionesAceptadas.usuario'])
            ->findOrFail($id);

        $request->validate([
            'participaciones' => 'required|array',
            'participaciones.*' => 'boolean',
        ]);

        // Iniciar transacción para asegurar consistencia de datos
        \DB::transaction(function () use ($disciplina, $request) {
            // 1. Crear registro en historial_disciplinas
            $historialDisciplina = HistorialDisciplina::create([
                'id_disciplina' => $disciplina->id_disciplina,
                'nombre_disciplina' => $disciplina->nombre,
                'descripcion' => $disciplina->descripcion,
                'categoria' => $disciplina->categoria,
                'genero' => $disciplina->genero,
                'cupo_maximo' => $disciplina->cupo_maximo,
                'periodo_inicio' => $disciplina->fecha_inicio,
                'periodo_fin' => $disciplina->fecha_fin,
                'total_inscritos' => $disciplina->contarInscripcionesAceptadas(),
                'fecha_finalizacion' => now(),
                'estado_finalizacion' => HistorialDisciplina::ESTADO_COMPLETADA,
            ]);

            // 2. Mover inscripciones al historial
            foreach ($disciplina->inscripcionesAceptadas as $inscripcion) {
                $participo = $request->participaciones[$inscripcion->id_inscripcion] ?? false;

                HistorialInscripcionDisciplina::crearDesdeInscripcion(
                    $inscripcion,
                    $historialDisciplina->id_historial
                )->update(['participo' => $participo]);
            }

            // 3. Limpiar datos de la disciplina actual
            $disciplina->update([
                'fecha_inicio' => null,
                'fecha_fin' => null,
                'cupo_maximo' => null,
                'descripcion' => null,
                'instrucciones' => null,
                'activa' => false,
                'updated_at' => now(),
            ]);

            // 4. Eliminar todas las inscripciones de esta disciplina
            Inscripcion::where('id_disciplina', $disciplina->id_disciplina)->delete();

            // 5. Crear notificación
            Notificacion::create([
                'tipo' => Notificacion::TIPO_GENERAL,
                'asunto' => 'Culminación de disciplina',
                'mensaje' => "La disciplina {$disciplina->nombre} ha llegado a su fin. ¡Esperamos contar con su presencia para la próxima!",
                'leida' => false,
                'destinatarios' => Notificacion::DESTINATARIOS_TODOS,
                'created_at' => now()
            ]);
        });

        return redirect()->route('comite.disciplinas')
            ->with('success', 'Disciplina finalizada exitosamente. Los datos han sido archivados en el historial.');
    }
}
