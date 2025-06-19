<?php

use App\Models\Investigador;
use App\Models\Proyecto;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('investigador_proyecto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained()->cascadeOnDelete();
            $table->foreignId('investigador_id')->constrained()->cascadeOnDelete();
            $table->foreignId('funcion_id')->constrained()->cascadeOnDelete();
            $table->boolean('vigente')->default(true);
            $table->date('inicio')->nullable();
            $table->date('fin')->nullable();
            $table->string('disposicion');
            $table->string('resolucion');
            $table->json('pdf_disposicion')->nullable();
            $table->json('pdf_resolucion')->nullable();


            $table->timestamps();

            $table->unique(['proyecto_id', 'investigador_id']); // evita duplicados
        });
    }

    public function down(): void {
        Schema::dropIfExists('investigador_proyecto');
    }
};
