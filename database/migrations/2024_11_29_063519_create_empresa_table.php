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
        Schema::create('empresa', function (Blueprint $table) {
            $table->string('idEmpresa', 6)->primary();
            $table->string('nombre');
            $table->string('direccion')->nullable();
            $table->string('correo');
            $table->dateTime('fecha_creado');
            $table->dateTime('fecha_actualizado')->nullable();
            $table->integer('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
