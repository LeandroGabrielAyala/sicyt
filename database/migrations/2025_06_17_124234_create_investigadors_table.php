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
        Schema::create('investigadors', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('dni', 10)->unique();
            $table->string('cuil', 15)->unique();
            $table->date('fecha_nac');
            $table->string('domicilio');
            $table->string('provincia');
            $table->string('email')->unique();
            $table->string('telefono', 20)->unique();
            $table->string('disposicion');
            $table->string('resolucion');
            $table->json('pdf_disposicion')->nullable();
            $table->json('pdf_resolucion')->nullable();
            $table->foreignId('funcion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('nivel_academico_id')->constrained()->cascadeOnDelete();
            $table->foreignId('disciplina_id')->constrained()->cascadeOnDelete();
            $table->foreignId('campo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('objetivo_id')->constrained()->cascadeOnDelete();
            $table->string('titulo');
            $table->string('titulo_posgrado');
            $table->foreignId('cargo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('categoria_interna_id')->constrained()->cascadeOnDelete();
            $table->foreignId('incentivo_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investigadors');
    }
};
