<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialInscripcionDisciplina extends Model
{
    use HasFactory;

    protected $table = 'historial_inscripciones_disciplinas';

    protected $primaryKey = 'id_historial_inscripcion';

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    // Estados de inscripción (consistentes con el modelo Inscripcion)
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_ACEPTADA = 'aceptada';
    const ESTADO_RECHAZADA = 'rechazada';

    protected $fillable = [
        'id_historial_disciplina',
        'id_usuario',
        'id_inscripcion_original',
        'nombre_usuario',
        'email_usuario',
        'fecha_inscripcion_original',
        'estado_inscripcion',
        'participo'
    ];

    protected $casts = [
        'fecha_inscripcion_original' => 'datetime',
        'participo' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Relación con el historial de disciplina
     */
    public function historialDisciplina()
    {
        return $this->belongsTo(HistorialDisciplina::class, 'id_historial_disciplina', 'id_historial');
    }

    /**
     * Relación con el usuario (opcional, si necesitas datos actualizados)
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación con la inscripción original (opcional)
     */
    public function inscripcionOriginal()
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion_original', 'id_inscripcion');
    }

    /**
     * Scope para inscripciones que participaron
     */
    public function scopeQueParticiparon($query)
    {
        return $query->where('participo', true);
    }

    /**
     * Scope para inscripciones por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado_inscripcion', $estado);
    }

    /**
     * Scope para inscripciones aceptadas
     */
    public function scopeAceptadas($query)
    {
        return $query->where('estado_inscripcion', self::ESTADO_ACEPTADA);
    }

    /**
     * Verificar si la inscripción estaba aceptada
     */
    public function estabaAceptada(): bool
    {
        return $this->estado_inscripcion === self::ESTADO_ACEPTADA;
    }

    /**
     * Verificar si la inscripción estaba pendiente
     */
    public function estabaPendiente(): bool
    {
        return $this->estado_inscripcion === self::ESTADO_PENDIENTE;
    }

    /**
     * Verificar si participó
     */
    public function participo(): bool
    {
        return (bool) $this->participo;
    }

    /**
     * Marcar como participó
     */
    public function marcarComoParticipo($observaciones = null): bool
    {
        return $this->update([
            'participo' => true,
        ]);
    }

    /**
     * Obtener el estado formateado
     */
    public function getEstadoFormateado(): string
    {
        return match ($this->estado_inscripcion) {
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_ACEPTADA => 'Aceptada',
            self::ESTADO_RECHAZADA => 'Rechazada',
            default => ucfirst($this->estado_inscripcion),
        };
    }

    /**
     * Obtener la clase CSS para el estado
     */
    public function getClaseEstado(): string
    {
        return match ($this->estado_inscripcion) {
            self::ESTADO_PENDIENTE => 'warning',
            self::ESTADO_ACEPTADA => 'success',
            self::ESTADO_RECHAZADA => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Crear registro de historial desde una inscripción
     */
    public static function crearDesdeInscripcion(Inscripcion $inscripcion, $idHistorialDisciplina): self
    {
        return self::create([
            'id_historial_disciplina' => $idHistorialDisciplina,
            'id_usuario' => $inscripcion->id_usuario,
            'id_inscripcion_original' => $inscripcion->id_inscripcion,
            'nombre_usuario' => $inscripcion->usuario->nombre_completo,
            'email_usuario' => $inscripcion->usuario->email,
            'fecha_inscripcion_original' => $inscripcion->fecha_inscripcion,
            'estado_inscripcion' => self::mapearEstado($inscripcion->estado),
            'participo' => false
        ]);
    }

    /**
     * Mapear estados del modelo Inscripcion al historial
     */
    private static function mapearEstado($estadoInscripcion): string
    {
        return match ($estadoInscripcion) {
            Inscripcion::ESTADO_PENDIENTE => self::ESTADO_PENDIENTE,
            Inscripcion::ESTADO_ACEPTADO => self::ESTADO_ACEPTADA,
            Inscripcion::ESTADO_RECHAZADO => self::ESTADO_RECHAZADA,
            default => self::ESTADO_PENDIENTE,
        };
    }

    /**
     * Obtener todos los estados disponibles
     */
    public static function getEstados(): array
    {
        return [
            self::ESTADO_PENDIENTE,
            self::ESTADO_ACEPTADA,
            self::ESTADO_RECHAZADA,
        ];
    }
}
