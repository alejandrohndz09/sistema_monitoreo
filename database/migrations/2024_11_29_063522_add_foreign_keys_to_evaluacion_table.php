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
        Schema::table('evaluacion', function (Blueprint $table) {
            $table->foreign(['idUsuario'], 'fk_usuario_evaluacion')->references(['idUsuario'])->on('usuario')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluacion', function (Blueprint $table) {
            $table->dropForeign('fk_usuario_evaluacion');
        });
    }
};
