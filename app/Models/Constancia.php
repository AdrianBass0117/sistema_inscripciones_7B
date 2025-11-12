<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Constancia extends Model
{
    use HasFactory;

    protected $table = 'constancias';
    protected $primaryKey = 'id_constancia';

    protected $fillable = [
        'id_inscripcion',
        'numero_constancia',
        'codigo_verificacion',
        'fecha_emision',
        'fecha_vencimiento',
        'descargas_realizadas',
        'activa',
        'hash_seguridad'
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
        'fecha_vencimiento' => 'datetime',
        'activa' => 'boolean',
    ];

    /**
     * Relación con la inscripción
     */
    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion', 'id_inscripcion');
    }

    /**
     * Verificar si la constancia está vigente
     */
    public function estaVigente(): bool
    {
        // Si no está activa, no está vigente
        if (!$this->activa) {
            return false;
        }

        // Si no tiene fecha de vencimiento, considerar como vigente
        if (!$this->fecha_vencimiento) {
            return true;
        }

        // Verificar que no haya expirado
        return now()->lte($this->fecha_vencimiento);
    }

    /**
     * Verificar si la constancia ha expirado
     */
    public function haExpirado(): bool
    {
        return now()->gt($this->fecha_vencimiento);
    }

    /**
     * Marcar como descargada
     */
    public function marcarComoDescargada(): bool
    {
        return $this->update([
            'descargas_realizadas' => $this->descargas_realizadas + 1
        ]);
    }

    /**
     * Invalidar constancia
     */
    public function invalidar(): bool
    {
        return $this->update(['activa' => false]);
    }

    /**
     * Scope para constancias vigentes
     */
    public function scopeVigentes($query)
    {
        return $query->where('activa', true)
            ->where('fecha_vencimiento', '>', now());
    }

    /**
     * Scope para buscar por código de verificación
     */
    public function scopePorCodigo($query, $codigo)
    {
        return $query->where('codigo_verificacion', $codigo);
    }

    /**
     * Generar hash de seguridad
     */
    public static function generarHash($inscripcion): string
    {
        $datos = $inscripcion->id_inscripcion .
            $inscripcion->id_usuario .
            $inscripcion->id_disciplina .
            now()->timestamp;

        return hash('sha256', $datos);
    }

    /**
     * Reactivar constancia expirada
     */
    public function reactivar(): bool
    {
        return $this->update([
            'fecha_emision' => now(),
            'fecha_vencimiento' => now()->addMonths(6),
            'activa' => true,
            'descargas_realizadas' => 0 // Reiniciar contador si es necesario
        ]);
    }
}
