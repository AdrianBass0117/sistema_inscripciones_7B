<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Comite;
use App\Models\Documento;
use App\Models\Supervisor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $email = $request->email;
        $password = $request->password;

        // Verificar Comité
        $comite = Comite::where('email', $email)->first();
        // En el método login, después de verificar las credenciales del comité:
        if ($comite && Hash::check($password, $comite->password_hash)) {
            session([
                'user_type' => 'comite',
                'user_id' => $comite->id_comite,
                'user_email' => $comite->email,
            ]);
            return response()->json([
                'success' => true,
                'redirect' => route('comite')
            ]);
        }

        // Verificar Supervisor
        $supervisor = Supervisor::where('email', $email)->first();
        if ($supervisor && Hash::check($password, $supervisor->password_hash)) {
            session(['user_type' => 'supervisor', 'user_id' => $supervisor->id_supervisor]);
            return response()->json([
                'success' => true,
                'redirect' => route('supervisor')
            ]);
        }

        // Verificar Usuario
        $usuario = Usuario::where('email', $email)->first();
        if ($usuario) {
            if (Hash::check($password, $usuario->password_hash)) {
                switch ($usuario->estado_cuenta) {
                    case Usuario::ESTADO_VALIDADO:
                        session([
                            'user_type' => 'usuario',
                            'user_id' => $usuario->id_usuario,
                            'user_email' => $usuario->email,
                            'user_name' => $usuario->nombre_completo
                        ]);
                        return response()->json([
                            'success' => true,
                            'redirect' => route('personal')
                        ]);

                    case Usuario::ESTADO_PENDIENTE:
                    case Usuario::ESTADO_RECHAZADO:
                        session(['user_type' => 'aspirante', 'user_id' => $usuario->id_usuario]);
                        return response()->json([
                            'success' => true,
                            'redirect' => route('aspirante')
                        ]);

                    case Usuario::ESTADO_SUSPENDIDO:
                        return response()->json([
                            'success' => false,
                            'show_modal' => true,
                            'message' => 'Cuenta suspendida. Contacte al administrador.'
                        ]);
                }
            }
        }

        // Credenciales incorrectas
        return response()->json([
            'success' => false,
            'message' => 'Las credenciales proporcionadas no son válidas.'
        ], 401);
    }

    public function getUserPhoto($userId)
    {
        try {
            $user = Usuario::find($userId);
            if (!$user) return null;

            $fotografia = $user->documentos()
                ->where('tipo_documento', Documento::TIPO_FOTOGRAFIA)
                ->where('estado', Documento::ESTADO_APROBADO)
                ->first();

            if ($fotografia) {
                // Verificar si el archivo existe usando el mismo método que en verDocumento
                $carpeta = 'fotografias';
                $nombreArchivo = basename($fotografia->url_archivo);
                $rutaArchivo = storage_path("app/public/{$carpeta}/{$nombreArchivo}");

                if (file_exists($rutaArchivo)) {
                    return route('user.photo', ['userId' => $userId]);
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function logout(Request $request)
    {
        // Limpiar sesión
        session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
