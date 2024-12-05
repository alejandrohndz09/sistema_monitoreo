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
        Schema::create('monitoreo', function (Blueprint $table) {
            $table->string('idMonitoreo', 6)->primary();
            $table->string('idEvaluacion', 6)->index('fk_empresa_monitoreo');
            $table->string('idNumeral', 10)->index('fk_numeral_monitoreo');
            $table->float('evaluacion')->comment('0=No, 1=Sí, 0.5=Parcialmente');
            $table->dateTime('fecha_creado');
            $table->dateTime('fecha_actualizado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoreo');
    }
};
