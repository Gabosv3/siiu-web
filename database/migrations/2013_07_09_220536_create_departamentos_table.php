<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique()->nullable(); // unit_code
            $table->string('nombre'); // unit_name
            $table->string('encargado')->nullable();
            $table->string('descripcion', 250); // unit_desc
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps(); // unit_create (created_at) and unit_update (updated_at)
            $table->softDeletes(); // Para manejar el borrado lógico


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departamentos');
    }
}
