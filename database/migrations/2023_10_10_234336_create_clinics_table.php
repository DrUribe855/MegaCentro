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
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('clinic_number');
<<<<<<<< HEAD:database/migrations/2023_10_09_141918_create_clinics_table.php
            $table->unsignedBigInteger('user');
            $table->enum('status', ['ACTIVO', 'INACTIVO']);
            $table->timestamps();

            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
========
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('Users');
            $table->string('status');
            $table->timestamps();
>>>>>>>> origin/dev2:database/migrations/2023_10_10_234336_create_clinics_table.php
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
