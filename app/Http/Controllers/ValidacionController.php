<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Documento;
use App\Models\Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidacionController extends Controller
{
    /**
     * Mostrar la vista de validación de documentos
     */
    public function index()
    {
        return view('comite.validacion');
    }

    /**
     * Obtener usuarios para AJAX
     */
    public function obtenerUsuarios(Request $request)
    {
        try {
            $query = Usuario::with(['documentos.errores', 'validacionesInformacionPersonal']);

            // Filtro por estado
            if ($request->has('estado') && $request->estado !== '') {
                $query->where('estado_cuenta', $request->estado);
            }

            // Búsqueda por nombre
            if ($request->has('busqueda') && $request->busqueda !== '') {
                $query->where('nombre_completo', 'like', '%' . $request->busqueda . '%');
            }

            $usuarios = $query->orderBy('created_at', 'desc')->get()
                ->map(function ($usuario) {
                    // Verificar si tiene errores corregidos para mostrar el botón de reconsiderar
                    $tieneErroresCorregidos = $usuario->errores()
                        ->where('corregido', true)
                        ->exists();

                    // Verificar si tiene información personal pendiente después de un rechazo
                    $tieneInfoPersonalPendiente = $usuario->tieneInformacionPersonalPendiente() &&
                        $usuario->tieneInformacionPersonalRechazadaAnteriormente();

                    // Obtener información de la fotografía
                    $fotografia = $usuario->documentos()
                        ->where('tipo_documento', Documento::TIPO_FOTOGRAFIA)
                        ->where('estado', Documento::ESTADO_APROBADO)
                        ->first();

                    // Si no hay aprobada, buscar cualquier fotografía
                    if (!$fotografia) {
                        $fotografia = $usuario->documentos()
                            ->where('tipo_documento', Documento::TIPO_FOTOGRAFIA)
                            ->first();
                    }

                    return [
                        'id_usuario' => $usuario->id_usuario,
                        'numero_trabajador' => $usuario->numero_trabajador,
                        'nombre_completo' => $usuario->nombre_completo,
                        'email' => $usuario->email,
                        'telefono' => $usuario->telefono,
                        'fecha_nacimiento' => $usuario->fecha_nacimiento,
                        'curp' => $usuario->curp,
                        'antiguedad' => $usuario->antiguedad,
                        'estado_cuenta' => $usuario->estado_cuenta,
                        'created_at' => $usuario->created_at,
                        'tiene_errores_corregidos' => $tieneErroresCorregidos,
                        'tiene_info_personal_pendiente' => $tieneInfoPersonalPendiente,
                        'documentos_count' => $usuario->documentos->count(),
                        'documentos_pendientes' => $usuario->documentos->where('estado', Documento::ESTADO_PENDIENTE)->count(),
                        'documentos_aprobados' => $usuario->documentos->where('estado', Documento::ESTADO_APROBADO)->count(),
                        'documentos_rechazados' => $usuario->documentos->where('estado', Documento::ESTADO_RECHAZADO)->count(),
                        'tiene_foto' => !is_null($fotografia),
                        'id_documento_foto' => $fotografia ? $fotografia->id_documento : null
                    ];
                });

            return response()->json([
                'success' => true,
                'usuarios' => $usuarios
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los usuarios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar la vista de validación de un aspirante específico
     */
    public function show($id)
    {
        try {
            $usuario = Usuario::with(['documentos.errores'])->findOrFail($id);
            $documentos = $usuario->documentos;

            return view('comite.validacion-aspirante', compact('usuario', 'documentos'));
        } catch (\Exception $e) {
            return redirect()->route('comite.validacion')
                ->with('error', 'Usuario no encontrado');
        }
    }

    /**
     * Aceptar usuario
     */
    public function aceptarUsuario($id)
    {
        try {
            DB::beginTransaction();

            $usuario = Usuario::findOrFail($id);

            // Verificar que todos los documentos estén aprobados
            $documentosPendientes = $usuario->documentos()
                ->where('estado', Documento::ESTADO_PENDIENTE)
                ->count();

            if ($documentosPendientes > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede validar el usuario. Hay documentos pendientes de revisión.'
                ], 400);
            }

            // Verificar que la información personal esté aceptada
            if (!$usuario->tieneInformacionPersonalAceptada()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede validar el usuario. La información personal no ha sido aceptada.'
                ], 400);
            }

            // Actualizar estado del usuario
            $usuario->estado_cuenta = Usuario::ESTADO_VALIDADO;
            $usuario->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario validado correctamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al validar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechazar usuario
     */
    public function rechazarUsuario(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $usuario = Usuario::findOrFail($id);

            // Validar que se proporcione un motivo
            $request->validate([
                'motivo' => 'required|string|min:10|max:500'
            ]);

            // Actualizar estado del usuario
            $usuario->estado_cuenta = Usuario::ESTADO_RECHAZADO;
            $usuario->save();

            // Crear registro de error para el usuario
            Error::create([
                'id_usuario' => $usuario->id_usuario,
                'tipo_error' => 'usuario',
                'descripcion_error' => $request->motivo,
                'corregido' => false
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario rechazado correctamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al rechazar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reconsiderar usuario
     */
    public function reconsiderarUsuario($id)
    {
        try {
            DB::beginTransaction();

            $usuario = Usuario::findOrFail($id);

            // Solo permitir reconsiderar si el usuario está rechazado y tiene errores corregidos
            if ($usuario->estado_cuenta !== Usuario::ESTADO_RECHAZADO) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden reconsiderar usuarios rechazados'
                ], 400);
            }

            // Verificar que tenga errores corregidos
            $tieneErroresCorregidos = $usuario->errores()
                ->where('corregido', true)
                ->exists();

            if (!$tieneErroresCorregidos) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede reconsiderar: el usuario no tiene errores corregidos'
                ], 400);
            }

            // Cambiar estado a pendiente
            $usuario->estado_cuenta = Usuario::ESTADO_PENDIENTE;
            $usuario->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario reconsiderado correctamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al reconsiderar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aceptar documento
     */
    public function aceptarDocumento($id)
    {
        try {
            DB::beginTransaction();

            $documento = Documento::findOrFail($id);

            // Actualizar estado del documento
            $documento->estado = Documento::ESTADO_APROBADO;
            $documento->save();

            // Eliminar errores previos de este documento si existen
            Error::where('id_documento', $id)->delete();

            // Verificar si todos los documentos del usuario están aprobados
            $this->verificarEstadoUsuario($documento->id_usuario);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Documento aceptado correctamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al aceptar el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar y actualizar estado del usuario basado en sus documentos e información personal
     */
    private function verificarEstadoUsuario($idUsuario)
    {
        try {
            $usuario = Usuario::with('documentos')->find($idUsuario);

            if (!$usuario) return;

            $documentos = $usuario->documentos;
            $totalDocumentos = $documentos->count();
            $documentosAprobados = $documentos->where('estado', Documento::ESTADO_APROBADO)->count();
            $documentosRechazados = $documentos->where('estado', Documento::ESTADO_RECHAZADO)->count();

            // Verificar estado de la información personal usando tu método existente
            $infoPersonalAprobada = $usuario->tieneInformacionPersonalAceptada();

            // Si hay al menos un documento rechazado, el usuario debe estar rechazado
            if ($documentosRechazados > 0) {
                $usuario->estado_cuenta = Usuario::ESTADO_RECHAZADO;
                $usuario->save();
                return;
            }

            // Si todos los documentos están aprobados Y la información personal está aceptada, marcar al usuario como validado
            if (
                $totalDocumentos > 0 &&
                $documentosAprobados === $totalDocumentos &&
                $infoPersonalAprobada
            ) {
                $usuario->estado_cuenta = Usuario::ESTADO_VALIDADO;
                $usuario->save();
            }
        } catch (\Exception $e) {
            // Log del error si es necesario
        }
    }

    /**
     * Rechazar documento
     */
    public function rechazarDocumento(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $documento = Documento::findOrFail($id);
            $usuario = $documento->usuario;

            // Validar que se proporcione un motivo
            $request->validate([
                'motivo' => 'required|string|min:10|max:500'
            ]);

            // Actualizar estado del documento
            $documento->estado = Documento::ESTADO_RECHAZADO;
            $documento->save();

            // Actualizar estado del usuario a Rechazado
            $usuario->estado_cuenta = Usuario::ESTADO_RECHAZADO;
            $usuario->save();

            // Crear registro de error para el documento
            Error::create([
                'id_usuario' => $usuario->id_usuario,
                'id_documento' => $documento->id_documento,
                'tipo_error' => 'Documento',
                'descripcion_error' => $request->motivo,
                'corregido' => false
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Documento rechazado correctamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al rechazar el documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ver documento en el navegador
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
}
