<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('maintenances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('equipment_id')->constrained('hardware');
        $table->foreignId('technician_id')->constrained('technicians');
        $table->date('maintenance_date');
        $table->text('description')->nullable();
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
        Schema::dropIfExists('maintenances');
    }
}
