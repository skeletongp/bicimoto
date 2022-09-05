<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name',75)->comment('Nombre del cliente');
            $table->string('lastname',75)->comment('Apellido del cliente');
            $table->string('fullname',175)->comment('Nombre completo');
            $table->string('email',100)->unique();
            $table->date('birthdate')->comment('Fecha de nacimiento del cliente');
            $table->enum('genre',['Masculino','Femenino'])->comment('Género del cliente');
            $table->string('nacionality',50)->comment('Nacionalidad del cliente');
            $table->enum('civil_status',['Soltero','Casado','Viudo','Unión Libre'])->comment('Estado civil del cliente');
            $table->string('address',255)->comment('Dirección del cliente');
            $table->string('phone',25);
            $table->string('cellphone',25);
            $table->string('cedula')->nullable();
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
        Schema::dropIfExists('contacts');
    }
};
