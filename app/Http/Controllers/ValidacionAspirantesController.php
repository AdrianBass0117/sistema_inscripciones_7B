<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Usuario;
use App\Models\Disciplina;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidacionAspirantesController extends Controller
{
    /**
     * Mostrar el listado de aspirantes con sus inscripciones
     */
    public function index()
    {
        try {
            // Obtener todas las inscripciones EXCLUYENDO cancelados
            $inscripciones = Inscripcion::with(['usuario', 'disciplina'])
                ->where('estado', '!=', Inscripcion::ESTADO_CANCELADO)
                ->orderBy('created_at', 'desc')
                ->get();

            // Obtener disciplinas únicas para el filtro
            $disciplinas = Disciplina::activas()->get();

            // Contadores por estado (sin cancelados)
            $contadores = [
                'total' => $inscripciones->count(),
                'pendientes' => $inscripciones->where('estado', Inscripcion::ESTADO_PENDIENTE)->count(),
                'aceptados' => $inscripciones->where('estado', Inscripcion::ESTADO_ACEPTADO)->count(),
                'rechazados' => $inscripciones->where('estado', Inscripcion::ESTADO_RECHAZADO)->count(),
                'cancelados' => 0,
            ];

            return view('comite.aspirantes', compact('inscripciones', 'disciplinas', 'contadores'));
        } catch (\Exception $e) {
            Log::error('Error en ValidacionAspirantesController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar los aspirantes.');
        }
    }

    /**
     * Mostrar perfil completo del aspirante
     */
    public function showCuentaAspirante($idUsuario, Request $request)
    {
        try {
            $usuario = Usuario::with(['documentos', 'inscripciones.disciplina'])->findOrFail($idUsuario);

            // Si se pasa un ID de inscripción específico, usar esa
            if ($request->has('inscripcion')) {
                $inscripcion = Inscripcion::with('disciplina')
                    ->where('id_inscripcion', $request->inscripcion)
                    ->where('id_usuario', $idUsuario)
                    ->first();
            }

            // Si no hay inscripción específica o no se encontró, buscar pendientes
            if (!isset($inscripcion) || !$inscripcion) {
                $inscripcion = $usuario->inscripciones()
                    ->with('disciplina')
                    ->where('estado', Inscripcion::ESTADO_PENDIENTE)
                    ->orderBy('created_at', 'desc')
                    ->first();
            }

            // Si aún no hay inscripción, mostrar cualquier inscripción
            if (!$inscripcion) {
                $inscripcion = $usuario->inscripciones()
                    ->with('disciplina')
                    ->orderBy('created_at', 'desc')
                    ->first();
            }

            if (!$inscripcion) {
                return redirect()->route('comite.aspirantes')->with('error', 'El usuario no tiene inscripciones activas.');
            }

            return view('comite.aspirantes-cuenta', compact('usuario', 'inscripcion'));
        } catch (\Exception $e) {
            Log::error('Error al cargar cuenta de aspirante: ' . $e->getMessage());
            return redirect()->route('comite.aspirantes')->with('error', 'Error al cargar el perfil del aspirante.');
        }
    }

    /**
     * Aceptar una inscripción
     */
    public function aceptarInscripcion($idInscripcion)
    {
        try {
            $inscripcion = Inscripcion::with(['usuario', 'disciplina'])->findOrFail($idInscripcion);

            // Verificar que la inscripción esté pendiente
            if (!$inscripcion->estaPendiente()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden aceptar inscripciones pendientes.'
                ], 422);
            }

            // Verificar si la disciplina aún tiene cupo disponible
            $disciplina = $inscripcion->disciplina;
            if (!$disciplina->tieneCupoDisponible()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La disciplina no tiene cupos disponibles.'
                ], 422);
            }

            // Aceptar la inscripción
            $inscripcion->marcarComoAceptada();

            Log::info('Inscripción aceptada', [
                'inscripcion_id' => $inscripcion->id_inscripcion,
                'usuario_id' => $inscripcion->id_usuario,
                'disciplina_id' => $inscripcion->id_disciplina
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inscripción aceptada correctamente.',
                'nuevo_estado' => $inscripcion->estado,
                'estado_formateado' => $inscripcion->getEstadoFormateado()
            ]);
        } catch (\Exception $e) {
            Log::error('Error al aceptar inscripción: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al aceptar la inscripción: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechazar una inscripción
     */
    public function rechazarInscripcion($idInscripcion)
    {
        try {
            $inscripcion = Inscripcion::with(['usuario', 'disciplina'])->findOrFail($idInscripcion);

            // Verificar que la inscripción esté pendiente
            if (!$inscripcion->estaPendiente()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden rechazar inscripciones pendientes.'
                ], 422);
            }

            // Rechazar la inscripción
            $inscripcion->marcarComoRechazada();

            Log::info('Inscripción rechazada', [
                'inscripcion_id' => $inscripcion->id_inscripcion,
                'usuario_id' => $inscripcion->id_usuario,
                'disciplina_id' => $inscripcion->id_disciplina
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inscripción rechazada correctamente.',
                'nuevo_estado' => $inscripcion->estado,
                'estado_formateado' => $inscripcion->getEstadoFormateado()
            ]);
        } catch (\Exception $e) {
            Log::error('Error al rechazar inscripción: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al rechazar la inscripción: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reconsiderar una inscripción (volver a pendiente)
     */
    public function reconsiderarInscripcion($idInscripcion)
    {
        try {
            $inscripcion = Inscripcion::with(['usuario', 'disciplina'])->findOrFail($idInscripcion);

            // Solo se puede reconsiderar inscripciones aceptadas o rechazadas
            if (!$inscripcion->estaValidada()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden reconsiderar inscripciones que han sido aceptadas o rechazadas.'
                ], 422);
            }

            // Volver a estado pendiente
            $inscripcion->update([
                'estado' => Inscripcion::ESTADO_PENDIENTE,
                'fecha_validacion' => null
            ]);

            Log::info('Inscripción reconsiderada', [
                'inscripcion_id' => $inscripcion->id_inscripcion,
                'usuario_id' => $inscripcion->id_usuario,
                'disciplina_id' => $inscripcion->id_disciplina
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inscripción puesta en reconsideración correctamente.',
                'nuevo_estado' => $inscripcion->estado,
                'estado_formateado' => $inscripcion->getEstadoFormateado()
            ]);
        } catch (\Exception $e) {
            Log::error('Error al reconsiderar inscripción: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al reconsiderar la inscripción: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ver documento
     */
    public function verDocumento($id)
    {
        try {
            $documento = Documento::findOrFail($id);

            // Determinar la carpeta según el tipo de documento
            $carpeta = match ($documento->tipo_documento) {
                'Constancia Laboral' => 'constancias_laborales',
                'CFDI/Recibo' => 'cfdi_recibos',
                'Fotografía' => 'fotografias',
                default => 'documentos'
            };

            // Obtener solo el nombre del archivo (sin la carpeta)
            $nombreArchivo = basename($documento->url_archivo);

            // Construir la ruta correcta
            $rutaArchivo = storage_path("app/public/{$carpeta}/{$nombreArchivo}");

            // Verificar si el archivo existe
            if (!file_exists($rutaArchivo)) {
                // Si no existe, intentar con la ruta almacenada directamente
                $rutaArchivo = storage_path("app/public/{$documento->url_archivo}");

                if (!file_exists($rutaArchivo)) {
                    abort(404, 'Archivo no encontrado');
                }
            }

            // Determinar el tipo de contenido
            $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

            $mimeTypes = [
                'pdf' => 'application/pdf',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif'
            ];

            $contentType = $mimeTypes[$extension] ?? 'application/octet-stream';

            return response()->file($rutaArchivo, [
                'Content-Type' => $contentType,
            ]);
        } catch (\Exception $e) {
            abort(404, 'Error al cargar el documento: ' . $e->getMessage());
        }
    }

    /**
     * Descargar documento
     */
    public function descargarDocumento($id)
    {
        try {
            $documento = Documento::findOrFail($id);

            // Determinar la carpeta según el tipo de documento
            $carpeta = match ($documento->tipo_documento) {
                'Constancia Laboral' => 'constancias_laborales',
                'CFDI/Recibo' => 'cfdi_recibos',
                'Fotografía' => 'fotografias',
                default => 'documentos'
            };

            // Obtener solo el nombre del archivo (sin la carpeta)
            $nombreArchivo = basename($documento->url_archivo);

            // Construir la ruta correcta
            $rutaArchivo = storage_path("app/public/{$carpeta}/{$nombreArchivo}");

            // Verificar si el archivo existe
            if (!file_exists($rutaArchivo)) {
                // Si no existe, intentar con la ruta almacenada directamente
                $rutaArchivo = storage_path("app/public/{$documento->url_archivo}");

                if (!file_exists($rutaArchivo)) {
                    abort(404, 'Archivo no encontrado');
                }
            }

            // Crear un nombre amigable para la descarga
            $nombreDescarga = str_replace(' ', '_', $documento->tipo_documento) . '_' . $nombreArchivo;

            return response()->download($rutaArchivo, $nombreDescarga);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al descargar el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas para filtros
     */
    public function obtenerEstadisticas()
    {
        try {
            $estadisticas = [
                'total' => Inscripcion::count(),
                'pendientes' => Inscripcion::where('estado', Inscripcion::ESTADO_PENDIENTE)->count(),
                'aceptados' => Inscripcion::where('estado', Inscripcion::ESTADO_ACEPTADO)->count(),
                'rechazados' => Inscripcion::where('estado', Inscripcion::ESTADO_RECHAZADO)->count(),
                'cancelados' => Inscripcion::where('estado', Inscripcion::ESTADO_CANCELADO)->count(),
            ];

            return response()->json([
                'success' => true,
                'estadisticas' => $estadisticas
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar las estadísticas.'
            ], 500);
        }
    }


}
