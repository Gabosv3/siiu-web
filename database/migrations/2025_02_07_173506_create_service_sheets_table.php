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
            $table->date('date')->nullable(); // Permitir fechas nulas
            $table->foreignId('department_id')->constrained('departaments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('technician_id')->constrained('technicians')->onDelete('cascade');
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->foreignId('hardware_id')->nullable()->constrained('hardware')->onDelete('cascade');
            $table->json('supplies_data')->nullable(); // Almacenar insumos como JSON
            $table->text('description');
            $table->text('observations')->nullable();
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
