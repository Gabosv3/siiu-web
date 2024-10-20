<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id(); // Clave primaria: id
        
            // Relación de clave foránea con la tabla 'users'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relación con la tabla 'users', elimina en cascada
        
            // Relación de clave foránea con la tabla 'technicians'
            $table->foreignId('technician_id')->nullable()->constrained('technicians')->onDelete('set null'); // Relación con la tabla 'technicians', establece a null si se elimina
        
            $table->string('title'); // Título del ticket
            $table->text('description'); // Descripción del ticket
            $table->enum('status', ['abierto', 'en proceso', 'resuelto', 'cerrado'])->default('abierto'); // Estado del ticket (por defecto es 'abierto')
            $table->enum('priority', ['alta', 'media', 'baja'])->default('media'); // Prioridad del ticket (por defecto es 'media')
        
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
        Schema::dropIfExists('tickets');
    }
}
