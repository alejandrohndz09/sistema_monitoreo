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
        Schema::create('evaluacion', function (Blueprint $table) {
            $table->string('idEvaluacion', 6)->primary();
            $table->integer('estado')->nullable()->comment('0=desactivada, 1=finalizada, 2=En proceso');
            $table->string('idUsuario', 6)->nullable()->index('fk_usuario_evaluacion');
            $table->string('fecha_creado')->nullable();
            $table->dateTime('fecha_actualizado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluacion');
    }
};
