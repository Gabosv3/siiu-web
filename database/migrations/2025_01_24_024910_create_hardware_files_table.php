<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHardwareFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hardware_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hardware_id')->constrained('hardware')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location'); // Ruta del archivo
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
        Schema::dropIfExists('hardware_files');
    }
}
