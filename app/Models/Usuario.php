<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $primaryKey = 'id_usuario';

    public $timestamps = true;

    protected $fillable = [
        'numero_trabajador',
        'nombre_completo',
        'email',
        'telefono',
        'password_hash',
        'fecha_nacimiento',
        'curp',
        'antiguedad',
        'estado_cuenta',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'antiguedad' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Constantes para el estado de la cuenta
    const ESTADO_PENDIENTE = 'Pendiente';
    const ESTADO_VALIDADO = 'Validado';
    const ESTADO_RECHAZADO = 'Rechazado';
    const ESTADO_SUSPENDIDO = 'Suspendido';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the name of the password attribute for the user.
     *
     * @return string
     */
    public function getAuthPasswordName()
    {
        return 'password_hash';
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getAuthIdentifierName()
    {
        return 'id_usuario';
    }

    public function getAuthIdentifier()
    {
        return $this->id_usuario;
    }
    /**
     * Scope para usuarios validados
     */
    public function scopeValidados($query)
    {
        return $query->where('estado_cuenta', self::ESTADO_VALIDADO);
    }

    /**
     * Scope para usuarios pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado_cuenta', self::ESTADO_PENDIENTE);
    }

    /**
     * Verificar si el usuario está validado
     */
    public function estaValidado(): bool
    {
        return $this->estado_cuenta === self::ESTADO_VALIDADO;
    }

    /**
     * Verificar si el usuario está suspendido
     */
    public function estaSuspendido(): bool
    {
        return $this->estado_cuenta === self::ESTADO_SUSPENDIDO;
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Obtener documentos por tipo
     */
    public function documentosPorTipo($tipo)
    {
        return $this->documentos()->where('tipo_documento', $tipo);
    }

    /**
     * Obtener documentos por estado
     */
    public function documentosPorEstado($estado)
    {
        return $this->documentos()->where('estado', $estado);
    }

    /**
     * Verificar si el usuario tiene documentos pendientes
     */
    public function tieneDocumentosPendientes(): bool
    {
        return $this->documentos()->where('estado', Documento::ESTADO_PENDIENTE)->exists();
    }

    /**
     * Relación con los errores del usuario
     */
    public function errores(): HasMany
    {
        return $this->hasMany(Error::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Obtener errores pendientes del usuario
     */
    public function erroresPendientes()
    {
        return $this->errores()->pendientes();
    }

    /**
     * Obtener errores corregidos del usuario
     */
    public function erroresCorregidos()
    {
        return $this->errores()->corregidos();
    }

    /**
     * Verificar si el usuario tiene errores pendientes
     */
    public function tieneErroresPendientes(): bool
    {
        return $this->errores()->pendientes()->exists();
    }

    /**
     * Obtener errores de documento del usuario
     */
    public function erroresDocumento()
    {
        return $this->errores()->tipoDocumento();
    }

    /**
     * Obtener errores de inscripción del usuario
     */
    public function erroresInscripcion()
    {
        return $this->errores()->tipoInscripcion();
    }

    /**
     * Relación con las inscripciones del usuario
     */
    public function inscripciones(): HasMany
    {
        return $this->hasMany(Inscripcion::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Obtener inscripciones pendientes del usuario
     */
    public function inscripcionesPendientes()
    {
        return $this->inscripciones()->pendientes();
    }

    /**
     * Obtener inscripciones aceptadas del usuario
     */
    public function inscripcionesAceptadas()
    {
        return $this->inscripciones()->aceptadas();
    }

    /**
     * Obtener inscripciones rechazadas del usuario
     */
    public function inscripcionesRechazadas()
    {
        return $this->inscripciones()->rechazadas();
    }

    /**
     * Verificar si el usuario tiene inscripciones pendientes
     */
    public function tieneInscripcionesPendientes(): bool
    {
        return $this->inscripciones()->pendientes()->exists();
    }

    /**
     * Verificar si el usuario está inscrito en una disciplina específica
     */
    public function estaInscritoEnDisciplina($idDisciplina): bool
    {
        return $this->inscripciones()
            ->where('id_disciplina', $idDisciplina)
            ->whereIn('estado', [Inscripcion::ESTADO_PENDIENTE, Inscripcion::ESTADO_ACEPTADO])
            ->exists();
    }

    /**
     * Obtener disciplinas del usuario (a través de inscripciones aceptadas)
     */
    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'inscripciones', 'id_usuario', 'id_disciplina')
            ->wherePivot('estado', Inscripcion::ESTADO_ACEPTADO)
            ->withTimestamps();
    }

    /**
     * Relación con las notificaciones del usuario
     */
    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Obtener notificaciones no leídas del usuario
     */
    public function notificacionesNoLeidas()
    {
        return $this->notificaciones()->noLeidas();
    }

    /**
     * Contar notificaciones no leídas del usuario
     */
    public function contarNotificacionesNoLeidas(): int
    {
        return $this->notificaciones()->noLeidas()->count();
    }

    /**
     * Relación con las validaciones de información personal
     */
    public function validacionesInformacionPersonal(): HasMany
    {
        return $this->hasMany(ValidacionInformacionPersonal::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Obtener la validación actual de información personal
     */
    public function validacionInformacionPersonalActual()
    {
        return $this->validacionesInformacionPersonal()
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Verificar si la información personal está pendiente de validación
     */
    public function tieneInformacionPersonalPendiente(): bool
    {
        $validacionActual = $this->validacionInformacionPersonalActual();
        return $validacionActual && $validacionActual->estaPendiente();
    }

    /**
     * Verificar si la información personal está aceptada
     */
    public function tieneInformacionPersonalAceptada(): bool
    {
        $validacionActual = $this->validacionInformacionPersonalActual();
        return $validacionActual && $validacionActual->estaAceptada();
    }

    /**
     * Verificar si el usuario tuvo información personal rechazada anteriormente
     */
    public function tieneInformacionPersonalRechazadaAnteriormente(): bool
    {
        return $this->validacionesInformacionPersonal()
            ->where('estado', 'Rechazado')
            ->exists();
    }

    /**
     * Relación con el historial de inscripciones en disciplinas finalizadas
     */
    public function historialInscripcionesDisciplinas()
    {
        return $this->hasMany(HistorialInscripcionDisciplina::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Obtener disciplinas históricas donde participó el usuario
     */
    public function disciplinasHistoricas()
    {
        return $this->belongsToMany(
            HistorialDisciplina::class,
            'historial_inscripciones_disciplinas',
            'id_usuario',
            'id_historial_disciplina'
        )->wherePivot('participo', true);
    }

    /**
     * Verificar si el usuario participó en una disciplina histórica específica
     */
    public function participoEnDisciplinaHistorica($idHistorialDisciplina): bool
    {
        return $this->historialInscripcionesDisciplinas()
            ->where('id_historial_disciplina', $idHistorialDisciplina)
            ->where('participo', true)
            ->exists();
    }
}
