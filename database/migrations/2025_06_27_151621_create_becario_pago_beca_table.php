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
        Schema::create('becario_pago_beca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pago_beca_id')->constrained('pagos_becas')->cascadeOnDelete();
            $table->foreignId('becario_id')->constrained()->cascadeOnDelete();
            $table->decimal('monto', 12, 2); // Monto pagado
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('becario_pago_beca');
    }
};
