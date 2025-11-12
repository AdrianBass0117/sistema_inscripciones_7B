<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidacionInformacionPersonal extends Model
{
    use HasFactory;

    protected $table = 'validaciones_informacion_personal';

    protected $primaryKey = 'id_validacion_informacion';

    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'estado',
        'motivo_rechazo',
        'fecha_validacion',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'fecha_validacion' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Constantes para el estado de validación
    const ESTADO_PENDIENTE = 'Pendiente';
    const ESTADO_ACEPTADO = 'Aceptado';
    const ESTADO_RECHAZADO = 'Rechazado';

    /**
     * Relación con el usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Scope para validaciones pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    /**
     * Scope para validaciones aceptadas
     */
    public function scopeAceptadas($query)
    {
        return $query->where('estado', self::ESTADO_ACEPTADO);
    }

    /**
     * Scope para validaciones rechazadas
     */
    public function scopeRechazadas($query)
    {
        return $query->where('estado', self::ESTADO_RECHAZADO);
    }

    /**
     * Verificar si la validación está pendiente
     */
    public function estaPendiente(): bool
    {
        return $this->estado === self::ESTADO_PENDIENTE;
    }

    /**
     * Verificar si la validación está aceptada
     */
    public function estaAceptada(): bool
    {
        return $this->estado === self::ESTADO_ACEPTADO;
    }

    /**
     * Verificar si la validación está rechazada
     */
    public function estaRechazada(): bool
    {
        return $this->estado === self::ESTADO_RECHAZADO;
    }

    /**
     * Marcar como aceptada
     */
    public function marcarComoAceptada()
    {
        $this->update([
            'estado' => self::ESTADO_ACEPTADO,
            'fecha_validacion' => now(),
            'motivo_rechazo' => null
        ]);
    }

    /**
     * Marcar como rechazada
     */
    public function marcarComoRechazada(string $motivo)
    {
        $this->update([
            'estado' => self::ESTADO_RECHAZADO,
            'fecha_validacion' => now(),
            'motivo_rechazo' => $motivo
        ]);
    }

    /**
     * Obtener la validación actual de un usuario
     */
    public static function obtenerValidacionActual(int $idUsuario)
    {
        return self::where('id_usuario', $idUsuario)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Crear una nueva validación pendiente para un usuario
     */
    public static function crearValidacionPendiente(int $idUsuario)
    {
        return self::create([
            'id_usuario' => $idUsuario,
            'estado' => self::ESTADO_PENDIENTE,
            'motivo_rechazo' => null,
            'fecha_validacion' => null
        ]);
    }
}
