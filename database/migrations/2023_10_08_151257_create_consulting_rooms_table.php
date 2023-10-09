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
        Schema::create('consulting_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('number_consulting');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['ACTIVO', 'INACTIVO']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consulting_rooms');
    }
};
