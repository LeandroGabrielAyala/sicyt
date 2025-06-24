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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->integer('nro');
            $table->string('nombre');
            $table->text('resumen');
            $table->unsignedBigInteger('director_id')->nullable();
            $table->foreign('director_id')->references('id')->on('investigadors')->nullOnDelete();
            $table->unsignedBigInteger('codirector_id')->nullable();
            $table->foreign('codirector_id')->references('id')->on('investigadors')->nullOnDelete();
            $table->foreignId('campo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('objetivo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actividad_id')->constrained()->cascadeOnDelete();
            $table->integer('duracion');
            $table->date('inicio');
            $table->date('fin');
            $table->boolean('estado');
            $table->string('disposicion');
            $table->string('resolucion');
            $table->json('pdf_disposicion')->nullable();
            $table->json('pdf_resolucion')->nullable();
            $table->decimal('presupuesto', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
