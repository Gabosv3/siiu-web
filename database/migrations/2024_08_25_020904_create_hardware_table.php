<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHardwareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hardware', function (Blueprint $table) {
            $table->id(); // Clave primaria: id
        
            $table->foreignId('category_id')->constrained('categories'); // Llave foránea con la tabla 'categorias'
            $table->foreignId('user_id')->nullable()->constrained('users'); // Dueño (si está asignada)
            $table->foreignId('departament_id')->nullable()->constrained('departaments'); // Ubicación (departamento)
            $table->foreignId('manufacturer_id')->constrained('manufacturers'); // Fabricante vinculado
            $table->foreignId('model_id')->constrained('models'); // Modelo vinculado al fabricante
        
            $table->string('name'); // Nombre del hardware
            $table->text('conflicts')->nullable(); // Conflictos (opcional)
            $table->string('status'); // Estado (asignada, nueva, etc.)
            $table->string('inventory_code'); // Código de inventario único
            $table->string('serial_number')->unique(); // Número de serie único
            $table->string('warranty_expiration_date')->nullable(); // Fecha de expiración de la garantiía (opcional)
            $table->string('barcode_path')->nullable(); // Notas (opcional)

            $table->timestamps(); // Timestamps para created_at y updated_at
            $table->softDeletes(); // Agrega la columna 'deleted_at'

            $table->unique(['category_id', 'inventory_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hardware');
    }
}
