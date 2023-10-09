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
        Schema::create('residue_types', function (Blueprint $table) {
            $table->id();
            // $table->enum('residue_category', ['RNP', 'RP']); // RNP: Residuos no peligrosos. RP: Residuos peligrosos. 
            // $table->enum('residue_type', ['IRB', 'Q', 'R', 'NT']); // IRB: Infecciosos o de riego biologicos. Q: Quimicos. R: Radiactivo. NT: No tiene tipo
            $table->string('residue_category');
            $table->string('residue_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residue_types');
    }
};
