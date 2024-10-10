<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Clave primaria: id
        
            // Relación con la tabla 'departments'
            $table->unsignedBigInteger('departament_id')->nullable(); // Clave foránea al departamento, puede ser nulo
            $table->foreign('departament_id')->references('id')->on('departaments')->onDelete('set null'); // Si el departamento es eliminado, se coloca en NULL
        
            $table->string('name'); // Nombre del usuario
            $table->string('email')->unique(); // Correo electrónico único del usuario
            $table->timestamp('email_verified_at')->nullable(); // Fecha de verificación del correo (opcional)
            $table->string('password'); // Contraseña del usuario
            $table->rememberToken(); // Token para recordar sesión del usuario
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
        Schema::dropIfExists('users');
    }
}
