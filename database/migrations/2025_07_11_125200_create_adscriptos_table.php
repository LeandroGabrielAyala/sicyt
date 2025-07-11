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
        Schema::create('adscriptos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('dni', 10)->unique();
            $table->string('cuil', 15)->unique();
            $table->date('fecha_nac');
            $table->string('lugar_nac');
            $table->string('domicilio');
            $table->string('provincia');
            $table->string('codigo');
            $table->string('email')->unique();
            $table->string('telefono', 20)->unique();
            $table->foreignId('carrera_id')->nullable()->constrained('carreras')->nullOnDelete();
            $table->foreignId('titulo_id')->nullable()->constrained('carreras')->nullOnDelete();
            // Poner en migracion carrera->titulo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adscriptos');
    }
};
