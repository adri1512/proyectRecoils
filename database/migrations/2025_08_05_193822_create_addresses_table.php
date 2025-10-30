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

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_client');
            $table->integer('sort_order'); // orden de visualización
            $table->boolean('is_main')->default(false);
            $table->string('status')->default('activa'); // activa, inactiva, eliminada.
            $table->unsignedBigInteger('previous_id')->nullable();
            $table->string('name');
            $table->unsignedBigInteger('id_town');
            $table->string('neighborhood')->nullable();
            $table->string('address'); 
            $table->string('reference')->nullable();
            $table->timestamps();

            // Definir las claves foráneas
            $table->foreign('id_client')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_town')->references('id')->on('towns')->onDelete('restrict');
            $table->foreign('previous_id')->references('id')->on('addresses')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
