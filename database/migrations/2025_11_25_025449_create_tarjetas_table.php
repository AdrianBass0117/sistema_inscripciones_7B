<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tarjetas', function (Blueprint $table) {
            $table->id('id_tarjeta');
            $table->integer('id_usuario'); // Relación con tu tabla usuarios
            $table->string('nombre_titular');
            $table->string('numero_enmascarado'); // Guardaremos **** **** **** 1234
            $table->string('hash_tarjeta'); // Hash de la tarjeta real (para comparación interna)
            $table->text('firma_digital_set'); // La firma del protocolo SET
            $table->text('certificado_seguridad'); // El certificado X.509 completo
            $table->timestamps();

            // Llave foránea (ajusta si tu tabla usuarios tiene otro nombre de clave)
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarjetas');
    }
};
