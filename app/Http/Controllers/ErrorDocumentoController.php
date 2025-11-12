<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Error;
use App\Models\Documento;
use App\Models\ValidacionInformacionPersonal;
use App\Models\Usuario;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ErrorDocumentoController extends Controller
{
    /**
     * Registrar error en documento
     */
    public function registrarError(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'id_documento' => 'required|exists:documentos,id_documento',
                'descripcion_error' => 'required|string|min:10|max:500'
            ]);

            $documento = Documento::findOrFail($request->id_documento);

            // Crear registro de error
            $error = Error::create([
                'id_usuario' => $documento->id_usuario,
                'id_documento' => $request->id_documento,
                'tipo_error' => 'documento',
                'descripcion_error' => $request->descripcion_error,
                'corregido' => false
            ]);

            // Actualizar estado del documento a rechazado
            $documento->estado = Documento::ESTADO_RECHAZADO;
            $documento->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Error registrado y documento rechazado correctamente',
                'error' => $error
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aprobar información personal de un usuario
     */
    public function aprobarInformacionPersonal(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'id_usuario' => 'required|exists:usuarios,id_usuario'
            ]);

            $usuario = Usuario::findOrFail($request->id_usuario);
            $validacionActual = $usuario->validacionInformacionPersonalActual();

            if (!$validacionActual) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró validación de información personal para este usuario'
                ], 404);
            }

            // Aprobar la información personal
            $validacionActual->marcarComoAceptada();

            // Verificar si el usuario puede ser validado completamente
            $this->verificarValidacionCompleta($usuario);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Información personal aprobada correctamente',
                'validacion' => $validacionActual
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al aprobar la información personal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechazar información personal de un usuario
     */
    public function rechazarInformacionPersonal(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'id_usuario' => 'required|exists:usuarios,id_usuario',
                'motivo_rechazo' => 'required|string|min:10|max:500'
            ]);

            $usuario = Usuario::findOrFail($request->id_usuario);
            $validacionActual = $usuario->validacionInformacionPersonalActual();

            if (!$validacionActual) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró validación de información personal para este usuario'
                ], 404);
            }

            // Rechazar la información personal
            $validacionActual->marcarComoRechazada($request->motivo_rechazo);

            // Actualizar estado general del usuario a Rechazado
            $usuario->estado_cuenta = Usuario::ESTADO_RECHAZADO;
            $usuario->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Información personal rechazada correctamente',
                'validacion' => $validacionActual
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al rechazar la información personal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar si el usuario puede ser validado completamente
     * (Información personal aprobada + todos los documentos aprobados)
     */
    private function verificarValidacionCompleta(Usuario $usuario)
    {
        // Verificar si la información personal está aprobada
        $infoPersonalAprobada = $usuario->tieneInformacionPersonalAceptada();

        // Verificar si todos los documentos están aprobados
        $todosDocumentosAprobados = !$usuario->tieneDocumentosPendientes() &&
            !$usuario->documentos()->where('estado', Documento::ESTADO_RECHAZADO)->exists();

        if ($infoPersonalAprobada && $todosDocumentosAprobados) {
            // Actualizar estado general del usuario a Validado
            $usuario->estado_cuenta = Usuario::ESTADO_VALIDADO;
            $usuario->save();
        }
    }

    /**
     * Obtener el estado de validación de información personal de un usuario
     */
    public function obtenerEstadoValidacionInformacion($idUsuario)
    {
        try {
            $usuario = Usuario::findOrFail($idUsuario);
            $validacionActual = $usuario->validacionInformacionPersonalActual();

            return response()->json([
                'success' => true,
                'estado' => $validacionActual ? $validacionActual->estado : 'No encontrado',
                'motivo_rechazo' => $validacionActual ? $validacionActual->motivo_rechazo : null,
                'fecha_validacion' => $validacionActual ? $validacionActual->fecha_validacion : null,
                'validacion' => $validacionActual
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el estado de validación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nueva validación pendiente (para cuando el usuario corrige su información)
     */
    public function crearNuevaValidacionPendiente(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'id_usuario' => 'required|exists:usuarios,id_usuario'
            ]);

            $usuario = Usuario::findOrFail($request->id_usuario);

            // Crear nueva validación pendiente
            $nuevaValidacion = ValidacionInformacionPersonal::crearValidacionPendiente($usuario->id_usuario);

            // Actualizar estado general del usuario a Pendiente
            $usuario->estado_cuenta = Usuario::ESTADO_PENDIENTE;
            $usuario->save();

            // Notificar al comité
            Notificacion::create([
                'tipo' => 'validacion',
                'destinatarios' => 'comite',
                'asunto' => 'Nueva validación de información pendiente',
                'mensaje' => 'El usuario ' . $usuario->nombre_completo . ' ha enviado correcciones en su información personal. Requiere nueva validación.',
                'leida' => false,
                'created_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Nueva validación pendiente creada correctamente',
                'validacion' => $nuevaValidacion
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear nueva validación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marcar error como corregido
     */
    public function marcarCorregido($id)
    {
        try {
            $error = Error::findOrFail($id);

            $error->corregido = true;
            $error->save();

            return response()->json([
                'success' => true,
                'message' => 'Error marcado como corregido'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar como corregido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener errores de un usuario
     */
    public function obtenerErroresUsuario($idUsuario)
    {
        try {
            $errores = Error::where('id_usuario', $idUsuario)
                ->with(['documento'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'errores' => $errores
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los errores: ' . $e->getMessage()
            ], 500);
        }
    }
}
