<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';

    protected $primaryKey = 'id_inscripcion';

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_usuario',
        'id_disciplina',
        'estado',
        'fecha_inscripcion',
        'fecha_validacion',
        'created_at'
    ];

    protected $casts = [
        'fecha_inscripcion' => 'datetime',
        'fecha_validacion' => 'datetime',
        'created_at' => 'datetime',
    ];

    // Constantes para estados de inscripción
    const ESTADO_PENDIENTE = 'Pendiente';
    const ESTADO_ACEPTADO = 'Aceptado';
    const ESTADO_RECHAZADO = 'Rechazado';
    const ESTADO_CANCELADO = 'Cancelado';

    /**
     * Relación con el usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación con la disciplina
     */
    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class, 'id_disciplina', 'id_disciplina');
    }

    /**
     * Scope para inscripciones pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    /**
     * Scope para inscripciones aceptadas
     */
    public function scopeAceptadas($query)
    {
        return $query->where('estado', self::ESTADO_ACEPTADO);
    }

    /**
     * Scope para inscripciones rechazadas
     */
    public function scopeRechazadas($query)
    {
        return $query->where('estado', self::ESTADO_RECHAZADO);
    }

    public function scopeCanceladas($query)
    {
        return $query->where('estado', self::ESTADO_CANCELADO);
    }

    /**
     * Scope para inscripciones de un usuario específico
     */
    public function scopeDeUsuario($query, $idUsuario)
    {
        return $query->where('id_usuario', $idUsuario);
    }

    /**
     * Scope para inscripciones de una disciplina específica
     */
    public function scopeDeDisciplina($query, $idDisciplina)
    {
        return $query->where('id_disciplina', $idDisciplina);
    }

    /**
     * Scope para inscripciones recientes
     */
    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope para inscripciones por fecha
     */
    public function scopePorFecha($query, $fecha)
    {
        return $query->whereDate('fecha_inscripcion', $fecha);
    }

    /**
     * Verificar si la inscripción está pendiente
     */
    public function estaPendiente(): bool
    {
        return $this->estado === self::ESTADO_PENDIENTE;
    }

    /**
     * Verificar si la inscripción está aceptada
     */
    public function estaAceptada(): bool
    {
        return $this->estado === self::ESTADO_ACEPTADO;
    }

    /**
     * Verificar si la inscripción está rechazada
     */
    public function estaRechazada(): bool
    {
        return $this->estado === self::ESTADO_RECHAZADO;
    }

    /**
     * Verificar si la inscripción está cancelada
     */
    public function estaCancelada(): bool
    {
        return $this->estado === self::ESTADO_CANCELADO;
    }

    /**
     * Verificar si la inscripción está validada (aceptada o rechazada)
     */
    public function estaValidada(): bool
    {
        return $this->estaAceptada() || $this->estaRechazada();
    }

    /**
     * Marcar como aceptada
     */
    public function marcarComoAceptada(): bool
    {
        return $this->update([
            'estado' => self::ESTADO_ACEPTADO,
            'fecha_validacion' => now()
        ]);
    }

    /**
     * Marcar como rechazada
     */
    public function marcarComoRechazada(): bool
    {
        return $this->update([
            'estado' => self::ESTADO_RECHAZADO,
            'fecha_validacion' => now()
        ]);
    }

    /**
     * Obtener el nombre del estado formateado
     */
    public function getEstadoFormateado(): string
    {
        return match ($this->estado) {
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_ACEPTADO => 'Aceptado',
            self::ESTADO_RECHAZADO => 'Rechazado',
            self::ESTADO_CANCELADO => 'Cancelado',
            default => $this->estado,
        };
    }

    /**
     * Obtener la clase CSS para el estado
     */
    public function getClaseEstado(): string
    {
        return match ($this->estado) {
            self::ESTADO_PENDIENTE => 'warning',
            self::ESTADO_ACEPTADO => 'success',
            self::ESTADO_RECHAZADO => 'danger',
            self::ESTADO_CANCELADO => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Obtener todos los estados disponibles
     */
    public static function getEstados(): array
    {
        return [
            self::ESTADO_PENDIENTE,
            self::ESTADO_ACEPTADO,
            self::ESTADO_RECHAZADO,
            self::ESTADO_CANCELADO,
        ];
    }

    /**
     * Crear una nueva inscripción pendiente
     */
    public static function crearInscripcion($idUsuario, $idDisciplina): self
    {
        return self::create([
            'id_usuario' => $idUsuario,
            'id_disciplina' => $idDisciplina,
            'estado' => self::ESTADO_PENDIENTE,
            'fecha_inscripcion' => now(),
            'fecha_validacion' => null,
        ]);
    }

    public static function usuarioInscritoEnDisciplina($idUsuario, $idDisciplina): bool
    {
        return self::where('id_usuario', $idUsuario)
            ->where('id_disciplina', $idDisciplina)
            ->where('estado', '!=', self::ESTADO_CANCELADO)
            ->whereIn('estado', [self::ESTADO_PENDIENTE, self::ESTADO_ACEPTADO])
            ->exists();
    }

    /**
     * Obtener el tiempo transcurrido desde la inscripción
     */
    public function getTiempoTranscurrido(): string
    {
        return $this->created_at->diffForHumans();
    }
}
