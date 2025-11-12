<?php

namespace App\Http\Controllers;

use App\Models\Comite;
use App\Models\Supervisor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class CrearController extends Controller
{
    /**
     * Mostrar el formulario de creación de usuarios
     */
    public function index()
    {
        return view('supervisor.comite');
    }

    /**
     * Crear nuevo usuario (comité o supervisor)
     */
    public function crearUsuario(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'tipo_usuario' => 'required|in:comite,supervisor',
            'email' => 'required|email|max:255',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $tipoUsuario = $request->tipo_usuario;
        $email = $request->email;
        $password = $request->password;

        // Verificar que el email sea único en todas las tablas
        if (!$this->emailEsUnico($email)) {
            return response()->json([
                'success' => false,
                'message' => 'El correo electrónico ya está registrado en el sistema'
            ], 422);
        }

        try {
            // Crear el usuario según el tipo
            if ($tipoUsuario === 'comite') {
                $usuario = Comite::crearComite($email, Hash::make($password));
                $mensaje = 'Miembro del comité creado exitosamente';
            } else {
                $usuario = Supervisor::crearSupervisor($email, Hash::make($password));
                $mensaje = 'Supervisor creado exitosamente';
            }

            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'usuario' => [
                    'email' => $usuario->email,
                    'tipo' => $tipoUsuario
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar si el email es único en todas las tablas de usuarios
     */
    private function emailEsUnico($email): bool
    {
        // Verificar en la tabla de comité
        if (Comite::existePorEmail($email)) {
            return false;
        }

        // Verificar en la tabla de supervisores
        if (Supervisor::existePorEmail($email)) {
            return false;
        }

        // Verificar en la tabla de usuarios
        if (Usuario::where('email', $email)->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Obtener lista de usuarios (opcional, para futuras implementaciones)
     */
    public function obtenerUsuarios()
    {
        try {
            $comites = Comite::all()->map(function($comite) {
                return [
                    'id' => $comite->id_comite,
                    'email' => $comite->email,
                    'tipo' => 'comite',
                    'fecha_creacion' => $comite->created_at ?? now()
                ];
            });

            $supervisores = Supervisor::all()->map(function($supervisor) {
                return [
                    'id' => $supervisor->id_supervisor,
                    'email' => $supervisor->email,
                    'tipo' => 'supervisor',
                    'fecha_creacion' => $supervisor->created_at ?? now()
                ];
            });

            $usuarios = $comites->merge($supervisores);

            return response()->json([
                'success' => true,
                'usuarios' => $usuarios
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la lista de usuarios'
            ], 500);
        }
    }
}
