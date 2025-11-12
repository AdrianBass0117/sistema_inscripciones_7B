<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_constancias_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('constancias', function (Blueprint $table) {
            $table->id('id_constancia');
            $table->foreignId('id_inscripcion')->constrained('inscripciones', 'id_inscripcion');
            $table->string('numero_constancia')->unique();
            $table->string('codigo_verificacion')->unique();
            $table->dateTime('fecha_emision');
            $table->dateTime('fecha_vencimiento');
            $table->integer('descargas_realizadas')->default(0);
            $table->boolean('activa')->default(true);
            $table->text('hash_seguridad');
            $table->timestamps();

            $table->index('codigo_verificacion');
            $table->index('numero_constancia');
        });
    }

    public function down()
    {
        Schema::dropIfExists('constancias');
    }
};
