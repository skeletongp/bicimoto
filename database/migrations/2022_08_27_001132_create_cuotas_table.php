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
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->decimal('saldo');
            $table->date('fecha');
            $table->enum('status',['pendiente','pagado']);
            $table->decimal('interes');
            $table->decimal('capital');
            $table->decimal('debe');
            $table->decimal('mora')->nullable();
            $table->decimal('restante');
            $table->date('payed_at')->nullable();
            $table->string('periodo');
            $table->foreignId('invoice_id')->constrained();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('payment_id')->nullable()->constrained();
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
        Schema::dropIfExists('cuotas');
    }
};
