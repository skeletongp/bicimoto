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
        Schema::create('crediticios', function (Blueprint $table) {
            $table->id();
            $table->decimal('state',10,2)->comment('Valor en inmuebles');
            $table->decimal('muebles',10,2)->comment('Valor en muebles');
            $table->decimal('rent',10,2)->comment('Rentas que paga');
            $table->decimal('hipoteca',10,2)->comment('Hipoteca que paga');
            $table->decimal('loans',10,2)->comment('Préstamos que paga');
            $table->decimal('others',10,2)->comment('Otros');
            $table->decimal('bank_value',10,2)->comment('Valores en el banco');
            $table->string('bank')->comment('Banco principal');
            $table->foreignId('client_id')->constrained();
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
        Schema::dropIfExists('crediticios');
    }
};
