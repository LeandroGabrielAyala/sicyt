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
        Schema::create('postulaciones', function (Blueprint $table) {
            $table->id();

            // Importante: acá tenés que especificar el nombre de la tabla correcta
            $table->foreignId('convocatoria_id')
                ->constrained('convocatoria_proyectos')
                ->cascadeOnDelete();

            $table->foreignId('investigador_id')
                ->constrained('investigadors')
                ->cascadeOnDelete();

            $table->string('archivo_pdf');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulaciones');
    }
};
