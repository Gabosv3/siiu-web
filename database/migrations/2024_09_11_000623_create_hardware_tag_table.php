<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHardwareTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hardware_tag', function (Blueprint $table) {
            $table->id();

            $table->foreignId('hardware_id')->constrained('hardware'); // Llave foránea con la tabla 'hardware'
            $table->foreignId('tag_id')->constrained('tags'); // Llave foránea con la tabla 'tags'
            
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
        Schema::dropIfExists('hardware_tag');
    }
}
