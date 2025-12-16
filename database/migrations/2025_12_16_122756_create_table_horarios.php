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
        Schema::create('horarios_barbero', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbero_id')->constrained('users')->onDelete('cascade');
            $table->integer('dia_semana')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->enum('estado', ['disponible', 'no_disponible'])->default('disponible')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios_barbero');
    }
};
