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
        Schema::table('numeral', function (Blueprint $table) {
            $table->foreign(['idNumeralPadre'], 'Fk_numeral_numeral')->references(['idNumeral'])->on('numeral')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('numeral', function (Blueprint $table) {
            $table->dropForeign('Fk_numeral_numeral');
        });
    }
};
