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
        Schema::create('laborals', function (Blueprint $table) {
            $table->id();
            $table->string('activity');
            $table->string('profesion');
            $table->enum('condition',['Dependiente','Independiente']);
            $table->string('company')->default('N/D');
            $table->string('address')->default('N/D');
            $table->string('phone')->default('N/D');
            $table->decimal('salary',10,2);
            $table->date('start_at')->nullable();
            $table->foreignId('client_id')->nullable()->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

   
    public function down()
    {
        Schema::dropIfExists('laborals');
    }
};
