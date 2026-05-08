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
        Schema::create('route_stops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_route'); // Relación con ruta
            $table->unsignedBigInteger('id_pickup_request'); // Relación con solicitud
            $table->integer('stop_order'); // Orden de la parada
            $table->string('status')->default('pendiente'); // Estado de la parada
            $table->timestamps();

            // Definir las claves foráneas
            $table->foreign('id_route')->references('id')->on('routes')->onDelete('cascade');
            $table->foreign('id_pickup_request')->references('id')->on('pickup_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_stops');
    }
};
