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
        Schema::create('blockchain_ledger', function (Blueprint $table) {
            $table->id(); // Índice del bloque
            $table->longText('data'); // Datos del evento (JSON)
            $table->string('previous_hash'); // El hash del bloque anterior
            $table->string('hash'); // El hash de ESTE bloque
            $table->string('tipo_evento'); // Ej: 'Validacion', 'Registro'
            $table->timestamps(); // Timestamp (Nonce temporal)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blockchain');
    }
};
