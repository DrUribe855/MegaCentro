<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('residue_types', function (Blueprint $table) {
            $table->id();
            $table->enum('residue_category', ['Residuo Peligroso', 'Residuo No Peligroso']);
            $table->enum('residue_type', ['NO TIENE', 'INFECCIOSOS O DE RIESGO BIOLOGICO', 'QUIMICOS', 'RADIACTIVOS']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residue_types');
    }
};
