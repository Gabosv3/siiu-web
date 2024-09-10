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
            $table->id();
            $table->string('nombre_software');
            $table->string('version');
            $table->string('fabricante');
            $table->string('asignada')->nullable(); // Puede ser nulo si no está asignado
            $table->string('ubicacion');
            $table->string('clasificacion_licencia');
            $table->string('tipo');
            // Campos adicionales sugeridos
            $table->text('descripcion')->nullable();
            $table->string('clave_licencia')->nullable();
            $table->date('fecha_compra')->nullable();
            $table->timestamps(); // created_at y updated_at
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
