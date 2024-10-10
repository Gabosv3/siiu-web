<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('models', function (Blueprint $table) {
            $table->id(); // Clave primaria: id

            // Llave foránea que referencia a la tabla 'fabricantes'
            $table->foreignId('manufacturer_id')->constrained('manufacturers')->onDelete('cascade'); // Elimina en cascada si se elimina el fabricante

            $table->string('name'); // Nombre del modelo

            $table->timestamps(); // Timestamps para created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('models');
    }
}
