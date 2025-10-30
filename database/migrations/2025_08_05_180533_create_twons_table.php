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
        Schema::create('towns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_department');
            $table->string('name');

            // Definir las claves foráneas
            $table->foreign('id_department')->references('id')->on('departments')->onDelete('cascade');
        });

        $towns = [
            ['name' => 'Barranquilla', 'id_department' => 1],
            ['name' => 'Soledad', 'id_department' => 1],
            ['name' => 'Malambo', 'id_department' => 1],
            ['name' => 'Sabanalarga', 'id_department' => 1],
            ['name' => 'Baranoa', 'id_department' => 1],
            ['name' => 'Galapa', 'id_department' => 1],
            ['name' => 'Puerto Colombia', 'id_department' => 1],
        ];

        // Insertar los registros en la tabla informacion formatos
        foreach ($towns as $t) {
            DB::table('towns')->insert($t);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('towns');
    }
};
