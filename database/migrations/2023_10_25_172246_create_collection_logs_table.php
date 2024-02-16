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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('clinic_id');
            $table->Integer('month');
            $table->Integer('year');
            $table->date('collection_date')->nullable();
            $table->enum('stored_stated', ['ALMACENADO', 'RECOLECTADO']); // Estado del almacenado
            $table->enum('schedule', ['Extra - 6:00 AM','Diurno', 'Nocturno', 'Extra']);
            $table->enum('invoice_status', ['Debe', 'Pago']);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('clinic_id')->references('id')->on('clinics');
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
