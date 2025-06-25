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
        Schema::create('convocatoria_becas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_beca_id')->constrained('tipo_becas')->cascadeOnDelete();
            $table->integer('anio');
            $table->date('inicio');
            $table->date('fin');
            $table->boolean('estado');
            $table->json('pdf_disposicion')->nullable();
            $table->json('pdf_resolucion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convocatoria_becas');
    }
};
