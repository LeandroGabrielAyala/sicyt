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
        Schema::create('documentacions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('postulacion_id');

            $table->string('nombre');
            $table->string('archivo');
            $table->string('tipo');
            $table->date('fecha');

            $table->timestamps();

            $table->foreign('postulacion_id')
                ->references('id')
                ->on('postulaciones')
                ->cascadeOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentacions');
    }
};
