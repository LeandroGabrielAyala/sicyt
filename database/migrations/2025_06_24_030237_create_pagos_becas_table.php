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
            $table->integer('anio'); // Año del pago
            $table->string('mes');   // Mes (ej. "Enero", "Febrero")
            $table->enum('tipo_beca', ['Grado', 'Posgrado', 'CIN']); // <-- cambio aquí
            $table->foreignId('convocatoria_beca_id')->constrained('convocatoria_becas')->cascadeOnDelete();
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
