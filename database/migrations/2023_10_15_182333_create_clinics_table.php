<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('clinic_number')->unique();
            $table->unsignedBigInteger('tower_id');
            $table->enum('status', [ 'OCUPADO', 'DESOCUPADO' ]);
            $table->enum('collection_status', ['SIN RECOLECTAR', 'RECOLECTADO']);
            $table->timestamps();

            $table->foreign('tower_id')->references('id')->on('towers');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
