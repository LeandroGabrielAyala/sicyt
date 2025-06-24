<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('becario_proyecto', function (Blueprint $table) {
            $table->id();

            $table->foreignId('becario_id')->constrained()->cascadeOnDelete();
            $table->foreignId('proyecto_id')->constrained()->cascadeOnDelete();

            $table->foreignId('convocatoria_beca_id')->constrained('convocatoria_becas')->cascadeOnDelete();

            $table->foreignId('director_id')->nullable()->constrained('investigadors')->nullOnDelete();
            $table->foreignId('codirector_id')->nullable()->constrained('investigadors')->nullOnDelete();

            $table->timestamps();

            $table->unique(['becario_id', 'proyecto_id', 'convocatoria_beca_id'], 'becario_proyecto_convocatoria_unique');
        });
    }

    public function down(): void {
        Schema::dropIfExists('becario_proyecto');
    }
};
