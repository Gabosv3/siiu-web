<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Clave primaria: id
            
            // Detalles de la categoría
            $table->string('name'); // Nombre de la categoría
            $table->string('code')->unique()->nullable(); // Código único para la categoría (opcional)
            $table->text('description')->nullable(); // Campo para la descripción de la categoría (opcional)
            $table->string('image')->nullable(); // Campo para la imagen de la categoría (opcional)
        
            $table->timestamps(); // Timestamps para created_at y updated_at
            $table->softDeletes(); // Borrado lógico para manejar eliminaciones lógicas
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
