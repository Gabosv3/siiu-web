<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHardwareAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('hardware_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hardware_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('departament_id')->nullable(); // Agregando departament_id directamente
            $table->timestamps();

            $table->foreign('hardware_id')->references('id')->on('hardware')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('departament_id')->references('id')->on('departaments')->onDelete('cascade');
        });

        // Crear tabla pivote
        Schema::create('hardware_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hardware_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('hardware_id')->references('id')->on('hardware')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['hardware_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('hardware_user');
        Schema::dropIfExists('hardware_assignments');
    }
}
