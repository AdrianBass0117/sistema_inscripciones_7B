<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Disciplina;
use App\Models\Inscripcion;
use App\Models\Documento;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;


class PrincipalController extends Controller
{
    public function principal()
    {
        // Obtener estadísticas detalladas
        $estadisticas = $this->obtenerEstadisticasComite();

        // Obtener métricas del día
        $metricasHoy = $this->obtenerMetricasHoy();

        return view('comite.principal', compact('estadisticas', 'metricasHoy'));
    }

    private function obtenerEstadisticasComite()
    {
        return [
            // Estadísticas de usuarios
            'pendientes_validar' => Usuario::where('estado_cuenta', Usuario::ESTADO_PENDIENTE)->count(),
            'usuarios_aceptados' => Usuario::where('estado_cuenta', Usuario::ESTADO_VALIDADO)->count(),
            'usuarios_rechazados' => Usuario::where('estado_cuenta', Usuario::ESTADO_RECHAZADO)->count(),
            'usuarios_suspendidos' => Usuario::where('estado_cuenta', Usuario::ESTADO_SUSPENDIDO)->count(),

            // Estadísticas de disciplinas
            'disciplinas_activas' => Disciplina::where('activa', true)->count(),
            'disciplinas_inactivas' => Disciplina::where('activa', false)->count(),
            'disciplinas_deportivas' => Disciplina::activas()->deportivas()->count(),
            'disciplinas_culturales' => Disciplina::activas()->culturales()->count(),

            // Disciplinas que necesitan atención
            'disciplinas_por_configurar' => Disciplina::where('activa', true)
                ->where(function ($query) {
                    $query->whereNull('fecha_inicio')
                        ->orWhereNull('fecha_fin')
                        ->orWhere('cupo_maximo', '<=', 0);
                })
                ->count(),

            // Estadísticas de documentos
            'documentos_pendientes' => Documento::where('estado', Documento::ESTADO_PENDIENTE)->count(),
            'documentos_aprobados' => Documento::where('estado', Documento::ESTADO_APROBADO)->count(),
            'documentos_rechazados' => Documento::where('estado', Documento::ESTADO_RECHAZADO)->count(),

            // Validaciones urgentes
            'validaciones_urgentes' => Documento::where('estado', Documento::ESTADO_PENDIENTE)
                ->where('created_at', '<=', now()->subHours(48))
                ->count(),

            // Estadísticas de inscripciones
            'inscripciones_pendientes' => Inscripcion::where('estado', Inscripcion::ESTADO_PENDIENTE)->count(),
            'inscripciones_aceptadas' => Inscripcion::where('estado', Inscripcion::ESTADO_ACEPTADO)->count(),
            'inscripciones_rechazadas' => Inscripcion::where('estado', Inscripcion::ESTADO_RECHAZADO)->count(),
        ];
    }

    private function obtenerMetricasHoy()
    {
        $hoy = now()->startOfDay();
        $ayer = now()->subDay()->startOfDay();
        $inicioHoy = $hoy;
        $finHoy = now()->endOfDay();

        // Documentos validados hoy
        $documentosHoy = Documento::where('estado', Documento::ESTADO_APROBADO)
            ->whereBetween('updated_at', [$inicioHoy, $finHoy])
            ->count();

        // Documentos validados ayer (para comparación)
        $documentosAyer = Documento::where('estado', Documento::ESTADO_APROBADO)
            ->whereBetween('updated_at', [$ayer, $ayer->copy()->endOfDay()])
            ->count();

        // Calcular porcentaje de cambio
        $porcentajeCambioDocumentos = $documentosAyer > 0
            ? round((($documentosHoy - $documentosAyer) / $documentosAyer) * 100, 1)
            : ($documentosHoy > 0 ? 100 : 0);

        // Tasa de aprobación hoy
        $totalDocumentosHoy = Documento::whereBetween('updated_at', [$inicioHoy, $finHoy])
            ->whereIn('estado', [Documento::ESTADO_APROBADO, Documento::ESTADO_RECHAZADO])
            ->count();

        $documentosAprobadosHoy = Documento::where('estado', Documento::ESTADO_APROBADO)
            ->whereBetween('updated_at', [$inicioHoy, $finHoy])
            ->count();

        $tasaAprobacion = $totalDocumentosHoy > 0
            ? round(($documentosAprobadosHoy / $totalDocumentosHoy) * 100, 1)
            : 0;

        // Tiempo promedio de respuesta (en horas)
        $tiempoRespuesta = Documento::where('estado', Documento::ESTADO_APROBADO)
            ->whereBetween('updated_at', [$inicioHoy, $finHoy])
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
            ->first()->avg_hours ?? 0;

        // Tiempo de respuesta ayer (para comparación)
        $tiempoRespuestaAyer = Documento::where('estado', Documento::ESTADO_APROBADO)
            ->whereBetween('updated_at', [$ayer, $ayer->copy()->endOfDay()])
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
            ->first()->avg_hours ?? 0;

        // Usuarios nuevos aceptados hoy
        $usuariosNuevosAceptados = Usuario::where('estado_cuenta', Usuario::ESTADO_VALIDADO)
            ->whereBetween('updated_at', [$inicioHoy, $finHoy])
            ->count();

        // Usuarios nuevos aceptados ayer (para comparación)
        $usuariosNuevosAyer = Usuario::where('estado_cuenta', Usuario::ESTADO_VALIDADO)
            ->whereBetween('updated_at', [$ayer, $ayer->copy()->endOfDay()])
            ->count();

        $porcentajeCambioUsuarios = $usuariosNuevosAyer > 0
            ? round((($usuariosNuevosAceptados - $usuariosNuevosAyer) / $usuariosNuevosAyer) * 100, 1)
            : ($usuariosNuevosAceptados > 0 ? 100 : 0);

        return [
            'documentos_validados' => [
                'valor' => $documentosHoy,
                'tendencia' => $this->determinarTendencia($porcentajeCambioDocumentos),
                'porcentaje' => abs($porcentajeCambioDocumentos),
                'texto_tendencia' => $porcentajeCambioDocumentos >= 0 ?
                    "{$porcentajeCambioDocumentos}% más que ayer" :
                    "{$porcentajeCambioDocumentos}% menos que ayer"
            ],
            'tasa_aprobacion' => [
                'valor' => $tasaAprobacion,
                'tendencia' => 'stable',
                'texto_tendencia' => 'Consistente'
            ],
            'tiempo_respuesta' => [
                'valor' => round($tiempoRespuesta, 1),
                'tendencia' => $tiempoRespuesta < $tiempoRespuestaAyer ? 'positive' : 'negative',
                'texto_tendencia' => $tiempoRespuesta < $tiempoRespuestaAyer ? 'Mejorando' : 'Por mejorar'
            ],
            'usuarios_aceptados' => [
                'valor' => $usuariosNuevosAceptados,
                'tendencia' => $this->determinarTendencia($porcentajeCambioUsuarios),
                'porcentaje' => abs($porcentajeCambioUsuarios),
                'texto_tendencia' => $porcentajeCambioUsuarios >= 0 ?
                    "{$porcentajeCambioUsuarios}% más que ayer" :
                    "{$porcentajeCambioUsuarios}% menos que ayer"
            ]
        ];
    }

    private function determinarTendencia($porcentaje)
    {
        if ($porcentaje > 5) return 'positive';
        if ($porcentaje < -5) return 'negative';
        return 'stable';
    }


    public function principalParticipante()
    {
        // Verificar que el usuario esté en sesión y sea de tipo 'usuario'
        if (session('user_type') !== 'usuario' || !session('user_id')) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión como usuario para acceder a esta página.');
        }

        // Obtener el usuario desde la base de datos usando el ID de sesión
        $usuario = Usuario::find(session('user_id'));

        // Verificar que el usuario existe
        if (!$usuario) {
            // Limpiar sesión si el usuario no existe
            session()->forget(['user_type', 'user_id', 'user_email', 'user_name']);
            return redirect()->route('login')->with('error', 'Usuario no válido.');
        }

        // Verificar que el usuario esté validado
        if ($usuario->estado_cuenta !== Usuario::ESTADO_VALIDADO) {
            return redirect()->route('aspirante')->with('error', 'Tu cuenta aún no ha sido validada.');
        }

        // Obtener estadísticas del participante
        $estadisticas = $this->obtenerEstadisticasParticipante($usuario->id_usuario);

        // Obtener disciplinas del participante
        $disciplinasParticipante = $this->obtenerDisciplinasParticipante($usuario->id_usuario);

        // Obtener disciplinas disponibles
        $disciplinasDisponibles = $this->obtenerDisciplinasDisponibles($usuario->id_usuario);

        // Obtener notificaciones recientes
        $notificaciones = $this->obtenerNotificacionesRecientes();

        return view('participante.principal', compact(
            'estadisticas',
            'disciplinasParticipante',
            'disciplinasDisponibles',
            'notificaciones',
            'usuario' // También pasar el usuario a la vista por si lo necesitas
        ));
    }

    private function obtenerEstadisticasParticipante($idUsuario)
    {
        // Si no se pasa ID, usar el de sesión
        if (!$idUsuario) {
            $idUsuario = session('user_id');
        }

        // Obtener inscripciones del usuario
        $inscripcionesAceptadas = Inscripcion::where('id_usuario', $idUsuario)
            ->aceptadas()
            ->count();

        $inscripcionesPendientes = Inscripcion::where('id_usuario', $idUsuario)
            ->pendientes()
            ->count();

        $totalInscripciones = $inscripcionesAceptadas + $inscripcionesPendientes;

        // Calcular progreso (máximo 2 disciplinas permitidas)
        $progreso = 0;
        if ($totalInscripciones > 0) {
            // 50% por inscripción pendiente, 100% por inscripción aceptada
            $progreso = (($inscripcionesPendientes * 50) + ($inscripcionesAceptadas * 100)) / 2;
            $progreso = min($progreso, 100); // Máximo 100%
        }

        // Verificar estado de la cuenta
        $usuario = Usuario::find($idUsuario);
        $estadoCuenta = $usuario->estaValidado() ? 'Aprobado' : 'Pendiente';

        return [
            'estado_inscripcion' => $estadoCuenta,
            'disciplinas_inscritas' => $totalInscripciones,
            'disciplinas_aceptadas' => $inscripcionesAceptadas,
            'disciplinas_pendientes' => $inscripcionesPendientes,
            'progreso' => round($progreso),
            'maximo_disciplinas' => 2
        ];
    }

    private function obtenerDisciplinasParticipante($idUsuario)
    {
        if (!$idUsuario) {
            $idUsuario = session('user_id');
        }

        return Inscripcion::with('disciplina')
            ->where('id_usuario', $idUsuario)
            ->whereIn('estado', [Inscripcion::ESTADO_PENDIENTE, Inscripcion::ESTADO_ACEPTADO])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($inscripcion) {
                return [
                    'id_disciplina' => $inscripcion->disciplina->id_disciplina,
                    'nombre' => $inscripcion->disciplina->nombre,
                    'estado' => $inscripcion->estado,
                    'estado_formateado' => $inscripcion->getEstadoFormateado(),
                    'clase_estado' => $inscripcion->getClaseEstado(),
                    'fecha_inscripcion' => $inscripcion->fecha_inscripcion,
                    'icono' => $this->obtenerIconoDisciplina($inscripcion->disciplina->nombre),
                    'tipo' => 'inscrita'
                ];
            });
    }

    private function obtenerDisciplinasDisponibles($idUsuario)
    {
        if (!$idUsuario) {
            $idUsuario = session('user_id');
        }

        // Obtener disciplinas activas y vigentes donde el usuario no esté inscrito
        $disciplinasInscritas = Inscripcion::where('id_usuario', $idUsuario)
            ->whereIn('estado', [Inscripcion::ESTADO_PENDIENTE, Inscripcion::ESTADO_ACEPTADO])
            ->pluck('id_disciplina')
            ->toArray();

        return Disciplina::activas()
            ->vigentes()
            ->whereNotIn('id_disciplina', $disciplinasInscritas)
            ->conCupoDisponible()
            ->orderBy('nombre')
            ->get()
            ->map(function ($disciplina) {
                return [
                    'id_disciplina' => $disciplina->id_disciplina,
                    'nombre' => $disciplina->nombre,
                    'cupos_disponibles' => $disciplina->getCuposDisponibles(),
                    'icono' => $this->obtenerIconoDisciplina($disciplina->nombre),
                    'tipo' => 'disponible'
                ];
            });
    }

    private function obtenerNotificacionesRecientes()
    {
        return Notificacion::whereIn('destinatarios', ['todos', 'personal'])
            ->recientes()
            ->limit(5)
            ->get()
            ->map(function ($notificacion) {
                return [
                    'tipo' => $notificacion->tipo,
                    'asunto' => $notificacion->asunto,
                    'mensaje' => $notificacion->mensaje,
                    'tiempo_transcurrido' => $notificacion->getTiempoTranscurrido(),
                    'clase_tipo' => $notificacion->getClaseTipo(),
                    'icono' => $this->obtenerIconoNotificacion($notificacion->tipo)
                ];
            });
    }

    private function obtenerIconoDisciplina($nombreDisciplina)
    {
        $iconos = [
            'Fútbol' => 'futbol',
            'Baloncesto' => 'basketball-ball',
            'Tenis de Mesa' => 'table-tennis',
            'Voleibol' => 'volleyball-ball',
            'Atletismo' => 'running',
            'Natación' => 'swimmer',
            'Ajedrez' => 'chess',
            'Atletismo' => 'running',
            'Bádminton' => 'table-tennis',
            'Boxeo' => 'fist-raised',
            'Canto' => 'music',
            'Danza' => 'theater-masks',
            'Pintura' => 'palette',
            'Teatro' => 'theater-masks',
            'Oratoria' => 'microphone'
        ];

        foreach ($iconos as $key => $icono) {
            if (stripos($nombreDisciplina, $key) !== false) {
                return $icono;
            }
        }

        return 'running'; // Icono por defecto
    }

    private function obtenerIconoNotificacion($tipo)
    {
        return match ($tipo) {
            'urgente' => 'exclamation-triangle',
            'recordatorio' => 'info-circle',
            default => 'check-circle'
        };
    }

    public function inscribirDisciplina(Request $request, $idDisciplina)
    {
        $usuario = Auth::user();

        // Verificar si ya está inscrito
        if (Inscripcion::usuarioInscritoEnDisciplina($usuario->id_usuario, $idDisciplina)) {
            return redirect()->route('personal.disciplinas')->with('error', 'Ya estás inscrito en esta disciplina.');
        }

        // Verificar límite de inscripciones (máximo 2)
        $inscripcionesActivas = Inscripcion::where('id_usuario', $usuario->id_usuario)
            ->whereIn('estado', [Inscripcion::ESTADO_PENDIENTE, Inscripcion::ESTADO_ACEPTADO])
            ->count();

        if ($inscripcionesActivas >= 2) {
            return redirect()->route('personal.disciplinas')->with('error', 'Ya has alcanzado el límite de 2 disciplinas.');
        }

        // Verificar si la disciplina existe y tiene cupo
        $disciplina = Disciplina::find($idDisciplina);
        if (!$disciplina || !$disciplina->activa || !$disciplina->tieneCupoDisponible()) {
            return redirect()->route('personal.disciplinas')->with('error', 'La disciplina no está disponible o no tiene cupo.');
        }

        // Crear inscripción usando tu controlador existente
        try {
            // Redirigir al controlador de disciplinas para manejar la inscripción
            return redirect()->route('disciplinas.usuario.inscribir', ['id' => $idDisciplina])
                ->with('disciplina_seleccionada', $idDisciplina);
        } catch (\Exception $e) {
            return redirect()->route('personal.disciplinas')->with('error', 'Error al procesar la inscripción.');
        }
    }
}
