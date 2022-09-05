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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('place_id')->constrained();
            $table->foreignId('invoice_id')->constrained();
            $table->integer('cuotas');
            $table->decimal('interes');
            $table->string('tipo')->nullable()->default('ND');
            $table->string('marca')->nullable()->default('ND');
            $table->string('modelo')->nullable()->default('ND');
            $table->string('color')->nullable()->default('ND');
            $table->string('chasis')->nullable()->default('ND');
            $table->string('year')->nullable()->default('ND');
            $table->string('placa')->nullable()->default('EN TRÁMITE');
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
        Schema::dropIfExists('contratos');
    }
};
