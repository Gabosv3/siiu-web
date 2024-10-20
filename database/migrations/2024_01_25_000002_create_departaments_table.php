<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departaments', function (Blueprint $table) {
            $table->id(); // Clave primaria: id
            $table->string('code')->unique()->nullable(); // Código único del departamento
            $table->string('name'); // Nombre del departamento
            $table->string('manager')->nullable(); // Encargado o administrador del departamento (opcional)
            $table->string('description', 250); // Descripción breve del departamento
            $table->decimal('latitude', 10, 7)->nullable(); // Latitud geográfica del departamento
            $table->decimal('longitude', 10, 7)->nullable(); // Longitud geográfica del departamento
            $table->timestamps(); // Timestamps para creado en (created_at) y actualizado en (updated_at)
            $table->softDeletes(); // Manejo de borrado lógico
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departaments');
    }
}
