<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id(); // Clave primaria: id
            // Clave foránea a la tabla 'softwares'
            $table->foreignId('software_id')->constrained('softwares')->onDelete('cascade');
            // Clave foránea opcional a la tabla 'equipos'
            $table->foreignId('hardware_id')->nullable()->constrained('hardware')->onDelete('set null');
            $table->string('license_key')->unique(); // Clave única de la licencia
            $table->date('purchase_date')->nullable(); // Fecha de compra de la licencia (opcional)
            $table->date('expiration_date')->nullable(); // Fecha de expiración de la licencia (opcional)
            $table->string('status')->default('active'); // Estado de la licencia (por defecto es 'active')
            
            $table->timestamps(); // Timestamps para created_at y updated_at
            $table->softDeletes(); // Agrega la columna 'deleted_at' para eliminaciones lógicas
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('licenses');
    }
}
