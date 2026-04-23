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
        //
        Schema::create('pickup_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_client');
            $table->unsignedBigInteger('id_address'); // Relación con addresses
            $table->string('phone', 20);  // Telefono
            $table->date('requested_date');  // Fecha que pide el cliente
            $table->date('scheduled_date')->nullable(); // Fecha confirmada por logística
            $table->integer('container_quantify'); // Número de pimpinas
            $table->string('additional_details')->nullable();
            $table->string('status'); // pendiente, programada, completada, cancelada.
            $table->unsignedBigInteger('id_driver')->nullable();
            $table->timestamps();

            // Definir las claves foráneas
            $table->foreign('id_client')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_address')->references('id')->on('addresses')->onDelete('restrict');
            $table->foreign('id_driver')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('pickup_requests');
    }
};
