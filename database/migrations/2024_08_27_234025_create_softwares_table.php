<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoftwaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('softwares', function (Blueprint $table) {
            $table->id(); // Clave primaria: id
            $table->foreignId('manufacturer_id')->constrained('manufacturers')->onDelete('cascade'); // Clave foránea a la tabla 'manufacturers'
            // Información del software
            $table->string('software_name'); // Nombre del software
            $table->string('version'); // Versión del software
            $table->text('description')->nullable(); // Descripción del software (opcional)
            $table->enum('type', ['free', 'paid'])->default('free'); // Tipo de software
            $table->timestamps(); // Timestamps para created_at y updated_at
            $table->softDeletes(); // Agrega la columna 'deleted_at'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('softwares');
    }
}
