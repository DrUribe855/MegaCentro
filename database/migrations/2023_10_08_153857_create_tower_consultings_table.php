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
        Schema::create('tower_consultings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tower_id');
            $table->unsignedBigInteger('consulting_id');
            $table->timestamps();

            $table->foreign('tower_id')->references('id')->on('towers')->onDelete('cascade');
            $table->foreign('consulting_id')->references('id')->on('consulting_rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tower_consultings');
    }
};
