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
        Schema::create('usuario', function (Blueprint $table) {
            $table->string('idUsuario', 6)->primary();
            $table->string('usuario');
            $table->text('clave');
            $table->integer('rol')->comment('0=Admin, 1=Usuario');
            $table->text('token')->nullable();
            $table->dateTime('fecha_creado');
            $table->dateTime('fecha_actualizado')->nullable();
            $table->string('idEmpresa', 6)->index('fk_usuario_empresa');
            $table->integer('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
