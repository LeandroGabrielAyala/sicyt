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
        Schema::create('convocatoria_proyectos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_proyecto_id')->constrained('tipo_proyectos')->cascadeOnDelete();
            $table->integer('anio');
            $table->date('inicio');
            $table->date('fin');
            $table->boolean('estado');
            $table->string('disposicion');
            $table->string('resolucion');
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
        Schema::dropIfExists('convocatoria_proyectos');
    }
};
