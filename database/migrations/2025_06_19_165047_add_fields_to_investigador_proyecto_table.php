<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::table('investigador_proyecto', function (Blueprint $table) {
            $table->foreignId('funcion_id')->nullable()->constrained()->nullOnDelete();
            $table->date('inicio')->nullable();
            $table->date('fin')->nullable();
            $table->string('disposicion');
            $table->string('resolucion');
            $table->json('pdf_disposicion')->nullable();
            $table->json('pdf_resolucion')->nullable();
            $table->text('plan_trabajo');
            $table->boolean('vigente')->default(true);
        });
    }

    public function down(): void {
        Schema::table('investigador_proyecto', function (Blueprint $table) {
            $table->dropColumn(['funcion_id', 'inicio', 'fin', 'pdf_disposicion', 'pdf_resolucion', 'plan_trabajo', 'vigente']);
        });
    }

};
