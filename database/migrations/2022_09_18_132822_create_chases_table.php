<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('chasis', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('tipo')->nullable()->default('ND');
            $table->string('marca')->nullable()->default('ND');
            $table->string('modelo')->nullable()->default('ND');
            $table->string('color')->nullable()->default('ND');
            $table->string('chasis')->nullable()->default('ND');
            $table->string('year')->nullable()->default('ND');
            $table->string('placa')->nullable()->default('EN TRÁMITE');
            $table->foreignId('invoice_id')->nullable()->constrained();
            $table->foreignId('place_id')->nullable()->constrained();
            $table->foreignId('product_id')->constrained();
            $table->enum('status', ['Pendiente', 'Entregado', 'Cancelado'])->default('Pendiente');
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
        Schema::dropIfExists('chases');
    }
};
