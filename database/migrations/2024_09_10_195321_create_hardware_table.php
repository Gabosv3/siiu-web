<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHardwareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hardware', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->text('conflictos')->nullable(); // Conflictos (opcional)
            $table->string('estado'); // Estado (asignada, nueva, etc.)
            $table->foreignId('user_id')->nullable()->constrained('users'); // Dueño (si está asignada)
            $table->foreignId('ubicacion_id')->constrained('departamentos'); // Ubicación (departamento)
            $table->string('codigo_de_inventario')->unique(); // Código de inventario único
            $table->string('numero_de_serie')->unique(); // Número de serie único
            $table->foreignId('fabricante_id')->constrained('fabricantes'); // Fabricante vinculado
            $table->foreignId('modelo_id')->nullable()->constrained('modelos'); // Modelo vinculado al fabricante
            $table->json('sistemas_asignados')->nullable(); // Sistemas asignados (software en formato JSON)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hardware');
    }
}
