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
        Schema::create('numeral', function (Blueprint $table) {
            $table->string('idNumeral', 10)->primary();
            $table->text('nombre');
            $table->text('descripcion')->nullable();
            $table->string('idNumeralPadre')->nullable()->index('fk_numeral_numeral');
            $table->integer('nivel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('numeral');
    }
};
