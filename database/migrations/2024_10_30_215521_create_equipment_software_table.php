<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentSoftwareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_softwares', function (Blueprint $table) { // Cambiado a plural
            $table->id(); // Clave primaria
            $table->foreignId('hardware_id')->constrained('hardware')->onDelete('cascade'); // Relación con 'hardware'
            $table->foreignId('software_id')->constrained('softwares')->onDelete('cascade'); // Relación con 'softwares'
            $table->foreignId('license_id')->nullable()->constrained('licenses')->onDelete('set null'); // Relación opcional con 'licenses'
            $table->softDeletes();
            $table->timestamps(); // Timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_software');
    }
}
