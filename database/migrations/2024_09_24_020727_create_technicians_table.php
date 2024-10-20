<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechniciansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technicians', function (Blueprint $table) {
            $table->id(); // Clave primaria: id
            
            // Relación de clave foránea con la tabla 'users'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relación con la tabla 'users', elimina en cascada
            $table->foreignId('specialty_id')->constrained('specialties')->onDelete('cascade'); // Relación con la tabla 'specialties', elimina en cascada
            $table->boolean('available')->default(true); // Disponibilidad del técnico (por defecto está disponible)
        
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
        Schema::dropIfExists('technicians');
    }
}
