<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIpsecTunnel
{
    public function handle(Request $request, Closure $next): Response
    {
        // SIMULACIÓN DE IPSEC
        // Verificamos si la petición viene de localhost (127.0.0.1)
        $esRedSegura = $request->ip() === '127.0.0.1' || $request->ip() === '::1';

        if (!$esRedSegura) {
            return response()->json([
                'error' => 'Conexión Rechazada',
                'protocolo' => 'IPSEC',
                'mensaje' => 'El tráfico no está encriptado por el túnel IPSEC requerido.'
            ], 403);
        }

        // Si pasa, agregamos una cabecera para indicar que la seguridad de red pasó
        $response = $next($request);
        $response->headers->set('X-Network-Security', 'IPSEC-ESP-AES256-TUNNEL');
        
        return $response;
    }
}