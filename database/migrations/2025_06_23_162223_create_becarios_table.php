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
        Schema::create('becarios', function (Blueprint $table) {
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
            $table->foreignId('tipo_beca_id')->constrained()->cascadeOnDelete();
            $table->text('plan_trabajo');
            $table->decimal('pago', 8, 2);

            $table->foreignId('carrera_id')->nullable()->constrained()->cascadeOnDelete();

            // Solo para posgrado
            $table->foreignId('nivel_academico_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('disciplina_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('campo_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('objetivo_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('titulo')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('becarios');
    }
};
