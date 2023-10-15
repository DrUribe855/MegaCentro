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
        Schema::create('collection_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tower_clinic_id');
            $table->unsignedBigInteger('residue_id');
            $table->float('weight');
            $table->timestamps();

            $table->foreign('tower_clinic_id')->references('id')->on('clinic_towers')->onDelete('cascade');
            $table->foreign('residue_id')->references('id')->on('residues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_logs');
    }
};