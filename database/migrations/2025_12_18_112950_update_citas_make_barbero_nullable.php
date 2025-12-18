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
        Schema::table('citas', function (Blueprint $table) {
            //Quitamos la FK.
            $table->dropForeign(['barbero_id']);

            //Modificamos la columna para que acepte nulos.
            $table->foreignId('barbero_id')
                ->nullable()
                ->change();

            //Volvemos a crear la FK con la nueva configuracion.
            $table->foreign('barbero_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            //Quitamos la FK.
            $table->dropForeign(['barbero_id']);

            //Modificamos la columna para que no acepte nulos.
            $table->foreignId('barbero_id')
                ->nullable(false)
                ->change();

            //Volvemos a crear la FK con la nueva configuracion.
            $table->foreign('barbero_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
};
