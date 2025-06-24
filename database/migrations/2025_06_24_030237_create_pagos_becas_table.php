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
        Schema::create('pagos_becas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convocatoria_beca_id')->constrained()->cascadeOnDelete();
            $table->integer('anio');
            $table->date('mes');
            // Ver tabla pivot $table->foreignId('becario_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_becas');
    }
};
