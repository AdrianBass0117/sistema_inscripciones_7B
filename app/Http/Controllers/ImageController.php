<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Usuario;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageController extends Controller
{
    public function showUserPhoto($userId)
    {
        try {
            // Buscar el usuario primero para verificar que existe
            $usuario = Usuario::find($userId);
            if (!$usuario) {
                abort(404, 'Usuario no encontrado');
            }

            // Buscar la fotografía del usuario
            $fotografia = Documento::where('id_usuario', $userId)
                ->where('tipo_documento', Documento::TIPO_FOTOGRAFIA)
                ->where('estado', Documento::ESTADO_APROBADO)
                ->first();

            if (!$fotografia) {
                // Si no hay foto, puedes devolver una imagen por defecto o 404
                abort(404, 'Foto no encontrada para este usuario');
            }

            // Usar el mismo método que en verDocumento para consistencia
            $carpeta = 'fotografias';
            $nombreArchivo = basename($fotografia->url_archivo);
            $rutaArchivo = storage_path("app/public/{$carpeta}/{$nombreArchivo}");

            // Verificar si el archivo existe
            if (!file_exists($rutaArchivo)) {
                // Si no existe, intentar con la ruta almacenada directamente
                $rutaArchivo = storage_path("app/public/{$fotografia->url_archivo}");

                if (!file_exists($rutaArchivo)) {
                    abort(404, 'Archivo de imagen no encontrado');
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

            $contentType = $mimeTypes[$extension] ?? 'image/jpeg';

            return response()->file($rutaArchivo, [
                'Content-Type' => $contentType,
                'Cache-Control' => 'public, max-age=3600',
            ]);

        } catch (\Exception $e) {
            abort(404, 'Error al cargar la imagen: ' . $e->getMessage());
        }
    }
}
