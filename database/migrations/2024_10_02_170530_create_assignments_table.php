<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technician_id')->constrained('technicians')->onDelete('cascade'); // Ajusta según tu relación
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade'); // Debe estar aquí
            $table->string('task'); // O el tipo de dato que estés utilizando
            $table->enum('status', ['pendiente', 'en progreso', 'finalizado'])->default('pendiente');
            $table->timestamp('initial_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignments');
    }
}
