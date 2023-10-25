<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('document')->nullable();
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('status', ['Activo', 'Inactivo']);
            $table->rememberToken();
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
