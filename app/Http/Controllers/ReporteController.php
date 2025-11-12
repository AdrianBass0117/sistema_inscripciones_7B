<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use App\Models\Inscripcion;
use App\Models\Usuario;
use App\Models\HistorialDisciplina;
use App\Models\Constancia;
use App\Models\HistorialInscripcionDisciplina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Lista todas las disciplinas diferenciando activas e inactivas
     */
    public function listarDisciplinas()
    {
        $disciplinas = Disciplina::withCount([
            'inscripciones as inscripciones_aceptadas_count' => function ($query) {
                $query->where('estado', Inscripcion::ESTADO_ACEPTADO);
            },
            'inscripciones as inscripciones_pendientes_count' => function ($query) {
                $query->where('estado', Inscripcion::ESTADO_PENDIENTE);
            }
        ])
            ->orderBy('activa', 'desc')
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'disciplinas' => $disciplinas->map(function ($disciplina) {
                return $this->formatearDisciplina($disciplina);
            }),
            'estadisticas' => [
                'total_activas' => $disciplinas->where('activa', true)->count(),
                'total_inactivas' => $disciplinas->where('activa', false)->count(),
                'total_general' => $disciplinas->count()
            ]
        ]);
    }

    /**
     * Recupera datos de disciplinas pasadas con su historial y participantes
     */
    public function disciplinasConHistorial()
    {
        $disciplinasConHistorial = Disciplina::whereHas('historial') // Asumiendo que agregas esta relación
            ->with(['historial.inscripcionesHistorial.usuario'])
            ->get();

        // Si no tienes la relación directa, usar este approach:
        $disciplinasIdsConHistorial = HistorialDisciplina::pluck('id_disciplina')->unique();

        $disciplinas = Disciplina::whereIn('id_disciplina', $disciplinasIdsConHistorial)
            ->with(['historialRelacion.inscripcionesHistorial.usuario']) // Relación temporal
            ->get();

        $resultado = $disciplinas->map(function ($disciplina) {
            $historiales = HistorialDisciplina::where('id_disciplina', $disciplina->id_disciplina)
                ->with(['inscripcionesHistorial.usuario'])
                ->get();

            return [
                'disciplina' => $this->formatearDisciplina($disciplina),
                'historiales' => $historiales->map(function ($historial) {
                    return [
                        'id_historial' => $historial->id_historial,
                        'nombre_disciplina_historico' => $historial->nombre_disciplina,
                        'periodo_inicio' => $historial->periodo_inicio,
                        'periodo_fin' => $historial->periodo_fin,
                        'estado_finalizacion' => $historial->estado_finalizacion,
                        'total_inscritos' => $historial->total_inscritos,
                        'tasa_participacion' => $historial->getTasaParticipacion(),
                        'fecha_finalizacion' => $historial->fecha_finalizacion,
                        'participantes' => $historial->inscripcionesHistorial->map(function ($inscripcionHistorial) {
                            return [
                                'id_usuario' => $inscripcionHistorial->id_usuario,
                                'nombre_usuario' => $inscripcionHistorial->nombre_usuario,
                                'email_usuario' => $inscripcionHistorial->email_usuario,
                                'estado_inscripcion' => $inscripcionHistorial->estado_inscripcion,
                                'participo' => $inscripcionHistorial->participo,
                                'fecha_inscripcion_original' => $inscripcionHistorial->fecha_inscripcion_original
                            ];
                        })
                    ];
                })
            ];
        });

        return response()->json([
            'disciplinas_con_historial' => $resultado,
            'total_disciplinas_con_historial' => $resultado->count()
        ]);
    }

    /**
     * Recupera datos específicos de una disciplina con su historial
     */
    public function disciplinaHistorial($idDisciplina)
    {
        $disciplina = Disciplina::findOrFail($idDisciplina);

        $historiales = HistorialDisciplina::where('id_disciplina', $idDisciplina)
            ->with(['inscripcionesHistorial.usuario'])
            ->orderBy('fecha_finalizacion', 'desc')
            ->get();

        return response()->json([
            'disciplina' => $this->formatearDisciplina($disciplina),
            'historial' => $historiales->map(function ($historial) {
                return [
                    'id_historial' => $historial->id_historial,
                    'nombre_disciplina_historico' => $historial->nombre_disciplina,
                    'periodo' => [
                        'inicio' => $historial->periodo_inicio,
                        'fin' => $historial->periodo_fin
                    ],
                    'estado_finalizacion' => $historial->estado_finalizacion,
                    'estado_finalizacion_formateado' => $historial->getEstadoFinalizacionFormateado(),
                    'total_inscritos' => $historial->total_inscritos,
                    'tasa_participacion' => $historial->getTasaParticipacion(),
                    'fecha_finalizacion' => $historial->fecha_finalizacion,
                    'participantes' => $historial->inscripcionesHistorial->map(function ($inscripcionHistorial) {
                        return [
                            'id_usuario' => $inscripcionHistorial->id_usuario,
                            'nombre_usuario' => $inscripcionHistorial->nombre_usuario,
                            'email_usuario' => $inscripcionHistorial->email_usuario,
                            'estado_inscripcion' => $inscripcionHistorial->estado_inscripcion,
                            'estado_inscripcion_formateado' => $inscripcionHistorial->getEstadoFormateado(),
                            'participo' => $inscripcionHistorial->participo,
                            'fecha_inscripcion_original' => $inscripcionHistorial->fecha_inscripcion_original,
                            'clase_estado' => $inscripcionHistorial->getClaseEstado()
                        ];
                    })
                ];
            }),
            'total_historiales' => $historiales->count()
        ]);
    }

    /**
     * Recupera todas las inscripciones con detalles completos
     */
    public function todasLasInscripciones()
    {
        $inscripciones = Inscripcion::with(['usuario', 'disciplina'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'inscripciones' => $inscripciones->map(function ($inscripcion) {
                return [
                    'id_inscripcion' => $inscripcion->id_inscripcion,
                    'fecha_inscripcion' => $inscripcion->fecha_inscripcion,
                    'fecha_validacion' => $inscripcion->fecha_validacion,
                    'estado' => $inscripcion->estado,
                    'estado_formateado' => $inscripcion->getEstadoFormateado(),
                    'clase_estado' => $inscripcion->getClaseEstado(),
                    'tiempo_transcurrido' => $inscripcion->getTiempoTranscurrido(),

                    // Datos del usuario
                    'usuario' => $inscripcion->usuario ? [
                        'id_usuario' => $inscripcion->usuario->id_usuario,
                        'numero_trabajador' => $inscripcion->usuario->numero_trabajador,
                        'nombre_completo' => $inscripcion->usuario->nombre_completo,
                        'email' => $inscripcion->usuario->email,
                        'telefono' => $inscripcion->usuario->telefono,
                        'estado_cuenta' => $inscripcion->usuario->estado_cuenta
                    ] : null,

                    // Datos de la disciplina
                    'disciplina' => $inscripcion->disciplina ? [
                        'id_disciplina' => $inscripcion->disciplina->id_disciplina,
                        'nombre' => $inscripcion->disciplina->nombre,
                        'categoria' => $inscripcion->disciplina->categoria,
                        'categoria_formateada' => $inscripcion->disciplina->getCategoriaFormateada(),
                        'genero' => $inscripcion->disciplina->genero,
                        'genero_formateado' => $inscripcion->disciplina->getGeneroFormateado(),
                        'activa' => $inscripcion->disciplina->activa,
                        'estado_formateado' => $inscripcion->disciplina->getEstadoFormateado(),
                        'cupo_maximo' => $inscripcion->disciplina->cupo_maximo,
                        'fecha_inicio' => $inscripcion->disciplina->fecha_inicio,
                        'fecha_fin' => $inscripcion->disciplina->fecha_fin
                    ] : null
                ];
            }),
            'estadisticas' => [
                'total_inscripciones' => $inscripciones->count(),
                'pendientes' => $inscripciones->where('estado', Inscripcion::ESTADO_PENDIENTE)->count(),
                'aceptadas' => $inscripciones->where('estado', Inscripcion::ESTADO_ACEPTADO)->count(),
                'rechazadas' => $inscripciones->where('estado', Inscripcion::ESTADO_RECHAZADO)->count(),
                'canceladas' => $inscripciones->where('estado', Inscripcion::ESTADO_CANCELADO)->count()
            ]
        ]);
    }

    /**
     * Inscripciones por estado específico
     */
    public function inscripcionesPorEstado($estado)
    {
        $estadosValidos = Inscripcion::getEstados();

        if (!in_array($estado, $estadosValidos)) {
            return response()->json([
                'error' => 'Estado no válido',
                'estados_validos' => $estadosValidos
            ], 400);
        }

        $inscripciones = Inscripcion::with(['usuario', 'disciplina'])
            ->where('estado', $estado)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'estado' => $estado,
            'estado_formateado' => (new Inscripcion())->getEstadoFormateado(),
            'inscripciones' => $inscripciones->map(function ($inscripcion) {
                return $this->formatearInscripcion($inscripcion);
            }),
            'total' => $inscripciones->count()
        ]);
    }

    /**
     * Método helper para formatear disciplina
     */
    private function formatearDisciplina($disciplina)
    {
        return [
            'id_disciplina' => $disciplina->id_disciplina,
            'nombre' => $disciplina->nombre,
            'categoria' => $disciplina->categoria,
            'categoria_formateada' => $disciplina->getCategoriaFormateada(),
            'genero' => $disciplina->genero,
            'genero_formateado' => $disciplina->getGeneroFormateado(),
            'activa' => $disciplina->activa,
            'estado_formateado' => $disciplina->getEstadoFormateado(),
            'cupo_maximo' => $disciplina->cupo_maximo,
            'inscripciones_aceptadas_count' => $disciplina->inscripciones_aceptadas_count ?? $disciplina->contarInscripcionesAceptadas(),
            'inscripciones_pendientes_count' => $disciplina->inscripciones_pendientes_count ?? $disciplina->inscripciones()->pendientes()->count(),
            'cupos_disponibles' => $disciplina->getCuposDisponibles(),
            'tiene_cupo_disponible' => $disciplina->tieneCupoDisponible(),
            'fecha_inicio' => $disciplina->fecha_inicio,
            'fecha_fin' => $disciplina->fecha_fin,
            'vigencia_formateada' => $disciplina->getVigenciaFormateada(),
            'esta_vigente' => $disciplina->estaVigente(),
            'dias_restantes' => $disciplina->getDiasRestantes(),
            'texto_dias_restantes' => $disciplina->getTextoDiasRestantes(),
            'estado_disponibilidad' => $disciplina->getEstadoDisponibilidad(),
            'texto_estado_disponibilidad' => $disciplina->getTextoEstadoDisponibilidad(),
            'clase_estado_disponibilidad' => $disciplina->getClaseEstadoDisponibilidad()
        ];
    }

    /**
     * Método helper para formatear inscripción
     */
    private function formatearInscripcion($inscripcion)
    {
        return [
            'id_inscripcion' => $inscripcion->id_inscripcion,
            'fecha_inscripcion' => $inscripcion->fecha_inscripcion,
            'fecha_validacion' => $inscripcion->fecha_validacion,
            'estado' => $inscripcion->estado,
            'estado_formateado' => $inscripcion->getEstadoFormateado(),
            'clase_estado' => $inscripcion->getClaseEstado(),
            'tiempo_transcurrido' => $inscripcion->getTiempoTranscurrido(),
            'usuario' => $inscripcion->usuario ? [
                'id_usuario' => $inscripcion->usuario->id_usuario,
                'numero_trabajador' => $inscripcion->usuario->numero_trabajador,
                'nombre_completo' => $inscripcion->usuario->nombre_completo,
                'email' => $inscripcion->usuario->email
            ] : null,
            'disciplina' => $inscripcion->disciplina ? [
                'id_disciplina' => $inscripcion->disciplina->id_disciplina,
                'nombre' => $inscripcion->disciplina->nombre,
                'categoria' => $inscripcion->disciplina->categoria
            ] : null
        ];
    }

    /**
     * Lista todos los usuarios formateados por estado de cuenta
     */
    public function listarUsuarios()
    {
        try {
            $usuarios = Usuario::withCount([
                'inscripciones as total_inscripciones',
                'inscripcionesAceptadas as inscripciones_aceptadas_count',
                'inscripcionesPendientes as inscripciones_pendientes_count',
                'documentos as total_documentos',
                'documentos as documentos_pendientes_count' => function ($query) {
                    $query->where('estado', 'Pendiente'); // Asumiendo que Documento::ESTADO_PENDIENTE = 'Pendiente'
                }
            ])
                ->orderBy('estado_cuenta')
                ->orderBy('nombre_completo')
                ->get();

            return response()->json([
                'usuarios' => $usuarios->map(function ($usuario) {
                    return $this->formatearUsuario($usuario);
                }),
                'estadisticas' => [
                    'total_usuarios' => $usuarios->count(),
                    'validados' => $usuarios->where('estado_cuenta', Usuario::ESTADO_VALIDADO)->count(),
                    'pendientes' => $usuarios->where('estado_cuenta', Usuario::ESTADO_PENDIENTE)->count(),
                    'rechazados' => $usuarios->where('estado_cuenta', Usuario::ESTADO_RECHAZADO)->count(),
                    'suspendidos' => $usuarios->where('estado_cuenta', Usuario::ESTADO_SUSPENDIDO)->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Método helper para formatear usuario
     */
    private function formatearUsuario($usuario)
    {
        return [
            'id_usuario' => $usuario->id_usuario,
            'numero_trabajador' => $usuario->numero_trabajador,
            'nombre_completo' => $usuario->nombre_completo,
            'email' => $usuario->email,
            'telefono' => $usuario->telefono,
            'fecha_nacimiento' => $usuario->fecha_nacimiento?->format('d/m/Y'),
            'curp' => $usuario->curp,
            'antiguedad' => $usuario->antiguedad,
            'estado_cuenta' => $usuario->estado_cuenta,
            'estado_cuenta_formateado' => $this->getEstadoCuentaFormateado($usuario->estado_cuenta),
            'clase_estado_cuenta' => $this->getClaseEstadoCuenta($usuario->estado_cuenta),
            'fecha_registro' => $usuario->created_at?->format('d/m/Y H:i'),

            // Estadísticas
            'total_inscripciones' => $usuario->total_inscripciones ?? 0,
            'inscripciones_aceptadas_count' => $usuario->inscripciones_aceptadas_count ?? 0,
            'inscripciones_pendientes_count' => $usuario->inscripciones_pendientes_count ?? 0,
            'documentos_pendientes_count' => $usuario->documentos_pendientes_count ?? 0,

            // Flags de estado
            'esta_validado' => $usuario->estaValidado(),
            'esta_suspendido' => $usuario->estaSuspendido(),
            'tiene_documentos_pendientes' => ($usuario->documentos_pendientes_count ?? 0) > 0,
            'tiene_inscripciones_pendientes' => ($usuario->inscripciones_pendientes_count ?? 0) > 0,

            // Información adicional
            'puede_inscribirse' => $usuario->estaValidado() && !$usuario->estaSuspendido(),
            'requiere_atencion' => ($usuario->documentos_pendientes_count ?? 0) > 0 ||
                ($usuario->inscripciones_pendientes_count ?? 0) > 0 ||
                $usuario->estado_cuenta === Usuario::ESTADO_PENDIENTE
        ];
    }

    /**
     * Obtener el estado de cuenta formateado
     */
    private function getEstadoCuentaFormateado($estado)
    {
        return match ($estado) {
            Usuario::ESTADO_VALIDADO => 'Validado',
            Usuario::ESTADO_PENDIENTE => 'Pendiente',
            Usuario::ESTADO_RECHAZADO => 'Rechazado',
            Usuario::ESTADO_SUSPENDIDO => 'Suspendido',
            default => $estado,
        };
    }

    /**
     * Obtener la clase CSS para el estado de cuenta
     */
    private function getClaseEstadoCuenta($estado)
    {
        return match ($estado) {
            Usuario::ESTADO_VALIDADO => 'success',
            Usuario::ESTADO_PENDIENTE => 'warning',
            Usuario::ESTADO_RECHAZADO => 'danger',
            Usuario::ESTADO_SUSPENDIDO => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Usuarios por estado específico
     */
    public function usuariosPorEstado($estado)
    {
        // Normalizar el estado recibido (primera letra mayúscula, resto minúsculas)
        $estado = ucfirst(strtolower($estado));

        $estadosValidos = [
            Usuario::ESTADO_VALIDADO,   // 'Validado'
            Usuario::ESTADO_PENDIENTE,  // 'Pendiente'
            Usuario::ESTADO_RECHAZADO,  // 'Rechazado'
            Usuario::ESTADO_SUSPENDIDO  // 'Suspendido'
        ];

        if (!in_array($estado, $estadosValidos)) {
            return response()->json([
                'error' => 'Estado no válido',
                'estado_recibido' => $estado,
                'estados_validos' => $estadosValidos
            ], 400);
        }

        $usuarios = Usuario::withCount([
            'inscripciones as total_inscripciones',
            'inscripcionesAceptadas as inscripciones_aceptadas_count',
            'documentos as total_documentos'
        ])
            ->where('estado_cuenta', $estado)
            ->orderBy('nombre_completo')
            ->get();

        return response()->json([
            'estado' => $estado,
            'estado_formateado' => $this->getEstadoCuentaFormateado($estado),
            'usuarios' => $usuarios->map(function ($usuario) {
                return $this->formatearUsuario($usuario);
            }),
            'total' => $usuarios->count(),
            'estadisticas' => [
                'con_documentos_pendientes' => $usuarios->where('tiene_documentos_pendientes', true)->count(),
                'con_inscripciones_pendientes' => $usuarios->where('tiene_inscripciones_pendientes', true)->count(),
                'pueden_inscribirse' => $usuarios->where('puede_inscribirse', true)->count()
            ]
        ]);
    }

    /**
     * Obtener inscritos actuales de una disciplina
     */
    public function obtenerInscritosActuales($idDisciplina)
    {
        $disciplina = Disciplina::findOrFail($idDisciplina);

        $inscritos = Inscripcion::with('usuario')
            ->where('id_disciplina', $idDisciplina)
            ->where('estado', Inscripcion::ESTADO_ACEPTADO)
            ->orderBy('fecha_inscripcion', 'desc')
            ->get();

        return response()->json([
            'disciplina' => $this->formatearDisciplina($disciplina),
            'inscritos' => $inscritos->map(function ($inscripcion) {
                return [
                    'id_usuario' => $inscripcion->usuario->id_usuario,
                    'nombre_usuario' => $inscripcion->usuario->nombre_completo,
                    'email_usuario' => $inscripcion->usuario->email,
                    'estado_inscripcion' => 'aceptada',
                    'estado_inscripcion_formateado' => 'Aceptada',
                    'participo' => true, // Asumiendo que los actuales participan
                    'fecha_inscripcion_original' => $inscripcion->fecha_inscripcion,
                    'clase_estado' => 'success'
                ];
            }),
            'total_inscritos' => $inscritos->count()
        ]);
    }

    /**
     * Mostrar vista de reporte detallado
     */
    public function mostrarReporteDetalle($idDisciplina)
    {
        // Verificar que la disciplina existe
        $disciplina = Disciplina::find($idDisciplina);

        if (!$disciplina) {
            abort(404, 'Disciplina no encontrada');
        }

        return view('comite.reportes-detalle', [
            'idDisciplina' => $idDisciplina,
            'disciplina' => $disciplina
        ]);
    }

    /**
     * Obtener datos detallados para el reporte de disciplina (combina actual e histórico)
     */
    public function obtenerDatosReporteDetallado($idDisciplina)
    {
        try {
            $disciplina = Disciplina::findOrFail($idDisciplina);

            // Verificar si la disciplina tiene historial
            $tieneHistorial = HistorialDisciplina::where('id_disciplina', $idDisciplina)->exists();

            $datos = [
                'disciplina' => $this->formatearDisciplina($disciplina),
                'tipo' => $disciplina->activa ? 'activa' : 'inactiva',
                'tiene_historial' => $tieneHistorial
            ];

            // Si la disciplina está activa, incluir inscritos actuales
            if ($disciplina->activa) {
                $inscritosActuales = Inscripcion::with('usuario')
                    ->where('id_disciplina', $idDisciplina)
                    ->where('estado', Inscripcion::ESTADO_ACEPTADO)
                    ->get()
                    ->map(function ($inscripcion) {
                        return [
                            'id_usuario' => $inscripcion->usuario->id_usuario,
                            'nombre_usuario' => $inscripcion->usuario->nombre_completo,
                            'email_usuario' => $inscripcion->usuario->email,
                            'estado_inscripcion' => 'aceptada',
                            'estado_inscripcion_formateado' => 'Aceptada',
                            'participo' => true,
                            'fecha_inscripcion_original' => $inscripcion->fecha_inscripcion,
                            'clase_estado' => 'success'
                        ];
                    });

                $datos['inscritos_actuales'] = $inscritosActuales;
                $datos['total_inscritos_actuales'] = $inscritosActuales->count();
            }

            // Incluir historial si existe
            if ($tieneHistorial) {
                $historial = HistorialDisciplina::where('id_disciplina', $idDisciplina)
                    ->with(['inscripcionesHistorial.usuario'])
                    ->orderBy('fecha_finalizacion', 'desc')
                    ->get()
                    ->map(function ($historial) {
                        return [
                            'id_historial' => $historial->id_historial,
                            'nombre_disciplina_historico' => $historial->nombre_disciplina,
                            'periodo' => [
                                'inicio' => $historial->periodo_inicio,
                                'fin' => $historial->periodo_fin
                            ],
                            'estado_finalizacion' => $historial->estado_finalizacion,
                            'estado_finalizacion_formateado' => $historial->getEstadoFinalizacionFormateado(),
                            'total_inscritos' => $historial->total_inscritos,
                            'tasa_participacion' => $historial->getTasaParticipacion(),
                            'fecha_finalizacion' => $historial->fecha_finalizacion,
                            'participantes' => $historial->inscripcionesHistorial->map(function ($inscripcionHistorial) {
                                return [
                                    'id_usuario' => $inscripcionHistorial->id_usuario,
                                    'nombre_usuario' => $inscripcionHistorial->nombre_usuario,
                                    'email_usuario' => $inscripcionHistorial->email_usuario,
                                    'estado_inscripcion' => $inscripcionHistorial->estado_inscripcion,
                                    'estado_inscripcion_formateado' => $inscripcionHistorial->getEstadoFormateado(),
                                    'participo' => $inscripcionHistorial->participo,
                                    'fecha_inscripcion_original' => $inscripcionHistorial->fecha_inscripcion_original,
                                    'clase_estado' => $inscripcionHistorial->getClaseEstado()
                                ];
                            })
                        ];
                    });

                $datos['historial'] = $historial;
            }

            return response()->json($datos);
        } catch (\Exception $e) {
            Log::error('Error en obtenerDatosReporteDetallado: ' . $e->getMessage(), [
                'id_disciplina' => $idDisciplina,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Error al cargar los datos del reporte',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Descargar constancia de inscripción (VERSIÓN CORREGIDA)
     */
    public function descargarConstancia($idInscripcion)
    {
        try {
            $userId = session('user_id');

            if (!$userId) {
                // Para descargas, mejor redirigir con mensaje de error
                return redirect()->back()->with('error', 'Debes iniciar sesión para descargar la constancia.');
            }

            $inscripcion = Inscripcion::where('id_inscripcion', $idInscripcion)
                ->where('id_usuario', $userId)
                ->where('estado', Inscripcion::ESTADO_ACEPTADO)
                ->with(['disciplina', 'usuario'])
                ->firstOrFail();

            // Buscar o crear constancia
            $constancia = Constancia::firstOrCreate(
                ['id_inscripcion' => $idInscripcion],
                [
                    'numero_constancia' => 'CONST-' . str_pad($inscripcion->id_inscripcion, 6, '0', STR_PAD_LEFT),
                    'codigo_verificacion' => $this->generarCodigoVerificacionUnico(),
                    'fecha_emision' => now(),
                    'fecha_vencimiento' => now()->addMonths(6),
                    'hash_seguridad' => Constancia::generarHash($inscripcion)
                ]
            );

            // Verificar que la constancia esté vigente
            if (!$constancia->estaVigente()) {
                // Si la constancia existe pero no está vigente, reactivarla con nuevas fechas
                $constancia->update([
                    'fecha_emision' => now(),
                    'fecha_vencimiento' => now()->addMonths(6),
                    'activa' => true
                ]);

                // O si prefieres no reactivar automáticamente, lanzar excepción
                // throw new \Exception('La constancia ha expirado. Por favor, genera una nueva.');
            }

            // Datos para la constancia
            $datosConstancia = [
                'inscripcion' => $inscripcion,
                'constancia' => $constancia,
                'codigo_verificacion' => $constancia->codigo_verificacion,
                'fecha_emision' => $constancia->fecha_emision->format('d/m/Y'),
                'fecha_vencimiento' => $constancia->fecha_vencimiento->format('d/m/Y'),
                'numero_constancia' => $constancia->numero_constancia
            ];

            // Generar PDF
            $pdf = Pdf::loadView('exports.constancia-pdf', $datosConstancia)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => false,
                    'dpi' => 150,
                    'defaultFont' => 'sans-serif',
                ]);

            // Registrar descarga
            $constancia->marcarComoDescargada();

            Log::info('Constancia descargada', [
                'usuario_id' => $userId,
                'inscripcion_id' => $idInscripcion,
                'constancia_id' => $constancia->id_constancia,
                'codigo_verificacion' => $constancia->codigo_verificacion
            ]);

            return $pdf->download("constancia-{$inscripcion->disciplina->nombre}-{$constancia->numero_constancia}.pdf");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Intento de descarga de constancia no encontrada', [
                'user_id' => session('user_id'),
                'inscripcion_id' => $idInscripcion
            ]);

            // Redirigir con mensaje de error en lugar de JSON
            return redirect()->back()->with('error', 'No se encontró la inscripción o no está aceptada.');
        } catch (\Exception $e) {
            Log::error('Error al generar constancia: ' . $e->getMessage(), [
                'user_id' => session('user_id'),
                'inscripcion_id' => $idInscripcion
            ]);

            // Redirigir con mensaje de error
            return redirect()->back()->with('error', 'Error al generar la constancia: ' . $e->getMessage());
        }
    }

    /**
     * API para descargar constancia (para llamadas AJAX)
     */
    public function descargarConstanciaApi($idInscripcion)
    {
        try {
            $userId = session('user_id');

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes iniciar sesión para descargar la constancia.'
                ], 401);
            }

            $inscripcion = Inscripcion::where('id_inscripcion', $idInscripcion)
                ->where('id_usuario', $userId)
                ->where('estado', Inscripcion::ESTADO_ACEPTADO)
                ->with(['disciplina', 'usuario'])
                ->firstOrFail();

            // Buscar o crear constancia
            $constancia = Constancia::firstOrCreate(
                ['id_inscripcion' => $idInscripcion],
                [
                    'numero_constancia' => 'CONST-' . str_pad($inscripcion->id_inscripcion, 6, '0', STR_PAD_LEFT),
                    'codigo_verificacion' => $this->generarCodigoVerificacionUnico(),
                    'fecha_emision' => now(),
                    'fecha_vencimiento' => now()->addMonths(6),
                    'hash_seguridad' => Constancia::generarHash($inscripcion)
                ]
            );

            // Verificar que la constancia esté vigente
            if (!$constancia->estaVigente()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La constancia ha expirado o no está activa.'
                ], 422);
            }

            // Para API, devolvemos la URL de descarga
            return response()->json([
                'success' => true,
                'download_url' => route('personal.inscripciones.descargar-constancia', ['id' => $idInscripcion]),
                'constancia' => [
                    'numero' => $constancia->numero_constancia,
                    'codigo_verificacion' => $constancia->codigo_verificacion,
                    'fecha_emision' => $constancia->fecha_emision->format('d/m/Y'),
                    'fecha_vencimiento' => $constancia->fecha_vencimiento->format('d/m/Y')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error en API de constancia: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar código de verificación único
     */
    private function generarCodigoVerificacionUnico(): string
    {
        do {
            $codigo = strtoupper(substr(md5(uniqid() . now()->timestamp), 0, 12));
        } while (Constancia::where('codigo_verificacion', $codigo)->exists());

        return $codigo;
    }

    /**
     * Verificar constancia
     */
    public function verificarConstancia($codigo)
    {
        try {
            // Normalizar código (mayúsculas, sin espacios)
            $codigo = strtoupper(trim($codigo));

            // Buscar constancia por código con todas las relaciones necesarias
            $constancia = Constancia::with([
                'inscripcion.disciplina',
                'inscripcion.usuario'
            ])
                ->where('codigo_verificacion', $codigo)
                ->first();

            if (!$constancia) {
                return view('exports.verificacion-constancia', [
                    'valida' => false,
                    'error' => 'Constancia no encontrada',
                    'codigo' => $codigo,
                    'fecha_verificacion' => now()->format('d/m/Y H:i')
                ]);
            }

            // Verificar si está vigente
            if (!$constancia->estaVigente()) {
                return view('exports.verificacion-constancia', [
                    'valida' => false,
                    'error' => 'Constancia expirada o inactiva',
                    'codigo' => $codigo,
                    'constancia' => $constancia,
                    'fecha_verificacion' => now()->format('d/m/Y H:i')
                ]);
            }

            // Verificar que tenga las relaciones necesarias
            if (!$constancia->inscripcion || !$constancia->inscripcion->disciplina || !$constancia->inscripcion->usuario) {
                return view('exports.verificacion-constancia', [
                    'valida' => false,
                    'error' => 'Datos de constancia incompletos',
                    'codigo' => $codigo,
                    'fecha_verificacion' => now()->format('d/m/Y H:i')
                ]);
            }

            // Constancia válida
            return view('exports.verificacion-constancia', [
                'valida' => true,
                'constancia' => $constancia,
                'codigo' => $codigo,
                'fecha_verificacion' => now()->format('d/m/Y H:i')
            ]);
        } catch (\Exception $e) {
            Log::error('Error al verificar constancia: ' . $e->getMessage(), [
                'codigo' => $codigo,
                'trace' => $e->getTraceAsString()
            ]);

            return view('exports.verificacion-constancia', [
                'valida' => false,
                'error' => 'Error interno al verificar la constancia',
                'codigo' => $codigo,
                'fecha_verificacion' => now()->format('d/m/Y H:i')
            ]);
        }
    }

    /**
     * Buscar constancia por código (implementación real)
     */
    private function buscarConstanciaPorCodigo($codigo)
    {
        // En una implementación completa, aquí buscarías en una tabla de constancias
        // Por ahora, vamos a extraer el ID de inscripción del código si es posible
        // y verificar si existe una inscripción aceptada

        // El código tiene formato: primeros 6 dígitos pueden ser el ID de inscripción
        $posibleId = substr($codigo, 0, 6);
        if (is_numeric($posibleId)) {
            $inscripcion = Inscripcion::where('id_inscripcion', $posibleId)
                ->where('estado', Inscripcion::ESTADO_ACEPTADO)
                ->with(['disciplina', 'usuario'])
                ->first();

            if ($inscripcion) {
                return [
                    'inscripcion' => $inscripcion,
                    'numero_constancia' => 'CONST-' . str_pad($inscripcion->id_inscripcion, 6, '0', STR_PAD_LEFT),
                    'fecha_emision' => $inscripcion->fecha_validacion ? $inscripcion->fecha_validacion->format('d/m/Y') : now()->format('d/m/Y')
                ];
            }
        }

        return null;
    }

    /**
     * API para verificación de constancias
     */
    public function apiVerificarConstancia($codigo)
    {
        try {
            // Normalizar código
            $codigo = strtoupper(trim($codigo));

            // Buscar constancia directamente
            $constancia = Constancia::with([
                'inscripcion.disciplina',
                'inscripcion.usuario'
            ])
                ->where('codigo_verificacion', $codigo)
                ->first();

            if (!$constancia) {
                return response()->json([
                    'success' => false,
                    'valida' => false,
                    'error' => 'Constancia no encontrada',
                    'codigo' => $codigo
                ], 404);
            }

            // Verificar vigencia
            if (!$constancia->estaVigente()) {
                return response()->json([
                    'success' => false,
                    'valida' => false,
                    'error' => 'Constancia expirada o inactiva',
                    'constancia' => [
                        'numero_constancia' => $constancia->numero_constancia,
                        'fecha_vencimiento' => $constancia->fecha_vencimiento->format('d/m/Y'),
                        'activa' => $constancia->activa
                    ]
                ], 422);
            }

            // Verificar relaciones
            if (!$constancia->inscripcion || !$constancia->inscripcion->disciplina || !$constancia->inscripcion->usuario) {
                return response()->json([
                    'success' => false,
                    'valida' => false,
                    'error' => 'Datos de constancia incompletos'
                ], 422);
            }

            // Constancia válida
            return response()->json([
                'success' => true,
                'valida' => true,
                'constancia' => [
                    'numero_constancia' => $constancia->numero_constancia,
                    'codigo_verificacion' => $constancia->codigo_verificacion,
                    'fecha_emision' => $constancia->fecha_emision->format('d/m/Y'),
                    'fecha_vencimiento' => $constancia->fecha_vencimiento->format('d/m/Y'),
                    'descargas_realizadas' => $constancia->descargas_realizadas,
                    'activa' => $constancia->activa
                ],
                'inscripcion' => [
                    'participante' => $constancia->inscripcion->usuario->nombre_completo,
                    'disciplina' => $constancia->inscripcion->disciplina->nombre,
                    'categoria' => $constancia->inscripcion->disciplina->getCategoriaFormateada(),
                    'genero' => $constancia->inscripcion->disciplina->getGeneroFormateado()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error en API de verificación: ' . $e->getMessage(), [
                'codigo' => $codigo
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error en la verificación: ' . $e->getMessage()
            ], 500);
        }
    }
}
