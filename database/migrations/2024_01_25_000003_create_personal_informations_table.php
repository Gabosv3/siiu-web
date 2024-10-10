<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_informations', function (Blueprint $table) {
            $table->id(); // Clave primaria: id

            // Llave foránea que referencia a la tabla 'users'
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('last_name')->nullable(); // Apellidos del usuario
            $table->string('first_name')->nullable(); // Nombres del usuario
            $table->date('birth_date')->nullable(); // Fecha de nacimiento
            $table->string('gender')->nullable(); // Género del usuario
            $table->string('dui')->unique()->nullable(); // DUI único del usuario
            $table->string('phone')->unique()->nullable(); // Número de teléfono único del usuario
            
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
        Schema::dropIfExists('personal_informations');
    }
}
