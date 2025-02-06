<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelCharacteristicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_characteristics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('models_id')->constrained('models')->onDelete('cascade'); // Relación con el modelo
            $table->foreignId('characteristic_id')->constrained('characteristics')->onDelete('cascade'); // Relación con la característica
            $table->string('value'); // Valor de la característica (ej., '16GB', 'Intel i5', 'mecánico')
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
        Schema::dropIfExists('model_characteristics');
    }
}
