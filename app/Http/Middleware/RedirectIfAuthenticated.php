<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar si ya está autenticado (para rutas de login/register)
        if (session()->has('user_type')) {
            $userType = session('user_type');

            // Redirigir según el tipo de usuario
            return match($userType) {
                'comite' => redirect()->route('comite'),
                'supervisor' => redirect()->route('supervisor'),
                'usuario' => redirect()->route('personal'),
                'aspirante' => redirect()->route('aspirante'),
                default => redirect()->route('home')
            };
        }

        return $next($request);
    }
}
