<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Error extends Model
{
    use HasFactory;

    protected $table = 'errores';

    protected $primaryKey = 'id_error';

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_usuario',
        'id_documento',
        'descripcion_error',
        'tipo_error',
        'corregido',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'corregido' => 'boolean',
        'updated_at'=> 'datetime',
        'created_at' => 'datetime',
    ];

    // Constantes para tipos de error
    const TIPO_DOCUMENTO = 'Documento';
    const TIPO_INSCRIPCION = 'Inscripción';

    /**
     * Relación con el usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación con el documento (opcional, puede ser null)
     */
    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class, 'id_documento', 'id_documento');
    }

    /**
     * Scope para errores corregidos
     */
    public function scopeCorregidos($query)
    {
        return $query->where('corregido', true);
    }

    /**
     * Scope para errores pendientes (no corregidos)
     */
    public function scopePendientes($query)
    {
        return $query->where('corregido', false);
    }

    /**
     * Scope para errores de un usuario específico
     */
    public function scopeDeUsuario($query, $idUsuario)
    {
        return $query->where('id_usuario', $idUsuario);
    }

    /**
     * Scope para errores relacionados con un documento específico
     */
    public function scopeDeDocumento($query, $idDocumento)
    {
        return $query->where('id_documento', $idDocumento);
    }

    /**
     * Verificar si el error está corregido
     */
    public function estaCorregido(): bool
    {
        return (bool) $this->corregido;
    }

    /**
     * Verificar si el error está pendiente
     */
    public function estaPendiente(): bool
    {
        return !$this->estaCorregido();
    }

    /**
     * Verificar si es error de documento
     */
    public function esErrorDocumento(): bool
    {
        return $this->tipo_error === self::TIPO_DOCUMENTO;
    }

    /**
     * Verificar si es error de inscripción
     */
    public function esErrorInscripcion(): bool
    {
        return $this->tipo_error === self::TIPO_INSCRIPCION;
    }

    /**
     * Marcar error como corregido
     */
    public function marcarComoCorregido(): bool
    {
        return $this->update(['corregido' => true]);
    }

    /**
     * Marcar error como pendiente
     */
    public function marcarComoPendiente(): bool
    {
        return $this->update(['corregido' => false]);
    }

    /**
     * Obtener el nombre del tipo de error formateado
     */
    public function getTipoErrorFormateado(): string
    {
        return match($this->tipo_error) {
            self::TIPO_DOCUMENTO => 'Error en Documento',
            self::TIPO_INSCRIPCION => 'Error en Inscripción',
            default => $this->tipo_error,
        };
    }

    /**
     * Obtener el estado formateado
     */
    public function getEstadoFormateado(): string
    {
        return $this->estaCorregido() ? 'Corregido' : 'Pendiente';
    }

    /**
     * Obtener todos los tipos de error disponibles
     */
    public static function getTiposError(): array
    {
        return [
            self::TIPO_DOCUMENTO,
            self::TIPO_INSCRIPCION,
        ];
    }

    /**
     * Crear un nuevo error de documento
     */
    public static function crearErrorDocumento($idUsuario, $idDocumento, $descripcion): self
    {
        return self::create([
            'id_usuario' => $idUsuario,
            'id_documento' => $idDocumento,
            'descripcion_error' => $descripcion,
            'corregido' => false,
        ]);
    }

    /**
     * Crear un nuevo error de inscripción
     */
    public static function crearErrorInscripcion($idUsuario, $descripcion, $idDocumento = null): self
    {
        return self::create([
            'id_usuario' => $idUsuario,
            'id_documento' => $idDocumento,
            'tipo_error' => self::TIPO_INSCRIPCION,
            'descripcion_error' => $descripcion,
            'corregido' => false,
        ]);
    }
}
