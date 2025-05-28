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
            $table->string('resumen');
            $table->foreignId('campo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('objetivo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actividad_id')->constrained()->cascadeOnDelete();
            $table->integer('duracion');
            $table->date('inicio');
            $table->date('fin');
            $table->boolean('estado');
            $table->string('resolucion');
            $table->string('pdf_resolucion');
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
