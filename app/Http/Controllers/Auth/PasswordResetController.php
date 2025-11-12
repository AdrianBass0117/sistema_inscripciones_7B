<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Comite;
use App\Models\Supervisor;

class PasswordResetController extends Controller
{
    /**
     * Muestra la vista de solicitud de enlace (forgot-password.blade.php)
     */
    public function showLinkRequestForm()
    {
        // Corregimos la ruta a la vista que subiste
        return view('auth.forgot-password');
    }

    /**
     * Envía el enlace de reseteo.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->email;
        $brokerName = null;

        // Usamos los nombres de provider de tu auth.php
        if (Comite::where('email', $email)->exists()) {
            $brokerName = 'comites'; // <-- CORREGIDO
        } elseif (Supervisor::where('email', $email)->exists()) {
            $brokerName = 'supervisores'; // <-- CORREGIDO
        } elseif (Usuario::where('email', $email)->exists()) {
            $brokerName = 'users'; // <-- CORREGIDO
        }

        if (!$brokerName) {
            return back()->withErrors(['email' => 'No podemos encontrar un usuario con esa dirección de correo electrónico.']);
        }

        // Usamos el PasswordBroker específico
        $status = Password::broker($brokerName)->sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', '¡Te hemos enviado por correo el enlace para restablecer tu contraseña!')
                    : back()->withErrors(['email' => 'No se pudo enviar el enlace de reseteo.']);
    }

    /**
     * Muestra la vista de reseteo (reset-password.blade.php)
     */
    public function showResetForm(Request $request, $token = null)
    {
        // Corregimos la ruta a la vista que subiste
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Procesa el reseteo de contraseña.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
        ], [
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un carácter especial.'
        ]);

        $email = $request->email;
        $brokerName = null;

        // Usamos los nombres de provider de tu auth.php
        if (Comite::where('email', $email)->exists()) {
            $brokerName = 'comites'; // <-- CORREGIDO
        } elseif (Supervisor::where('email', $email)->exists()) {
            $brokerName = 'supervisores'; // <-- CORREGIDO
        } elseif (Usuario::where('email', $email)->exists()) {
            $brokerName = 'users'; // <-- CORREGIDO
        }

        if (!$brokerName) {
            return back()->withErrors(['email' => 'Correo no encontrado.']);
        }

        // Intentar resetear la contraseña
        $status = Password::broker($brokerName)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // El campo de contraseña es 'password_hash' en tus modelos
                $user->password_hash = Hash::make($password);
                $user->save();
            }
        );

        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', '¡Tu contraseña ha sido restablecida!')
                    : back()->withErrors(['email' => 'No se pudo restablecer la contraseña (token inválido o expirado).']);
    }
}