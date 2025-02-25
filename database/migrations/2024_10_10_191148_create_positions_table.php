<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shelf_id'); // Relación con los estantes
            $table->unsignedBigInteger('hardware_id')->nullable(); // Relación con hardware
            $table->string('estado')->default('empty'); // Estado de la posición (vacía o ocupada)
            $table->foreign('shelf_id')->references('id')->on('shelves')->onDelete('cascade');
            $table->foreign('hardware_id')->references('id')->on('hardware')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('positions');
    }
}
