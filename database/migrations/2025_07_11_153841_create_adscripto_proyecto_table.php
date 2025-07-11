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
        Schema::create('adscripto_proyecto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adscripto_id')->constrained()->cascadeOnDelete();
            $table->foreignId('proyecto_id')->constrained()->cascadeOnDelete();
            $table->foreignId('convocatoria_adscripto_id')->constrained()->cascadeOnDelete();
            $table->foreignId('director_id')->nullable()->constrained('investigadors')->nullOnDelete();
            $table->foreignId('codirector_id')->nullable()->constrained('investigadors')->nullOnDelete();
            $table->boolean('vigente')->default(true);
            $table->timestamps();

            $table->unique(['adscripto_id', 'proyecto_id', 'convocatoria_adscripto_id'], 'adscripto_proyecto_unique');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adscripto_proyecto');
    }
};
