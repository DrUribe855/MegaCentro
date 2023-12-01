<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waste_collections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_collection_log');
            $table->unsignedBigInteger('id_residue');
            $table->float('weight');
            $table->Integer('garbage_bags');
            $table->date('collection_date')->nullable();
            $table->timestamps();

            $table->foreign('id_collection_log')->references('id')->on('collection_logs');
            $table->foreign('id_residue')->references('id')->on('residues');
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_collections');
    }
};
