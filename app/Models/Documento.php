<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $primaryKey = 'id_documento';

    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'tipo_documento',
        'url_archivo',
        'estado',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Constantes para tipos de documento
    const TIPO_CONSTANCIA_LABORAL = 'Constancia Laboral';
    const TIPO_CFDI_RECIBO = 'CFDI/Recibo';
    const TIPO_FOTOGRAFIA = 'Fotografía';

    // Constantes para estados del documento
    const ESTADO_PENDIENTE = 'Pendiente';
    const ESTADO_APROBADO = 'Aprobado';
    const ESTADO_RECHAZADO = 'Rechazado';

    /**
     * Relación con el usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Scope para documentos pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    /**
     * Scope para documentos aprobados
     */
    public function scopeAprobados($query)
    {
        return $query->where('estado', self::ESTADO_APROBADO);
    }

    /**
     * Scope para documentos rechazados
     */
    public function scopeRechazados($query)
    {
        return $query->where('estado', self::ESTADO_RECHAZADO);
    }

    /**
     * Scope para documentos por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_documento', $tipo);
    }

    /**
     * Scope para documentos de constancia laboral
     */
    public function scopeConstanciasLaborales($query)
    {
        return $query->where('tipo_documento', self::TIPO_CONSTANCIA_LABORAL);
    }

    /**
     * Scope para documentos CFDI/Recibo
     */
    public function scopeCfdiRecibos($query)
    {
        return $query->where('tipo_documento', self::TIPO_CFDI_RECIBO);
    }

    public function scopeFotografias($query)
    {
        return $query->where('tipo_documento', self::TIPO_FOTOGRAFIA);
    }

    /**
     * Verificar si el documento está pendiente
     */
    public function estaPendiente(): bool
    {
        return $this->estado === self::ESTADO_PENDIENTE;
    }

    /**
     * Verificar si el documento está aprobado
     */
    public function estaAprobado(): bool
    {
        return $this->estado === self::ESTADO_APROBADO;
    }

    /**
     * Verificar si el documento está rechazado
     */
    public function estaRechazado(): bool
    {
        return $this->estado === self::ESTADO_RECHAZADO;
    }

    /**
     * Verificar si es constancia laboral
     */
    public function esConstanciaLaboral(): bool
    {
        return $this->tipo_documento === self::TIPO_CONSTANCIA_LABORAL;
    }

    /**
     * Verificar si es CFDI/Recibo
     */
    public function esCfdiRecibo(): bool
    {
        return $this->tipo_documento === self::TIPO_CFDI_RECIBO;
    }

    public function esFotografia(): bool
    {
        return $this->tipo_documento === self::TIPO_FOTOGRAFIA;
    }

    /**
     * Obtener el nombre del tipo de documento formateado
     */
    public function getTipoDocumentoFormateado(): string
    {
        return match($this->tipo_documento) {
            self::TIPO_CONSTANCIA_LABORAL => 'Constancia Laboral',
            self::TIPO_CFDI_RECIBO => 'CFDI/Recibo',
            self::TIPO_FOTOGRAFIA => 'Fotografía',
            default => $this->tipo_documento,
        };
    }

    /**
     * Obtener el nombre del estado formateado
     */
    public function getEstadoFormateado(): string
    {
        return match($this->estado) {
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_APROBADO => 'Aprobado',
            self::ESTADO_RECHAZADO => 'Rechazado',
            default => $this->estado,
        };
    }

    /**
     * Obtener todos los tipos de documento disponibles
     */
    public static function getTiposDocumento(): array
    {
        return [
            self::TIPO_CONSTANCIA_LABORAL,
            self::TIPO_CFDI_RECIBO,
            self::TIPO_FOTOGRAFIA,
        ];
    }

    /**
     * Obtener todos los estados disponibles
     */
    public static function getEstados(): array
    {
        return [
            self::ESTADO_PENDIENTE,
            self::ESTADO_APROBADO,
            self::ESTADO_RECHAZADO,
        ];
    }

    /**
     * Relación con los errores del documento
     */
    public function errores(): HasMany
    {
        return $this->hasMany(Error::class, 'id_documento', 'id_documento');
    }

    /**
     * Obtener errores pendientes del documento
     */
    public function erroresPendientes()
    {
        return $this->errores()->pendientes();
    }

    /**
     * Verificar si el documento tiene errores pendientes
     */
    public function tieneErroresPendientes(): bool
    {
        return $this->errores()->pendientes()->exists();
    }

    /**
     * Obtener el último error pendiente del documento
     */
    public function ultimoErrorPendiente()
    {
        return $this->errores()->pendientes()->latest()->first();
    }
}
