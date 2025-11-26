<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{
    use HasFactory;

    protected $table = 'tarjetas';
    protected $primaryKey = 'id_tarjeta';

    protected $fillable = [
        'id_usuario',
        'nombre_titular',
        'numero_enmascarado',
        'hash_tarjeta',
        'firma_digital_set',
        'certificado_seguridad'
    ];
}