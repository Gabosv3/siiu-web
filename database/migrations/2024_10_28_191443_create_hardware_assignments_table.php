<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHardwareAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hardware_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hardware_id')->constrained()->onDelete('cascade'); // Llave foránea
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Llave foránea
            $table->timestamps();
            
            // Asegurarse de que solo haya una asignación por hardware
            $table->unique('hardware_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hardware_assignments');
    }
}
