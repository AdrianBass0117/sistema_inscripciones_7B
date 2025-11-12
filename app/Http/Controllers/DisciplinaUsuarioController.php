<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use App\Models\Inscripcion;
use App\Models\Notificacion;
use App\Models\HistorialInscripcionDisciplina;
use App\Models\HistorialDisciplina;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DisciplinaUsuarioController extends Controller
{
    /**
     * Mostrar el listado de disciplinas disponibles
     */
    public function index()
    {
        // Obtener el ID del usuario de la sesión como fallback
        $userId = session('user_id');

        // Obtener el usuario
        $usuario = Usuario::find($userId);

        // Obtener disciplinas activas con información de cupos y fechas
        $disciplinas = Disciplina::activas()
            ->withCount(['inscripcionesAceptadas as inscripciones_aceptadas_count'])
            ->get()
            ->map(function ($disciplina) {
                $disciplina->cupos_disponibles = $disciplina->getCuposDisponibles();
                $disciplina->porcentaje_ocupado = $disciplina->cupo_maximo > 0
                    ? round(($disciplina->inscripciones_aceptadas_count / $disciplina->cupo_maximo) * 100)
                    : 0;

                // Agregar información de estado de disponibilidad
                $disciplina->estado_disponibilidad = $disciplina->getEstadoDisponibilidad();
                $disciplina->texto_estado_disponibilidad = $disciplina->getTextoEstadoDisponibilidad();
                $disciplina->dias_restantes = $disciplina->getDiasRestantes();
                $disciplina->texto_dias_restantes = $disciplina->getTextoDiasRestantes();

                return $disciplina;
            })
            ->filter(function ($disciplina) {
                // Filtrar disciplinas que no tienen fechas definidas
                return $disciplina->tieneFechasValidas();
            });

        // Obtener inscripciones del usuario (excluyendo las canceladas)
        $inscripcionesUsuario = Inscripcion::where('id_usuario', $usuario->id_usuario)
            ->where('estado', '!=', Inscripcion::ESTADO_CANCELADO)
            ->with('disciplina')
            ->get();

        // Contar inscripciones activas (pendientes o aceptadas)
        $inscripcionesActivas = $inscripcionesUsuario->whereIn('estado', [
            Inscripcion::ESTADO_PENDIENTE,
            Inscripcion::ESTADO_ACEPTADO
        ]);

        $selectedCount = $inscripcionesActivas->count();
        $maxDisciplinas = 2;

        return view('participante.disciplinas', compact(
            'disciplinas',
            'inscripcionesUsuario',
            'selectedCount',
            'maxDisciplinas'
        ));
    }

    /**
     * Inscribir usuario a una disciplina
     */
    public function inscribir(Request $request)
    {
        $request->validate([
            'id_disciplina' => 'required|exists:disciplinas,id_disciplina'
        ]);

        // Obtener el ID del usuario de la sesión
        $userId = session('user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.'
            ], 401);
        }

        $usuario = Usuario::find($userId);
        $idDisciplina = $request->id_disciplina;
        $disciplina = Disciplina::findOrFail($idDisciplina);

        // Verificar si la disciplina está activa
        if (!$disciplina->estaActiva()) {
            return response()->json([
                'success' => false,
                'message' => 'Esta disciplina no está disponible.'
            ], 422);
        }

        // Verificar si la disciplina tiene fechas válidas
        if (!$disciplina->tieneFechasValidas()) {
            return response()->json([
                'success' => false,
                'message' => 'Esta disciplina no tiene fechas de inscripción definidas.'
            ], 422);
        }

        // Verificar si el usuario ya está inscrito en esta disciplina (excluyendo canceladas)
        $inscripcionExistente = Inscripcion::where('id_usuario', $usuario->id_usuario)
            ->where('id_disciplina', $idDisciplina)
            ->where('estado', '!=', Inscripcion::ESTADO_CANCELADO)
            ->first();

        if ($inscripcionExistente) {
            return response()->json([
                'success' => false,
                'message' => 'Ya estás inscrito en esta disciplina.'
            ], 422);
        }

        // Verificar límite de disciplinas (máximo 2) - excluyendo canceladas
        $inscripcionesActivas = Inscripcion::where('id_usuario', $usuario->id_usuario)
            ->where('estado', '!=', Inscripcion::ESTADO_CANCELADO)
            ->whereIn('estado', [Inscripcion::ESTADO_PENDIENTE, Inscripcion::ESTADO_ACEPTADO])
            ->count();

        if ($inscripcionesActivas >= 2) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has alcanzado el límite máximo de 2 disciplinas.'
            ], 422);
        }

        // Verificar estado de disponibilidad de la disciplina
        $estadoDisponibilidad = $disciplina->getEstadoDisponibilidad();

        if ($estadoDisponibilidad !== 'disponible') {
            $mensaje = match ($estadoDisponibilidad) {
                'no_iniciada' => 'El período de inscripción para esta disciplina aún no ha comenzado.',
                'expirada' => 'El período de inscripción para esta disciplina ha finalizado.',
                'cupo_lleno' => 'Esta disciplina no tiene cupos disponibles.',
                default => 'Esta disciplina no está disponible para inscripción.'
            };

            return response()->json([
                'success' => false,
                'message' => $mensaje
            ], 422);
        }

        // Verificar cupo disponible
        if (!$disciplina->tieneCupoDisponible()) {
            return response()->json([
                'success' => false,
                'message' => 'Esta disciplina no tiene cupos disponibles.'
            ], 422);
        }

        // Crear la inscripción
        try {
            $inscripcion = Inscripcion::crearInscripcion($usuario->id_usuario, $idDisciplina);

            // Crear notificación para el comité
            Notificacion::create([
                'tipo' => 'inscripcion',
                'destinatarios' => 'comite',
                'asunto' => 'Nueva inscripción a disciplina',
                'mensaje' => "Una nueva inscripción ha sido realizada por {$usuario->nombre_completo} en la disciplina '{$disciplina->nombre}'. Revisa la solicitud lo más pronto posible.",
                'leida' => false,
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inscripción realizada correctamente. Estado: Pendiente',
                'inscripcion' => [
                    'id' => $inscripcion->id_inscripcion,
                    'estado' => $inscripcion->estado,
                    'estado_formateado' => $inscripcion->getEstadoFormateado(),
                    'clase_estado' => $inscripcion->getClaseEstado()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al realizar la inscripción: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancelar inscripción (solo si está pendiente)
     */
    public function cancelarInscripcion(Request $request)
    {
        $request->validate([
            'id_inscripcion' => 'required|exists:inscripciones,id_inscripcion'
        ]);

        // Obtener el ID del usuario de la sesión
        $userId = session('user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.'
            ], 401);
        }

        $usuario = Usuario::find($userId);
        $inscripcion = Inscripcion::where('id_inscripcion', $request->id_inscripcion)
            ->where('id_usuario', $usuario->id_usuario)
            ->firstOrFail();

        // Solo se puede cancelar si está pendiente
        if (!$inscripcion->estaPendiente()) {
            return response()->json([
                'success' => false,
                'message' => 'Solo puedes cancelar inscripciones que están pendientes.'
            ], 422);
        }

        try {
            // Cambiar estado a Cancelado en lugar de eliminar
            $inscripcion->update([
                'estado' => Inscripcion::ESTADO_CANCELADO,
                'fecha_validacion' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inscripción cancelada correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la inscripción: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener información de una disciplina específica
     */
    public function obtenerDisciplina($id)
    {
        $disciplina = Disciplina::activas()
            ->withCount(['inscripcionesAceptadas as inscripciones_aceptadas_count'])
            ->findOrFail($id);

        $disciplina->cupos_disponibles = $disciplina->getCuposDisponibles();
        $disciplina->porcentaje_ocupado = $disciplina->cupo_maximo > 0
            ? round(($disciplina->inscripciones_aceptadas_count / $disciplina->cupo_maximo) * 100)
            : 0;

        // Agregar información de fechas y estado
        $disciplina->estado_disponibilidad = $disciplina->getEstadoDisponibilidad();
        $disciplina->texto_estado_disponibilidad = $disciplina->getTextoEstadoDisponibilidad();
        $disciplina->fecha_inicio_formateada = $disciplina->fecha_inicio ? $disciplina->fecha_inicio->format('d/m/Y') : null;
        $disciplina->fecha_fin_formateada = $disciplina->fecha_fin ? $disciplina->fecha_fin->format('d/m/Y') : null;
        $disciplina->dias_restantes = $disciplina->getDiasRestantes();
        $disciplina->texto_dias_restantes = $disciplina->getTextoDiasRestantes();

        return response()->json([
            'success' => true,
            'disciplina' => [
                'id' => $disciplina->id_disciplina,
                'nombre' => $disciplina->nombre,
                'categoria' => $disciplina->categoria,
                'categoria_formateada' => $disciplina->getCategoriaFormateada(),
                'genero' => $disciplina->genero,
                'genero_formateado' => $disciplina->getGeneroFormateado(),
                'descripcion' => $disciplina->descripcion,
                'instrucciones' => $disciplina->instrucciones,
                'cupo_maximo' => $disciplina->cupo_maximo,
                'cupos_disponibles' => $disciplina->cupos_disponibles,
                'porcentaje_ocupado' => $disciplina->porcentaje_ocupado,
                'inscripciones_aceptadas' => $disciplina->inscripciones_aceptadas_count,
                'fecha_inicio' => $disciplina->fecha_inicio,
                'fecha_fin' => $disciplina->fecha_fin,
                'fecha_inicio_formateada' => $disciplina->fecha_inicio_formateada,
                'fecha_fin_formateada' => $disciplina->fecha_fin_formateada,
                'estado_disponibilidad' => $disciplina->estado_disponibilidad,
                'texto_estado_disponibilidad' => $disciplina->texto_estado_disponibilidad,
                'dias_restantes' => $disciplina->dias_restantes,
                'texto_dias_restantes' => $disciplina->texto_dias_restantes
            ]
        ]);
    }

    /**
     * Mostrar el listado de inscripciones del usuario
     */
    public function inscripciones()
    {
        // Obtener el ID del usuario de la sesión
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        // Obtener el usuario
        $usuario = Usuario::find($userId);

        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Usuario no válido.');
        }

        // Obtener todas las inscripciones del usuario (incluyendo canceladas para historial)
        $inscripciones = Inscripcion::where('id_usuario', $usuario->id_usuario)
            ->with(['disciplina'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Contar inscripciones activas (pendientes o aceptadas)
        $totalActivas = $inscripciones->whereIn('estado', [
            Inscripcion::ESTADO_PENDIENTE,
            Inscripcion::ESTADO_ACEPTADO
        ])->count();

        return view('participante.inscripciones', compact(
            'inscripciones',
            'totalActivas',
            'usuario'
        ));
    }

    /**
     * Obtener el historial de una inscripción específica
     */
    public function obtenerHistorialInscripcion($idInscripcion)
    {
        try {
            $userId = session('user_id');

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado.'
                ], 401);
            }

            $inscripcion = Inscripcion::where('id_inscripcion', $idInscripcion)
                ->where('id_usuario', $userId)
                ->with(['disciplina'])
                ->firstOrFail();

            // Construir el historial de la inscripción
            $historial = $this->construirHistorialInscripcion($inscripcion);

            return response()->json([
                'success' => true,
                'historial' => $historial,
                'inscripcion' => [
                    'id' => $inscripcion->id_inscripcion,
                    'estado' => $inscripcion->estado,
                    'estado_formateado' => $inscripcion->getEstadoFormateado(),
                    'fecha_inscripcion' => $inscripcion->fecha_inscripcion->format('d M Y - h:i A'),
                    'fecha_validacion' => $inscripcion->fecha_validacion ? $inscripcion->fecha_validacion->format('d M Y - h:i A') : null,
                    'disciplina' => [
                        'nombre' => $inscripcion->disciplina->nombre,
                        'categoria' => $inscripcion->disciplina->categoria,
                        'categoria_formateada' => $inscripcion->disciplina->getCategoriaFormateada()
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar el historial de la inscripción.'
            ], 500);
        }
    }

    /**
     * Construir el historial de una inscripción
     */
    private function construirHistorialInscripcion($inscripcion)
    {
        $historial = [];

        // Paso 1: Inscripción enviada (siempre presente)
        $historial[] = [
            'etapa' => 'inscripcion_enviada',
            'titulo' => 'Inscripción Enviada',
            'completado' => true,
            'actual' => false,
            'fecha' => $inscripcion->fecha_inscripcion->format('d M Y - h:i A'),
            'icono' => 'check',
            'clase' => 'completed'
        ];

        // Paso 2: Según el estado actual
        switch ($inscripcion->estado) {
            case Inscripcion::ESTADO_PENDIENTE:
                $historial[] = [
                    'etapa' => 'revision_comite',
                    'titulo' => 'Revisión del Comité',
                    'completado' => false,
                    'actual' => true,
                    'fecha' => 'En proceso - Estimado: 2 días',
                    'icono' => 'sync-alt',
                    'clase' => 'current'
                ];
                $historial[] = [
                    'etapa' => 'resultado_final',
                    'titulo' => 'Resultado Final',
                    'completado' => false,
                    'actual' => false,
                    'fecha' => 'Pendiente',
                    'icono' => 'flag',
                    'clase' => 'upcoming'
                ];
                break;

            case Inscripcion::ESTADO_ACEPTADO:
                $historial[] = [
                    'etapa' => 'aprobacion_comite',
                    'titulo' => 'Aprobación del Comité',
                    'completado' => true,
                    'actual' => false,
                    'fecha' => $inscripcion->fecha_validacion->format('d M Y - h:i A'),
                    'icono' => 'check',
                    'clase' => 'completed'
                ];
                $historial[] = [
                    'etapa' => 'participacion_activa',
                    'titulo' => 'Participación Activa',
                    'completado' => false,
                    'actual' => true,
                    'fecha' => null,
                    'icono' => 'flag',
                    'clase' => 'current'
                ];
                break;

            case Inscripcion::ESTADO_RECHAZADO:
                $historial[] = [
                    'etapa' => 'rechazo_comite',
                    'titulo' => 'Rechazo del Comité',
                    'completado' => true,
                    'actual' => false,
                    'fecha' => $inscripcion->fecha_validacion->format('d M Y - h:i A'),
                    'icono' => 'times',
                    'clase' => 'completed error'
                ];
                $historial[] = [
                    'etapa' => 'resultado_final',
                    'titulo' => 'Resultado Final',
                    'completado' => true,
                    'actual' => false,
                    'fecha' => 'Rechazado',
                    'icono' => 'flag',
                    'clase' => 'upcoming'
                ];
                break;

            case Inscripcion::ESTADO_CANCELADO:
                $historial[] = [
                    'etapa' => 'cancelacion',
                    'titulo' => 'Cancelado',
                    'completado' => true,
                    'actual' => false,
                    'fecha' => $inscripcion->fecha_validacion->format('d M Y - h:i A'),
                    'icono' => 'times',
                    'clase' => 'completed error'
                ];
                break;
        }

        return $historial;
    }

    /**
     * Descargar constancia de inscripción
     */
    public function descargarConstancia($idInscripcion)
    {
        try {
            $userId = session('user_id');

            if (!$userId) {
                return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
            }

            $inscripcion = Inscripcion::where('id_inscripcion', $idInscripcion)
                ->where('id_usuario', $userId)
                ->where('estado', Inscripcion::ESTADO_ACEPTADO)
                ->with(['disciplina', 'usuario'])
                ->firstOrFail();

            // Aquí iría la lógica para generar el PDF de la constancia
            // Por ahora simulamos la descarga
            return response()->json([
                'success' => true,
                'message' => 'Constancia generada correctamente',
                'download_url' => '#' // URL temporal
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo generar la constancia. Verifica que la inscripción esté aceptada.'
            ], 500);
        }
    }

    // Reemplaza las funciones historial() y obtenerDetallesHistorial() con estas:

    public function historial()
    {
        // Obtener el ID del usuario de la sesión
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        // Obtener el usuario
        $usuario = Usuario::find($userId);

        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Usuario no válido.');
        }

        // Obtener todas las inscripciones históricas del usuario
        $inscripcionesHistorial = HistorialInscripcionDisciplina::with('historialDisciplina')
            ->where('id_usuario', $usuario->id_usuario)
            ->orderBy('created_at', 'desc')
            ->get();

        // Preparar datos para la vista
        $estadisticas = [
            'total_participaciones' => $inscripcionesHistorial->where('participo', true)->count(),
            'total_inscripciones' => $inscripcionesHistorial->count(),
            'disciplinas_diferentes' => $inscripcionesHistorial->unique('id_historial_disciplina')->count(),
        ];

        return view('participante.historial', compact('inscripcionesHistorial', 'estadisticas'));
    }

    public function obtenerDetallesHistorial($idHistorialInscripcion)
    {
        try {
            // Obtener el ID del usuario de la sesión
            $userId = session('user_id');

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado.'
                ], 401);
            }

            $usuario = Usuario::find($userId);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no válido.'
                ], 401);
            }

            $inscripcionHistorial = HistorialInscripcionDisciplina::with('historialDisciplina')
                ->where('id_historial_inscripcion', $idHistorialInscripcion)
                ->where('id_usuario', $usuario->id_usuario)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'disciplina' => [
                        'nombre' => $inscripcionHistorial->historialDisciplina->nombre_disciplina,
                        'categoria' => $inscripcionHistorial->historialDisciplina->categoria,
                        'genero' => $inscripcionHistorial->historialDisciplina->genero,
                        'descripcion' => $inscripcionHistorial->historialDisciplina->descripcion,
                        'periodo_inicio' => $inscripcionHistorial->historialDisciplina->periodo_inicio->format('d/m/Y'),
                        'periodo_fin' => $inscripcionHistorial->historialDisciplina->periodo_fin->format('d/m/Y'),
                        'fecha_finalizacion' => $inscripcionHistorial->historialDisciplina->fecha_finalizacion->format('d/m/Y H:i'),
                        'estado_finalizacion' => $inscripcionHistorial->historialDisciplina->getEstadoFinalizacionFormateado(),
                        'cupo_maximo' => $inscripcionHistorial->historialDisciplina->cupo_maximo,
                        'total_inscritos' => $inscripcionHistorial->historialDisciplina->total_inscritos,
                    ],
                    'inscripcion' => [
                        'fecha_inscripcion' => $inscripcionHistorial->fecha_inscripcion_original->format('d/m/Y H:i'),
                        'estado_inscripcion' => $inscripcionHistorial->getEstadoFormateado(),
                        'participo' => $inscripcionHistorial->participo,
                        'fecha_registro_historial' => $inscripcionHistorial->created_at->format('d/m/Y H:i'),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener la información del historial'
            ], 404);
        }
    }

    /**
     * Obtener información completa de una inscripción para el modal de detalles
     */
    public function obtenerDetallesInscripcion($idInscripcion)
    {
        try {
            $userId = session('user_id');

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado.'
                ], 401);
            }

            $inscripcion = Inscripcion::where('id_inscripcion', $idInscripcion)
                ->where('id_usuario', $userId)
                ->with(['disciplina'])
                ->firstOrFail();

            $disciplina = $inscripcion->disciplina;

            return response()->json([
                'success' => true,
                'data' => [
                    'inscripcion' => [
                        'id' => $inscripcion->id_inscripcion,
                        'estado' => $inscripcion->estado,
                        'estado_formateado' => $inscripcion->getEstadoFormateado(),
                        'fecha_inscripcion' => $inscripcion->fecha_inscripcion->format('d/m/Y H:i'),
                        'fecha_validacion' => $inscripcion->fecha_validacion ? $inscripcion->fecha_validacion->format('d/m/Y H:i') : null,
                    ],
                    'disciplina' => [
                        'id' => $disciplina->id_disciplina,
                        'nombre' => $disciplina->nombre,
                        'categoria' => $disciplina->categoria,
                        'categoria_formateada' => $disciplina->getCategoriaFormateada(),
                        'genero' => $disciplina->genero,
                        'genero_formateado' => $disciplina->getGeneroFormateado(),
                        'descripcion' => $disciplina->descripcion,
                        'instrucciones' => $disciplina->instrucciones,
                        'cupo_maximo' => $disciplina->cupo_maximo,
                        'cupos_disponibles' => $disciplina->getCuposDisponibles(),
                        'fecha_inicio' => $disciplina->fecha_inicio ? $disciplina->fecha_inicio->format('d/m/Y') : 'No definida',
                        'fecha_fin' => $disciplina->fecha_fin ? $disciplina->fecha_fin->format('d/m/Y') : 'No definida',
                        'dias_restantes' => $disciplina->getDiasRestantes(),
                        'texto_dias_restantes' => $disciplina->getTextoDiasRestantes(),
                        'estado_disponibilidad' => $disciplina->getEstadoDisponibilidad(),
                        'texto_estado_disponibilidad' => $disciplina->getTextoEstadoDisponibilidad(),
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener la información de la inscripción.'
            ], 404);
        }
    }
}
