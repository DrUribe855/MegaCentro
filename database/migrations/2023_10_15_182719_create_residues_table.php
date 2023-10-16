<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('residues', function (Blueprint $table) {
            $table->id();
            $table->string('residue_name');
            $table->unsignedBigInteger('residue_type_id');
            $table->timestamps();

            $table->foreign('residue_type_id')->references('id')->on('residue_types')->onDelete('cascade');
        });
    }

     
    public function down(): void
    {
        Schema::dropIfExists('residues');
    }
};
