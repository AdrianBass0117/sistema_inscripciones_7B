<?php

namespace App\Http\Controllers;

use App\Models\ValidacionInformacionPersonal;
use App\Models\Usuario;
use App\Models\Comite;
use App\Models\Supervisor;
use App\Models\Documento;
use App\Models\Notificacion;
use App\Models\Error;
use App\Models\Tarjeta;
use App\Models\BlockchainBlock;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CuentaUsuarioController extends Controller
{
    public function index()
    {
        // Obtener usuario autenticado desde la sesión
        $userType = session('user_type');
        $userId = session('user_id');

        if ($userType !== 'usuario' && $userType !== 'aspirante') {
            return redirect('/')->with('error', 'Acceso no autorizado');
        }

        $usuario = Usuario::findOrFail($userId);

        // Obtener documentos del usuario
        $documentos = $usuario->documentos()->with('errores')->get();

        // Separar documentos por estado
        $documentosPendientes = $documentos->where('estado', Documento::ESTADO_PENDIENTE);
        $documentosRechazados = $documentos->where('estado', Documento::ESTADO_RECHAZADO);
        $documentosAprobados = $documentos->where('estado', Documento::ESTADO_APROBADO);

        return view('aspirante.cuenta', compact(
            'usuario',
            'documentosPendientes',
            'documentosRechazados',
            'documentosAprobados'
        ));
    }

    /**
     * Mostrar vista de configuración de cuenta personal (solo datos básicos)
     */
    public function indexPersonal()
    {
        // Obtener usuario autenticado desde la sesión
        $userType = session('user_type');
        $userId = session('user_id');

        if ($userType !== 'usuario' && $userType !== 'aspirante') {
            return redirect('/')->with('error', 'Acceso no autorizado');
        }

        // Obtener solo los datos básicos del usuario (email y estado)
        $usuario = Usuario::select('id_usuario', 'email', 'estado_cuenta', 'updated_at')
            ->findOrFail($userId);

        return view('participante.cuenta', compact('usuario'));
    }

    /**
     * Actualizar correo electrónico del usuario
     */
    public function actualizarEmail(Request $request)
    {
        $userId = session('user_id');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $email = $request->email;

            // Verificar que el email no exista en ninguna tabla
            if ($this->emailExisteEnOtrasTablas($email, $userId)) {
                return back()->with('error', 'El correo electrónico ya está en uso por otro usuario.')->withInput();
            }

            $usuario = Usuario::findOrFail($userId);
            $usuario->update([
                'email' => $email,
                'updated_at' => now(),
            ]);

            return back()->with('success', 'Correo electrónico actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el correo electrónico: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar contraseña del usuario
     */
    public function actualizarPassword(Request $request)
    {
        $userId = session('user_id');

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
        ], [
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un carácter especial.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $usuario = Usuario::findOrFail($userId);

            // Verificar contraseña actual
            if (!Hash::check($request->current_password, $usuario->password_hash)) {
                return back()->with('error', 'La contraseña actual es incorrecta.')->withInput();
            }

            // Actualizar contraseña
            $usuario->update([
                'password_hash' => Hash::make($request->password),
                'updated_at' => now(),
            ]);

            return back()->with('success', 'Contraseña actualizada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la contraseña: ' . $e->getMessage());
        }
    }

    /**
     * Verificar si el email existe en otras tablas (Comite, Supervisor)
     */
    private function emailExisteEnOtrasTablas($email, $currentUserId)
    {
        // Verificar en tabla Comite
        $existeEnComite = Comite::where('email', $email)->exists();

        // Verificar en tabla Supervisor
        $existeEnSupervisor = Supervisor::where('email', $email)->exists();

        // Verificar en tabla Usuario (excluyendo el usuario actual)
        $existeEnUsuario = Usuario::where('email', $email)
            ->where('id_usuario', '!=', $currentUserId)
            ->exists();

        return $existeEnComite || $existeEnSupervisor || $existeEnUsuario;
    }

    public function actualizarInformacionPersonal(Request $request)
    {
        $userId = session('user_id');

        $request->validate([
            'telefono' => 'nullable|string|max:15',
            'email' => 'required|email|unique:usuarios,email,' . $userId . ',id_usuario',
        ]);

        $usuario = Usuario::findOrFail($userId);
        $usuario->update($request->only(['telefono', 'email']));

        return back()->with('success', 'Información personal actualizada correctamente');
    }

    public function subirDocumentoCorregido(Request $request, $idDocumento)
    {
        $userId = session('user_id');

        $request->validate([
            'documento' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB máximo
        ]);

        try {
            $documento = Documento::where('id_documento', $idDocumento)
                ->where('id_usuario', $userId)
                ->firstOrFail();

            // Verificar que el documento esté rechazado
            if ($documento->estado !== Documento::ESTADO_RECHAZADO) {
                return back()->with('error', 'Este documento no requiere corrección');
            }

            // Determinar la carpeta según el tipo de documento
            $carpeta = $this->getCarpetaPorTipoDocumento($documento->tipo_documento);

            // Eliminar archivo anterior si existe
            if ($documento->url_archivo && Storage::disk('public')->exists($documento->url_archivo)) {
                Storage::disk('public')->delete($documento->url_archivo);
            }

            // Guardar nuevo archivo
            $archivo = $request->file('documento');
            $nombreArchivo = $this->generarNombreArchivo($userId, $idDocumento, $documento->tipo_documento, $archivo);
            $rutaArchivo = $archivo->storeAs($carpeta, $nombreArchivo, 'public');

            // **MARCAR ERRORES COMO CORREGIDOS - USANDO 1 EN VEZ DE true**
            $erroresActualizados = Error::where('id_documento', $idDocumento)
                ->where('id_usuario', $userId)
                ->where('corregido', 0) // Buscar donde corregido = 0
                ->update([
                    'corregido' => 1, // Usar 1 en lugar de true
                    'updated_at' => now()
                ]);

            // Actualizar documento
            $documento->update([
                'url_archivo' => $rutaArchivo,
                'estado' => Documento::ESTADO_PENDIENTE,
                'updated_at' => now(),
            ]);

            // Obtener información del usuario para la notificación
            $usuario = Usuario::find($userId);

            // Crear notificación para el comité
            Notificacion::create([
                'tipo' => 'correccion',
                'destinatarios' => 'comite',
                'asunto' => "El usuario {$usuario->nombre_completo} ha corregido sus documentos",
                'mensaje' => "El usuario {$usuario->nombre_completo} ha subido una corrección del documento {$documento->tipo_documento}. Revisa los documentos corregidos lo más pronto posible.",
                'leida' => false,
                'created_at' => now(),
            ]);

            return back()->with('success', 'Documento corregido enviado para revisión. El estado ha cambiado a "Pendiente".');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Documento no encontrado.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir el documento: ' . $e->getMessage());
        }
    }

    public function actualizarDocumentos(Request $request)
    {
        $userId = session('user_id');

        try {
            DB::beginTransaction();

            $usuario = Usuario::findOrFail($userId);
            $documentosActualizados = [];

            $tiposDocumentos = [
                Documento::TIPO_CONSTANCIA_LABORAL => [
                    'archivo' => $request->file('constancia_laboral_corregida'),
                    'carpeta' => 'constancias_laborales',
                ],
                Documento::TIPO_CFDI_RECIBO => [
                    'archivo' => $request->file('cfdi_corregido'),
                    'carpeta' => 'cfdi_recibos',
                ],
                Documento::TIPO_FOTOGRAFIA => [
                    'archivo' => $request->file('fotografia_corregida'),
                    'carpeta' => 'fotografias',
                ],
            ];

            foreach ($tiposDocumentos as $tipo => $config) {
                $archivo = $config['archivo'];

                if ($archivo && $archivo->isValid()) {
                    $documentoExistente = Documento::where('id_usuario', $userId)
                        ->where('tipo_documento', $tipo)
                        ->first();

                    if ($documentoExistente) {
                        // Eliminar archivo anterior
                        if ($documentoExistente->url_archivo && Storage::disk('public')->exists($documentoExistente->url_archivo)) {
                            Storage::disk('public')->delete($documentoExistente->url_archivo);
                        }

                        // Guardar nuevo archivo
                        $nombreArchivo = $this->generarNombreArchivo(
                            $userId,
                            $documentoExistente->id_documento,
                            $tipo,
                            $archivo
                        );

                        $rutaArchivo = $archivo->storeAs($config['carpeta'], $nombreArchivo, 'public');

                        // Actualizar documento
                        $documentoExistente->update([
                            'url_archivo' => $rutaArchivo,
                            'estado' => Documento::ESTADO_PENDIENTE,
                            'updated_at' => now(),
                        ]);

                        // **MARCAR ERRORES COMO CORREGIDOS - USANDO 1 EN VEZ DE true**
                        Error::where('id_documento', $documentoExistente->id_documento)
                            ->where('id_usuario', $userId)
                            ->where('corregido', 0) // Buscar donde corregido = 0
                            ->update([
                                'corregido' => 1, // Usar 1 en lugar de true
                                'updated_at' => now()
                            ]);

                        $documentosActualizados[] = $tipo;
                    }
                }
            }

            // Crear notificación solo si se actualizaron documentos
            if (count($documentosActualizados) > 0) {
                $tiposActualizados = implode(', ', $documentosActualizados);

                Notificacion::create([
                    'tipo' => 'correccion',
                    'destinatarios' => 'comite',
                    'asunto' => "El usuario {$usuario->nombre_completo} ha corregido sus documentos",
                    'mensaje' => "El usuario {$usuario->nombre_completo} ha subido correcciones para los siguientes documentos: {$tiposActualizados}. Revisa los documentos corregidos lo más pronto posible.",
                    'leida' => false,
                    'created_at' => now(),
                ]);
            }

            DB::commit();

            if (count($documentosActualizados) > 0) {
                return back()->with(
                    'success',
                    count($documentosActualizados) . ' documento(s) actualizado(s) correctamente. Errores marcados como corregidos.'
                );
            } else {
                return back()->with('info', 'No se actualizaron documentos.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar los documentos: ' . $e->getMessage());
        }
    }

    public function verificarErroresDocumento($idDocumento, $userId)
    {
        $errores = Error::where('id_documento', $idDocumento)
            ->where('id_usuario', $userId)
            ->get();

        return $errores;
    }

    /**
     * Genera nombre único para el archivo
     */
    private function generarNombreArchivo($userId, $documentoId, $tipoDocumento, $archivo)
    {
        $nombreBase = $this->getNombreBasePorTipo($tipoDocumento);
        $extension = $archivo->getClientOriginalExtension();

        return $userId . '_' . $documentoId . '_' . $nombreBase . '_' . time() . '.' . $extension;
    }

    /**
     * Obtiene la carpeta de almacenamiento según el tipo de documento
     */
    private function getCarpetaPorTipoDocumento($tipoDocumento)
    {
        return match ($tipoDocumento) {
            Documento::TIPO_CONSTANCIA_LABORAL => 'constancias_laborales',
            Documento::TIPO_CFDI_RECIBO => 'cfdi_recibos',
            Documento::TIPO_FOTOGRAFIA => 'fotografias',
            default => 'documentos_usuarios'
        };
    }

    /**
     * Obtiene el nombre base del archivo según el tipo de documento
     */
    private function getNombreBasePorTipo($tipoDocumento)
    {
        return match ($tipoDocumento) {
            Documento::TIPO_CONSTANCIA_LABORAL => 'Constancia_Laboral',
            Documento::TIPO_CFDI_RECIBO => 'CFDI_Recibo',
            Documento::TIPO_FOTOGRAFIA => 'Fotografia',
            default => 'Documento'
        };
    }

    public function obtenerInformacionUsuario()
    {
        $userId = session('user_id');
        $usuario = Usuario::findOrFail($userId);

        return response()->json([
            'success' => true,
            'usuario' => $usuario,
            'documentos' => $usuario->documentos()->with('errores')->get()
        ]);
    }

    /**
     * Mostrar formulario para corregir información personal rechazada
     */
    public function corregirInformacionPersonal()
    {
        $userId = session('user_id');
        $usuario = Usuario::findOrFail($userId);

        // Obtener la validación actual de información personal
        $validacionActual = $usuario->validacionInformacionPersonalActual();

        // Verificar si la información personal fue rechazada
        if (!$validacionActual || !$validacionActual->estaRechazada()) {
            return back()->with('error', 'No tienes información personal que requiera corrección.');
        }

        return view('aspirante.correccion-informacion', compact('usuario', 'validacionActual'));
    }

    /**
     * Procesar corrección de información personal
     */
    public function actualizarInformacionPersonalRechazada(Request $request)
    {
        $userId = session('user_id');
        $usuario = Usuario::findOrFail($userId);

        $validacionActual = $usuario->validacionInformacionPersonalActual();

        // Verificar que la información personal esté rechazada
        if (!$validacionActual || !$validacionActual->estaRechazada()) {
            return back()->with('error', 'No tienes información personal que requiera corrección.');
        }

        $request->validate([
            'nombre_completo' => 'required|string|max:255|regex:/^[\pL\s\-\.]+$/u',
            'fecha_nacimiento' => [
                'required',
                'date',
                'before_or_equal:-18 years',
                'after_or_equal:-70 years'
            ],
            'curp' => [
                'required',
                'string',
                'size:18',
                'regex:/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z]{2}$/'
            ],
            'telefono' => [
                'required',
                'string',
                'max:15',
                'regex:/^(\+52|52)?\s?(\d{2,3}|\(\d{2,3}\))[\s\-]?\d{3,4}[\s\-]?\d{4}$/'
            ],
            'antiguedad' => 'required|integer|min:0|max:50',
        ], [
            'nombre_completo.required' => 'El campo nombre completo es obligatorio.',
            'nombre_completo.regex' => 'El nombre solo puede contener letras, espacios, guiones y puntos.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before_or_equal' => 'Debes ser mayor de 18 años.',
            'fecha_nacimiento.after_or_equal' => 'La fecha de nacimiento no es válida.',
            'curp.required' => 'El campo CURP es obligatorio.',
            'curp.size' => 'La CURP debe tener exactamente 18 caracteres.',
            'curp.regex' => 'El formato de la CURP no es válido.',
            'telefono.required' => 'El número de teléfono es obligatorio.',
            'telefono.regex' => 'El formato del teléfono no es válido.',
            'antiguedad.required' => 'La antigüedad es obligatoria.',
            'antiguedad.integer' => 'La antigüedad debe ser un número entero.',
            'antiguedad.min' => 'La antigüedad no puede ser negativa.',
            'antiguedad.max' => 'La antigüedad no puede ser mayor a 50 años.',
        ]);

        try {
            DB::beginTransaction();

            // Actualizar información personal
            $usuario->update([
                'nombre_completo' => trim($request->nombre_completo),
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'curp' => strtoupper($request->curp),
                'telefono' => $request->telefono,
                'antiguedad' => $request->antiguedad,
                'updated_at' => now(),
            ]);

            // Crear nueva validación pendiente
            $nuevaValidacion = ValidacionInformacionPersonal::crearValidacionPendiente($usuario->id_usuario);

            // Crear notificación para el comité
            Notificacion::create([
                'tipo' => 'correccion',
                'destinatarios' => 'comite',
                'asunto' => "El usuario {$usuario->nombre_completo} ha corregido su información personal",
                'mensaje' => "El usuario {$usuario->nombre_completo} ha enviado correcciones en su información personal. Requiere nueva validación.",
                'leida' => false,
                'created_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('aspirante.cuenta')
                ->with('success', 'Información personal corregida y enviada para revisión. El estado ha cambiado a "Pendiente".');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar la información personal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Obtener el error más reciente de un documento
     */
    private function obtenerErrorMasReciente($idDocumento, $userId)
    {
        return Error::where('id_documento', $idDocumento)
            ->where('id_usuario', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Mostrar vista de gestión de tarjetas (Protocolo SET)
     */
    public function indexTarjetas()
    {
        $userId = session('user_id');
        $tarjetas = Tarjeta::where('id_usuario', $userId)->get();
        return view('participante.tarjetas', compact('tarjetas'));
    }

    /**
     * Guardar tarjeta generando Firma y Certificado (Simulación SET Completa)
     */
    public function storeTarjeta(Request $request)
    {
        $userId = session('user_id');
        
        $request->validate([
            'titular' => 'required|string|max:100',
            'numero' => 'required|string|min:16|max:16', // Simulación simple
            'expiracion' => 'required|string',
            'cvv' => 'required|string|max:4'
        ]);

        // 1. Enmascarar la tarjeta (Nunca guardar la real en texto plano)
        $enmascarada = '**** **** **** ' . substr($request->numero, -4);
        
        // 2. GENERAR FIRMA DIGITAL (Principio de Integridad SET)
        // Usamos HMAC-SHA256. Si un solo dato cambia, la firma no coincide.
        $datosParaFirmar = $userId . $request->titular . $request->numero . $request->expiracion;
        $secretoBanco = env('APP_KEY'); // Usamos la llave de la app como "secreto bancario"
        $firmaDigital = hash_hmac('sha256', $datosParaFirmar, $secretoBanco);

        // 3. GENERAR CERTIFICADO DIGITAL X.509
        
        $dn = [
            "countryName" => "MX",
            "stateOrProvinceName" => "Ciudad Universitaria",
            "localityName" => "Campus",
            "organizationName" => "Sistema SET Escolar",
            "organizationalUnitName" => "Seguridad",
            "commonName" => $request->titular,
            "emailAddress" => session('user_email') ?? 'alumno@universidad.edu' // Fallback por si no hay email en sesión
        ];

        // CONFIGURACIÓN EXPLÍCITA PARA OPENSSL (Esto arregla el error en Windows)
        // Intentamos detectar la ruta común de config en XAMPP/Windows
        $configPath = 'C:/xampp/php/extras/ssl/openssl.cnf'; 
        
        // Si no existe en esa ruta (o estás en otro entorno), usamos null para que PHP intente buscarlo solo
        // Pero definimos un array de configuración básico
        $configArgs = array(
            "digest_alg" => "sha256",
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );

        // Si encontramos el archivo de config, lo agregamos a los argumentos
        if (file_exists($configPath)) {
            $configArgs['config'] = $configPath;
        }

        // Generar llaves privada/pública
        $privkey = openssl_pkey_new($configArgs);
        
        // VERIFICACIÓN DE ERRORES (Importante para debug)
        if ($privkey === false) {
            // Si falla, probablemente es por falta de openssl.cnf. 
            // Intentamos forzar una ruta alternativa común o lanzar error descriptivo.
            while ($msg = openssl_error_string()) echo $msg . "<br />\n";
            throw new \Exception("Error al generar llaves privadas. Verifica tu configuración de OpenSSL.");
        }

        // Generar solicitud de firma (CSR)
        $csr = openssl_csr_new($dn, $privkey, $configArgs); // ¡Pasamos $configArgs aquí también!

        if ($csr === false) {
            throw new \Exception("Error al generar CSR. Verifica openssl.cnf");
        }

        // Generar el certificado autofirmado
        $sscert = openssl_csr_sign($csr, null, $privkey, 365, $configArgs); // ¡Y aquí también!

        if ($sscert === false) {
            throw new \Exception("Error al firmar el certificado.");
        }

        // Exportar
        openssl_x509_export($sscert, $certout);

        // 4. Guardar en Base de Datos
        Tarjeta::create([
            'id_usuario' => $userId,
            'nombre_titular' => $request->titular,
            'numero_enmascarado' => $enmascarada,
            'hash_tarjeta' => hash('sha256', $request->numero), // Hash unidireccional
            'firma_digital_set' => $firmaDigital,
            'certificado_seguridad' => $certout
        ]);

        BlockchainBlock::addBlock([
            'evento' => 'Registro Tarjeta SET',
            'usuario' => $userId,
            'titular' => $request->titular
        ], 'TarjetaRegistrada');

        return back()->with('success', 'Tarjeta registrada bajo el protocolo SET. Certificado y Firma generados.');
    }
}
