<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAuth
{
    public function handle(Request $request, Closure $next, string $userType = null)
    {
        // Verificar si hay sesión activa
        if (!session()->has('user_type')) {
            return redirect()->route('login')->withErrors([
                'email' => 'Por favor inicia sesión para acceder.'
            ]);
        }

        // Si se especifica un tipo de usuario, verificar que coincida
        if ($userType && session('user_type') !== $userType) {
            return redirect()->route('login')->withErrors([
                'email' => 'No tienes permisos para acceder a esta sección.'
            ]);
        }

        return $next($request);
    }
}
