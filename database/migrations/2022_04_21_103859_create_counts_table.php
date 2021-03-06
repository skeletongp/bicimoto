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
        Schema::create('counts', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->enum('origin',['debit','credit']);
            $table->enum('currency',['DOP','USD'])->default('DOP');
            $table->enum('type',['real','nominal']);
            $table->decimal('balance', 14,4)->comment('Balance de la cuenta en pesos');
            $table->decimal('balance_real', 14,4)->comment('Balance de la cuenta en su moneda')->nullable();
            $table->foreignId('count_main_id')->constrained();
            $table->foreignId('place_id')->constrained();
            $table->foreignId('store_id')->constrained()->on('moso_master.stores');
            $table->morphs('contable');
            $table->tinyInteger('borrable')->nullable();
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
        Schema::dropIfExists('counts');
    }
};
