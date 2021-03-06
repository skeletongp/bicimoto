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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            
            $table->string('code',50)->comment('Código del cliente basado en tres cifra');
            $table->string('name',75)->comment('Nombre del negocio');
            $table->string('email',100)->unique();
            $table->string('address',255)->comment('Dirección del cliente');
            $table->string('phone',25);
            $table->string('rnc')->nullable();
            $table->decimal('limit', 14,4)->default(0)->comment('Crédito límite del cliente');
            $table->foreignId('store_id')->constrained()->on('moso_master.stores');
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
        Schema::dropIfExists('clients');
    }
};
