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
        Schema::create('recursos', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->string('name')->comment('Identificador en texto');
            $table->text('description')->comment('Detalles opcionales del recurso, para facilitar su búsqueda')->nullable();
            $table->decimal('cost')->comment('Costo base del recurso (valor de compra)');
            $table->decimal('cant')->comment('Cantidad del recurso');
            $table->foreignId('store_id')->comment('Tienda a la que pertenece el recurso')->constrained();
            $table->foreignId(('place_id'))->comment('Sucursal a la que pertenedce el material')->constrained();
            $table->foreignId('unit_id')->comment('Unidad con que se gestiona el recurso')->constrained();
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
        Schema::dropIfExists('materials');
    }
};
