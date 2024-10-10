<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_securities', function (Blueprint $table) {
            $table->id(); // Clave primaria: id

            // Relación de clave foránea con la tabla 'users'
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Clave foránea que referencia a 'users', elimina registros asociados en cascada

            // Campos para autenticación de dos factores
            $table->boolean('google2fa_enable')->default(false); // Indica si Google 2FA está habilitado
            $table->string('google2fa_secret')->nullable(); // Clave secreta de Google 2FA (opcional)

            $table->timestamps(); // Timestamps para created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_securities');
    }
}
