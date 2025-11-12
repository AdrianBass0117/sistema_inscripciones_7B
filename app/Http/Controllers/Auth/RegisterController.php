<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Documento;
use App\Models\Comite;
use App\Models\Supervisor;
use App\Models\Notificacion;
use App\Models\ValidacionInformacionPersonal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validación de campos con mensajes personalizados
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255|regex:/^[\pL\s\-\.]+$/u',
            'numero_trabajador' => 'required|string|max:50|unique:usuarios,numero_trabajador|regex:/^[A-Z0-9\-]+$/',
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
                'regex:/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z]{2}$/',
                'unique:usuarios,curp'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                Rule::unique('usuarios', 'email'),
                Rule::unique('comite', 'email'),
                Rule::unique('supervisor', 'email'),
            ],
            'telefono' => [
                'required',
                'string',
                'max:15',
                'regex:/^(\+52|52)?\s?(\d{2,3}|\(\d{2,3}\))[\s\-]?\d{3,4}[\s\-]?\d{4}$/'
            ],
            'antiguedad' => 'required|integer|min:0|max:50',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            'terms' => 'required|accepted',
            'constancia_laboral' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'cfdi' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'fotografia' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ], [
            // ... mensajes personalizados existentes (sin cambios) ...
            'nombre.required' => 'El campo nombre completo es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios, guiones y puntos.',
            'numero_trabajador.required' => 'El número de trabajador es obligatorio.',
            'numero_trabajador.unique' => 'Este número de trabajador ya está registrado.',
            'numero_trabajador.regex' => 'El número de trabajador solo puede contener letras mayúsculas, números y guiones.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before_or_equal' => 'Debes ser mayor de 18 años para registrarte.',
            'fecha_nacimiento.after_or_equal' => 'La fecha de nacimiento no es válida.',
            'curp.required' => 'El campo CURP es obligatorio.',
            'curp.size' => 'La CURP debe tener exactamente 18 caracteres.',
            'curp.regex' => 'El formato de la CURP no es válido.',
            'curp.unique' => 'Esta CURP ya está registrada en el sistema.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.regex' => 'El formato del correo electrónico no es válido.',
            'email.unique' => 'Este correo electrónico ya está registrado en el sistema.',
            'telefono.required' => 'El número de teléfono es obligatorio.',
            'telefono.regex' => 'El formato del teléfono no es válido. Use formato mexicano.',
            'antiguedad.required' => 'La antigüedad es obligatoria.',
            'antiguedad.integer' => 'La antigüedad debe ser un número entero.',
            'antiguedad.min' => 'La antigüedad no puede ser negativa.',
            'antiguedad.max' => 'La antigüedad no puede ser mayor a 50 años.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un carácter especial (@$!%*?&).',
            'terms.required' => 'Debes aceptar los términos y condiciones.',
            'terms.accepted' => 'Debes aceptar los términos y condiciones.',

            // Mensajes para archivos
            'constancia_laboral.required' => 'La constancia laboral es obligatoria.',
            'constancia_laboral.file' => 'La constancia laboral debe ser un archivo válido.',
            'constancia_laboral.mimes' => 'La constancia laboral debe ser un archivo PDF, JPG, JPEG o PNG.',
            'constancia_laboral.max' => 'La constancia laboral no debe pesar más de 5MB.',

            'cfdi.required' => 'El CFDI/Recibo de nómina es obligatorio.',
            'cfdi.file' => 'El CFDI/Recibo debe ser un archivo válido.',
            'cfdi.mimes' => 'El CFDI/Recibo debe ser un archivo PDF, JPG, JPEG o PNG.',
            'cfdi.max' => 'El CFDI/Recibo no debe pesar más de 5MB.',

            'fotografia.required' => 'La fotografía del rostro es obligatoria.',
            'fotografia.file' => 'La fotografía debe ser un archivo válido.',
            'fotografia.mimes' => 'La fotografía debe ser un archivo JPG, JPEG o PNG.',
            'fotografia.max' => 'La fotografía no debe pesar más de 2MB.',
        ]);

        // Validación adicional para fecha de nacimiento
        $fechaNacimiento = Carbon::parse($validatedData['fecha_nacimiento']);
        $edad = $fechaNacimiento->age;

        if ($edad < 18) {
            return back()->with('error', 'Debes ser mayor de 18 años para registrarte.')
                ->withInput();
        }

        if ($edad > 70) {
            return back()->with('error', 'La fecha de nacimiento no es válida.')
                ->withInput();
        }

        // Validar CURP con API oficial
        $validacionCURP = $this->validarCURPConAPI($validatedData['curp'], $validatedData['fecha_nacimiento']);

        if (!$validacionCURP['valida']) {
            return back()->with('error', $validacionCURP['mensaje'])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Crear usuario (SOLO información básica para login)
            $usuario = Usuario::create([
                'numero_trabajador' => strtoupper($validatedData['numero_trabajador']),
                'nombre_completo' => trim($validatedData['nombre']),
                'email' => strtolower(trim($validatedData['email'])),
                'password_hash' => Hash::make($validatedData['password']),
                'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
                'curp' => strtoupper($validatedData['curp']),
                'telefono' => $validatedData['telefono'],
                'antiguedad' => $validatedData['antiguedad'],
                'estado_cuenta' => Usuario::ESTADO_PENDIENTE, // Estado general pendiente
            ]);

            // CREAR VALIDACIÓN DE INFORMACIÓN PERSONAL (NUEVO)
            ValidacionInformacionPersonal::crearValidacionPendiente($usuario->id_usuario);

            // Subir documentos (se mantiene igual)
            $this->subirDocumentos($usuario, $request);

            // Crear notificación para el comité (actualizada)
            Notificacion::create([
                'tipo' => 'registro',
                'destinatarios' => 'comite',
                'asunto' => 'Nuevo aspirante - Información y documentos pendientes',
                'mensaje' => 'Un nuevo aspirante espera validación de información personal y documentos. Revisa ambos lo más pronto posible.',
                'leida' => false,
                'created_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('login')
                ->with('success', '¡Registro exitoso! Tu información personal y documentos están pendientes de validación. Te notificaremos por correo electrónico cuando sean revisados.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Eliminar archivos subidos en caso de error
            if (isset($usuario)) {
                $this->eliminarDocumentos($usuario);
            }

            return back()->with('error', 'Error en el registro: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Valida la CURP usando la API oficial del gobierno mexicano
     */
    private function validarCURPConAPI($curp, $fechaNacimiento)
    {
        try {
            $client = new Client([
                'timeout' => 10, // Timeout de 10 segundos
                'verify' => true, // Verificar SSL
            ]);

            $curp = strtoupper(trim($curp));

            $response = $client->get("https://curp.api.gob.mx/curp/{$curp}", [
                'headers' => [
                    'Accept' => 'application/json',
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);

                if (isset($data['resultado']) && $data['resultado'] === 'success') {
                    $datosCURP = $data['datos'];

                    // Verificar coincidencia con fecha de nacimiento
                    $fechaCURP = Carbon::createFromFormat('d-m-Y', $datosCURP['fechaNacimiento'])->format('Y-m-d');
                    $fechaInput = Carbon::parse($fechaNacimiento)->format('Y-m-d');

                    if ($fechaCURP === $fechaInput) {
                        return [
                            'valida' => true,
                            'mensaje' => 'CURP válida y coincide con los datos proporcionados.',
                            'datos' => $datosCURP
                        ];
                    } else {
                        return [
                            'valida' => false,
                            'mensaje' => 'La CURP no coincide con la fecha de nacimiento proporcionada. Fecha en CURP: ' . $datosCURP['fechaNacimiento']
                        ];
                    }
                } else {
                    return [
                        'valida' => false,
                        'mensaje' => 'La CURP no es válida según el registro oficial.'
                    ];
                }
            }

            return [
                'valida' => false,
                'mensaje' => 'Error al consultar el servicio de validación de CURP.'
            ];

        } catch (RequestException $e) {
            Log::error('Error en API CURP: ' . $e->getMessage());

            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();

                if ($statusCode === 404) {
                    return [
                        'valida' => false,
                        'mensaje' => 'La CURP no se encuentra en el registro oficial.'
                    ];
                }
            }

            // Si falla la API, hacer validación local básica
            return $this->validarCURPLocal($curp, $fechaNacimiento);

        } catch (\Exception $e) {
            Log::error('Error general validando CURP: ' . $e->getMessage());

            // Fallback a validación local
            return $this->validarCURPLocal($curp, $fechaNacimiento);
        }
    }

    /**
     * Validación local como fallback cuando la API no está disponible
     */
    private function validarCURPLocal($curp, $fechaNacimiento)
    {
        $curp = strtoupper(trim($curp));
        $fechaNac = Carbon::parse($fechaNacimiento);

        // Extraer componentes de la CURP
        $fechaCURP = substr($curp, 4, 6); // AAMMDD

        // Validar fecha de la CURP vs fecha proporcionada
        $anioCURP = substr($fechaCURP, 0, 2);
        $mesCURP = substr($fechaCURP, 2, 2);
        $diaCURP = substr($fechaCURP, 4, 2);

        $anioNacimiento = $fechaNac->format('y');
        $mesNacimiento = $fechaNac->format('m');
        $diaNacimiento = $fechaNac->format('d');

        // Verificar coincidencia de fecha
        if ($anioCURP === $anioNacimiento &&
            $mesCURP === $mesNacimiento &&
            $diaCURP === $diaNacimiento) {
            return [
                'valida' => true,
                'mensaje' => 'CURP válida (validación local).'
            ];
        }

        return [
            'valida' => false,
            'mensaje' => 'La CURP no coincide con la fecha de nacimiento proporcionada.'
        ];
    }

    private function subirDocumentos(Usuario $usuario, Request $request)
    {
        $documentos = [
            [
                'tipo' => Documento::TIPO_CONSTANCIA_LABORAL,
                'archivo' => $request->file('constancia_laboral'),
                'carpeta' => 'constancias_laborales',
            ],
            [
                'tipo' => Documento::TIPO_CFDI_RECIBO,
                'archivo' => $request->file('cfdi'),
                'carpeta' => 'cfdi_recibos',
            ],
            [
                'tipo' => Documento::TIPO_FOTOGRAFIA,
                'archivo' => $request->file('fotografia'),
                'carpeta' => 'fotografias',
            ],
        ];

        foreach ($documentos as $doc) {
            $archivo = $doc['archivo'];

            if (!$archivo) {
                throw new \Exception("Archivo no encontrado para: " . $doc['tipo']);
            }

            $nombreArchivo = time() . '_' . $usuario->id_usuario . '_' .
                str_replace(' ', '_', $doc['tipo']) . '.' .
                $archivo->getClientOriginalExtension();

            $ruta = $archivo->storeAs($doc['carpeta'], $nombreArchivo, 'public');

            Documento::create([
                'id_usuario' => $usuario->id_usuario,
                'tipo_documento' => $doc['tipo'],
                'url_archivo' => $ruta,
                'estado' => Documento::ESTADO_PENDIENTE,
            ]);
        }
    }

    private function eliminarDocumentos(Usuario $usuario)
    {
        $documentos = $usuario->documentos;

        foreach ($documentos as $documento) {
            if (Storage::disk('public')->exists($documento->url_archivo)) {
                Storage::disk('public')->delete($documento->url_archivo);
            }
            $documento->delete();
        }
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        $existsInUsuarios = Usuario::where('email', $email)->exists();
        $existsInComite = Comite::where('email', $email)->exists();
        $existsInSupervisor = Supervisor::where('email', $email)->exists();

        return response()->json([
            'available' => !($existsInUsuarios || $existsInComite || $existsInSupervisor),
            'message' => $existsInUsuarios || $existsInComite || $existsInSupervisor
                ? 'Este correo electrónico ya está registrado.'
                : 'Correo electrónico disponible.'
        ]);
    }
}
