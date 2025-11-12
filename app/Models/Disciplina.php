<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;

    protected $table = 'disciplinas';

    protected $primaryKey = 'id_disciplina';

    public $timestamps = false;

    const UPDATED_AT = 'updated_at';
    const CREATED_AT = null;

    protected $fillable = [
        'nombre',
        'categoria',
        'genero',
        'cupo_maximo',
        'activa',
        'descripcion',
        'instrucciones',
        'fecha_inicio', // Nuevo campo
        'fecha_fin',    // Nuevo campo
        'updated_at'
    ];

    protected $casts = [
        'cupo_maximo' => 'integer',
        'activa' => 'boolean',
        'fecha_inicio' => 'datetime', // Nuevo cast
        'fecha_fin' => 'datetime',    // Nuevo cast
        'updated_at' => 'datetime',
    ];

    // Constantes para categorías
    const CATEGORIA_DEPORTE = 'Deporte';
    const CATEGORIA_CULTURAL = 'Cultural';

    // Constantes para géneros
    const GENERO_FEMENIL = 'Femenil';
    const GENERO_VARONIL = 'Varonil';
    const GENERO_MIXTO = 'Mixto';

    /**
     * Scope para disciplinas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Scope para disciplinas inactivas
     */
    public function scopeInactivas($query)
    {
        return $query->where('activa', false);
    }

    /**
     * Scope para disciplinas por categoría
     */
    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Scope para disciplinas deportivas
     */
    public function scopeDeportivas($query)
    {
        return $query->where('categoria', self::CATEGORIA_DEPORTE);
    }

    /**
     * Scope para disciplinas culturales
     */
    public function scopeCulturales($query)
    {
        return $query->where('categoria', self::CATEGORIA_CULTURAL);
    }

    /**
     * Scope para disciplinas por género
     */
    public function scopePorGenero($query, $genero)
    {
        return $query->where('genero', $genero);
    }

    /**
     * Scope para disciplinas femeniles
     */
    public function scopeFemeniles($query)
    {
        return $query->where('genero', self::GENERO_FEMENIL);
    }

    /**
     * Scope para disciplinas varoniles
     */
    public function scopeVaroniles($query)
    {
        return $query->where('genero', self::GENERO_VARONIL);
    }

    /**
     * Scope para disciplinas mixtas
     */
    public function scopeMixtas($query)
    {
        return $query->where('genero', self::GENERO_MIXTO);
    }

    /**
     * Scope para disciplinas vigentes (dentro de fechas)
     */
    public function scopeVigentes($query)
    {
        $now = now();
        return $query->where('fecha_inicio', '<=', $now)
            ->where('fecha_fin', '>=', $now);
    }

    /**
     * Scope para disciplinas futuras
     */
    public function scopeFuturas($query)
    {
        return $query->where('fecha_inicio', '>', now());
    }

    /**
     * Scope para disciplinas pasadas
     */
    public function scopePasadas($query)
    {
        return $query->where('fecha_fin', '<', now());
    }

    /**
     * Verificar si la disciplina está activa
     */
    public function estaActiva(): bool
    {
        return (bool) $this->activa;
    }

    /**
     * Verificar si la disciplina está inactiva
     */
    public function estaInactiva(): bool
    {
        return !$this->estaActiva();
    }

    /**
     * Verificar si es deportiva
     */
    public function esDeportiva(): bool
    {
        return $this->categoria === self::CATEGORIA_DEPORTE;
    }

    /**
     * Verificar si es cultural
     */
    public function esCultural(): bool
    {
        return $this->categoria === self::CATEGORIA_CULTURAL;
    }

    /**
     * Verificar si es femenil
     */
    public function esFemenil(): bool
    {
        return $this->genero === self::GENERO_FEMENIL;
    }

    /**
     * Verificar si es varonil
     */
    public function esVaronil(): bool
    {
        return $this->genero === self::GENERO_VARONIL;
    }

    /**
     * Verificar si es mixta
     */
    public function esMixta(): bool
    {
        return $this->genero === self::GENERO_MIXTO;
    }

    /**
     * Verificar si la disciplina está vigente
     */
    public function estaVigente(): bool
    {
        $now = now();
        return $this->fecha_inicio <= $now && $this->fecha_fin >= $now;
    }

    /**
     * Verificar si la disciplina es futura
     */
    public function esFutura(): bool
    {
        return $this->fecha_inicio > now();
    }

    /**
     * Verificar si la disciplina es pasada
     */
    public function esPasada(): bool
    {
        return $this->fecha_fin < now();
    }

    /**
     * Activar la disciplina
     */
    public function activar(): bool
    {
        return $this->update(['activa' => true]);
    }

    /**
     * Desactivar la disciplina
     */
    public function desactivar(): bool
    {
        return $this->update(['activa' => false]);
    }

    /**
     * Obtener el nombre de la categoría formateado
     */
    public function getCategoriaFormateada(): string
    {
        return match ($this->categoria) {
            self::CATEGORIA_DEPORTE => 'Deporte',
            self::CATEGORIA_CULTURAL => 'Cultural',
            default => $this->categoria,
        };
    }

    /**
     * Obtener el nombre del género formateado
     */
    public function getGeneroFormateado(): string
    {
        return match ($this->genero) {
            self::GENERO_FEMENIL => 'Femenil',
            self::GENERO_VARONIL => 'Varonil',
            self::GENERO_MIXTO => 'Mixto',
            default => $this->genero,
        };
    }

    /**
     * Obtener el estado formateado
     */
    public function getEstadoFormateado(): string
    {
        return $this->estaActiva() ? 'Activa' : 'Inactiva';
    }

    /**
     * Obtener el estado de vigencia formateado
     */
    public function getVigenciaFormateada(): string
    {
        if ($this->esPasada()) {
            return 'Finalizada';
        } elseif ($this->esFutura()) {
            return 'Próxima';
        } else {
            return 'En curso';
        }
    }

    /**
     * Obtener todas las categorías disponibles
     */
    public static function getCategorias(): array
    {
        return [
            self::CATEGORIA_DEPORTE,
            self::CATEGORIA_CULTURAL,
        ];
    }

    /**
     * Obtener todos los géneros disponibles
     */
    public static function getGeneros(): array
    {
        return [
            self::GENERO_FEMENIL,
            self::GENERO_VARONIL,
            self::GENERO_MIXTO,
        ];
    }

    /**
     * Obtener disciplinas para select dropdown
     */
    public static function paraSelect(): array
    {
        return self::activas()
            ->vigentes()
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->pluck('nombre', 'id_disciplina')
            ->toArray();
    }

    /**
     * Obtener resumen de la disciplina
     */
    public function getResumenAttribute(): string
    {
        return "{$this->nombre} ({$this->getCategoriaFormateada()} - {$this->getGeneroFormateado()})";
    }

    /**
     * Relación con las inscripciones de la disciplina
     */
    public function inscripciones(): HasMany
    {
        return $this->hasMany(Inscripcion::class, 'id_disciplina', 'id_disciplina');
    }

    /**
     * Obtener inscripciones aceptadas de la disciplina
     */
    public function inscripcionesAceptadas()
    {
        return $this->inscripciones()->aceptadas();
    }

    /**
     * Obtener inscripciones pendientes de la disciplina
     */
    public function inscripcionesPendientes()
    {
        return $this->inscripciones()->pendientes();
    }

    /**
     * Contar inscripciones aceptadas (cupo actual)
     */
    public function contarInscripcionesAceptadas(): int
    {
        return $this->inscripciones()->aceptadas()->count();
    }

    /**
     * Verificar si hay cupo disponible
     */
    public function tieneCupoDisponible(): bool
    {
        return $this->contarInscripcionesAceptadas() < $this->cupo_maximo;
    }

    /**
     * Obtener cupos disponibles
     */
    public function getCuposDisponibles(): int
    {
        $inscritos = $this->contarInscripcionesAceptadas();
        return max(0, $this->cupo_maximo - $inscritos);
    }

    /**
     * Obtener usuarios inscritos (a través de inscripciones aceptadas)
     */
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'inscripciones', 'id_disciplina', 'id_usuario')
            ->wherePivot('estado', Inscripcion::ESTADO_ACEPTADO)
            ->withTimestamps();
    }

    /**
     * Scope para disciplinas con cupo disponible
     */
    public function scopeConCupoDisponible($query)
    {
        return $query->whereHas('inscripcionesAceptadas', function ($q) {
            $q->havingRaw('COUNT(*) < disciplinas.cupo_maximo');
        })->orWhereDoesntHave('inscripcionesAceptadas');
    }

    /**
     * Scope para disciplinas con cupo lleno
     */
    public function scopeConCupoLleno($query)
    {
        return $query->whereHas('inscripcionesAceptadas', function ($q) {
            $q->havingRaw('COUNT(*) >= disciplinas.cupo_maximo');
        });
    }

    /**
     * Verificar si la disciplina tiene cupo lleno
     */
    public function tieneCupoLleno(): bool
    {
        return $this->contarInscripcionesAceptadas() >= $this->cupo_maximo;
    }

    /**
     * Desactivar disciplinas expiradas automáticamente
     */
    public static function desactivarDisciplinasExpiradas(): int
    {
        return self::where('activa', true)
            ->where('fecha_fin', '<', now())
            ->update(['activa' => false, 'updated_at' => now()]);
    }


    /**
     * Verificar si la disciplina tiene fechas válidas
     */
    public function tieneFechasValidas(): bool
    {
        return !is_null($this->fecha_inicio) && !is_null($this->fecha_fin);
    }

    /**
     * Verificar si la fecha de inscripción ha expirado
     */
    public function fechaInscripcionExpirada(): bool
    {
        // Si no tiene fechas válidas, considerar como expirada
        if (!$this->tieneFechasValidas()) {
            return true;
        }

        return now()->gt($this->fecha_fin);
    }

    /**
     * Verificar si la fecha de inscripción está vigente
     */
    public function fechaInscripcionVigente(): bool
    {
        // Si no tiene fechas válidas, no está vigente
        if (!$this->tieneFechasValidas()) {
            return false;
        }

        return now()->between($this->fecha_inicio, $this->fecha_fin);
    }

    /**
     * Verificar si la disciplina puede estar activa
     */
    public function puedeEstarActiva(): bool
    {
        // Para estar activa debe tener ambas fechas
        if (!$this->tieneFechasValidas()) {
            return false;
        }

        // Y no debe haber expirado
        if ($this->fechaInscripcionExpirada()) {
            return false;
        }

        return true;
    }

    /**
     * Obtener días restantes para el cierre de inscripciones
     */
    public function getDiasRestantes(): int
    {
        if (!$this->tieneFechasValidas()) {
            return -999; // Valor que indica "fechas no definidas"
        }

        return now()->diffInDays($this->fecha_fin, false);
    }

    /**
     * Obtener el texto de días restantes
     */
    public function getTextoDiasRestantes(): string
    {
        if (!$this->tieneFechasValidas()) {
            return 'Fechas no definidas';
        }

        $dias = $this->getDiasRestantes();

        if ($dias < 0) {
            return 'Finalizada';
        } elseif ($dias === 0) {
            return 'Último día';
        } elseif ($dias === 1) {
            return '1 día restante';
        } else {
            return "{$dias} días restantes";
        }
    }

    /**
     * Obtener el estado de disponibilidad de la disciplina
     */
    public function getEstadoDisponibilidad(): string
    {
        if (!$this->estaActiva()) {
            return 'inactiva';
        }

        // Si está activa pero no tiene fechas válidas, es un estado especial
        if (!$this->tieneFechasValidas()) {
            return 'sin_fechas';
        }

        if ($this->fechaInscripcionExpirada()) {
            return 'expirada';
        }

        if ($this->tieneCupoLleno()) {
            return 'cupo_lleno';
        }

        if (!$this->fechaInscripcionVigente()) {
            return 'no_iniciada';
        }

        return 'disponible';
    }

    /**
     * Obtener la clase CSS para el estado de disponibilidad
     */
    public function getClaseEstadoDisponibilidad(): string
    {
        return match ($this->getEstadoDisponibilidad()) {
            'inactiva' => 'inactive',
            'sin_fechas' => 'no-dates',
            'expirada' => 'expired',
            'cupo_lleno' => 'full',
            'no_iniciada' => 'upcoming',
            'disponible' => 'active',
            default => 'inactive'
        };
    }

    /**
     * Obtener el texto del estado de disponibilidad
     */
    public function getTextoEstadoDisponibilidad(): string
    {
        return match ($this->getEstadoDisponibilidad()) {
            'inactiva' => 'Inactiva',
            'sin_fechas' => 'Fechas No Definidas',
            'expirada' => 'Inscripciones Cerradas',
            'cupo_lleno' => 'Cupo Lleno',
            'no_iniciada' => 'Próximamente',
            'disponible' => 'Disponible',
            default => 'Inactiva'
        };
    }

    /**
     * Desactivar disciplinas sin fechas válidas o expiradas
     */
    public static function desactivarDisciplinasInvalidas(): int
    {
        return self::where('activa', true)
            ->where(function ($query) {
                $query->whereNull('fecha_inicio')
                    ->orWhereNull('fecha_fin')
                    ->orWhere('fecha_fin', '<', now());
            })
            ->update(['activa' => false, 'updated_at' => now()]);
    }
}
