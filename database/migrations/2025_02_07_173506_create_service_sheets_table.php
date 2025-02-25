<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_sheets', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('department');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('technician_id')->constrained('users');
            $table->foreignId('hardware_id')->constrained('hardware');
            $table->string('inventory_number');
            $table->string('serial_number');
            $table->string('model');
            $table->string('status');
            $table->text('description');
            $table->text('observations')->nullable();
            $table->boolean('use_supply')->default(false);
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
        Schema::dropIfExists('service_sheets');
    }
}
