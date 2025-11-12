<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('id_comite')->nullable()->after('id_notificacion');
            $table->unsignedBigInteger('id_usuario')->nullable()->after('id_comite');
            $table->enum('tipo_usuario', ['comite', 'personal', 'aspirante'])->nullable()->after('id_usuario');

            $table->foreign('id_comite')->references('id_comite')->on('comite')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('notificaciones', function (Blueprint $table) {
            $table->dropForeign(['id_comite']);
            $table->dropColumn(['id_comite', 'id_usuario', 'tipo_usuario']);
        });
    }
};
