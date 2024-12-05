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
        Schema::table('monitoreo', function (Blueprint $table) {
            $table->foreign(['idEvaluacion'], 'fk_empresa_monitoreo')->references(['idEvaluacion'])->on('evaluacion')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['idNumeral'], 'fk_numeral_monitoreo')->references(['idNumeral'])->on('numeral')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoreo', function (Blueprint $table) {
            $table->dropForeign('fk_empresa_monitoreo');
            $table->dropForeign('fk_numeral_monitoreo');
        });
    }
};
