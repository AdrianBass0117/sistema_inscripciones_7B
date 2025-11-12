<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialDisciplina extends Model
{
    use HasFactory;

    protected $table = 'historial_disciplinas';

    protected $primaryKey = 'id_historial';

    public $timestamps = true;

    // Estados de finalización
    const ESTADO_COMPLETADA = 'completada';
    const ESTADO_CANCELADA = 'cancelada';
    const ESTADO_EXPIRADA = 'expirada';

    protected $fillable = [
        'id_disciplina',
        'nombre_disciplina',
        'descripcion',
        'categoria',
        'genero',
        'cupo_maximo',
        'periodo_inicio',
        'periodo_fin',
        'total_inscritos',
        'fecha_finalizacion',
        'estado_finalizacion',
    ];

    protected $casts = [
        'periodo_inicio' => 'date',
        'periodo_fin' => 'date',
        'fecha_finalizacion' => 'datetime',
        'cupo_maximo' => 'integer',
        'total_inscritos' => 'integer',
    ];

    /**
     * Relación con las inscripciones del historial
     */
    public function inscripcionesHistorial()
    {
        return $this->hasMany(HistorialInscripcionDisciplina::class, 'id_historial_disciplina', 'id_historial');
    }

    /**
     * Relación con la disciplina original
     */
    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'id_disciplina', 'id_disciplina');
    }

    /**
     * Scope para disciplinas completadas
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado_finalizacion', self::ESTADO_COMPLETADA);
    }

    /**
     * Obtener el estado formateado
     */
    public function getEstadoFinalizacionFormateado(): string
    {
        return match ($this->estado_finalizacion) {
            self::ESTADO_COMPLETADA => 'Completada',
            self::ESTADO_CANCELADA => 'Cancelada',
            self::ESTADO_EXPIRADA => 'Expirada',
            default => ucfirst($this->estado_finalizacion),
        };
    }

    /**
     * Calcular tasa de participación
     */
    public function getTasaParticipacion(): float
    {
        if ($this->total_inscritos === 0) return 0;

        $participantes = $this->inscripcionesHistorial()->queParticiparon()->count();
        return round(($participantes / $this->total_inscritos) * 100, 2);
    }

    /**
     * Obtener inscripciones históricas que participaron
     */
    public function inscripcionesQueParticiparon()
    {
        return $this->inscripcionesHistorial()->queParticiparon();
    }

    /**
     * Contar total de participantes
     */
    public function contarParticipantes(): int
    {
        return $this->inscripcionesQueParticiparon()->count();
    }

    /**
     * Obtener el nombre del género formateado
     */
    public function getGeneroFormateado(): string
    {
        return match ($this->genero) {
            'Varoni' => 'Varonil',
            'Femeni' => 'Femenil',
            'Mixto' => 'Mixto',
            default => $this->genero,
        };
    }
}
