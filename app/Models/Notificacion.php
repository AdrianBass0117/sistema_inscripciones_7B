<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $primaryKey = 'id_notificacion';

    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'tipo',
        'asunto',
        'mensaje',
        'leida',
        'destinatarios',
        'created_at'
    ];

    protected $casts = [
        'leida' => 'boolean',
        'created_at' => 'datetime',
    ];

    // Constantes para tipos de notificación
    const TIPO_GENERAL = 'general';
    const TIPO_URGENTE = 'urgente';
    const TIPO_RECORDATORIO = 'recordatorio';

    // Constantes para destinatarios
    const DESTINATARIOS_TODOS = 'todos';
    const DESTINATARIOS_COMITE = 'comite';
    const DESTINATARIOS_PERSONAL = 'personal';

    /**
     * Scope para notificaciones leídas
     */
    public function scopeLeidas($query)
    {
        return $query->where('leida', true);
    }

    /**
     * Scope para notificaciones no leídas
     */
    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    /**
     * Scope para notificaciones por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para notificaciones recientes
     */
    public function scopeRecientes($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Verificar si la notificación está leída
     */
    public function estaLeida(): bool
    {
        return (bool) $this->leida;
    }

    /**
     * Verificar si la notificación no está leída
     */
    public function noEstaLeida(): bool
    {
        return !$this->estaLeida();
    }

    /**
     * Marcar como leída
     */
    public function marcarComoLeida(): bool
    {
        return $this->update(['leida' => true]);
    }

    /**
     * Marcar como no leída
     */
    public function marcarComoNoLeida(): bool
    {
        return $this->update(['leida' => false]);
    }

    /**
     * Obtener el nombre del tipo formateado
     */
    public function getTipoFormateado(): string
    {
        return match ($this->tipo) {
            self::TIPO_GENERAL => 'General',
            self::TIPO_URGENTE => 'Urgente',
            self::TIPO_RECORDATORIO => 'Recordatorio',
            default => ucfirst($this->tipo),
        };
    }

    /**
     * Obtener la clase CSS para el tipo
     */
    public function getClaseTipo(): string
    {
        return match ($this->tipo) {
            self::TIPO_GENERAL => 'info',
            self::TIPO_URGENTE => 'danger',
            self::TIPO_RECORDATORIO => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Obtener todos los tipos disponibles
     */
    public static function getTipos(): array
    {
        return [
            self::TIPO_GENERAL,
            self::TIPO_URGENTE,
            self::TIPO_RECORDATORIO,
        ];
    }

    /**
     * Obtener todos los tipos de destinatarios disponibles
     */
    public static function getDestinatariosTipos(): array
    {
        return [
            self::DESTINATARIOS_TODOS,
            self::DESTINATARIOS_COMITE,
            self::DESTINATARIOS_PERSONAL,
        ];
    }

    /**
     * Obtener resumen del mensaje (primeros 100 caracteres)
     */
    public function getResumenMensaje(): string
    {
        return strlen($this->mensaje) > 100
            ? substr($this->mensaje, 0, 100) . '...'
            : $this->mensaje;
    }

    /**
     * Obtener el tiempo transcurrido desde la creación
     */
    public function getTiempoTranscurrido(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Obtener el nombre de los destinatarios formateado
     */
    public function getDestinatariosFormateado(): string
    {
        return match ($this->destinatarios) {
            'todos' => 'Todos los usuarios',
            'comite' => 'Solo comité',
            'personal' => 'Solo personal',
            default => ucfirst($this->destinatarios),
        };
    }
}
