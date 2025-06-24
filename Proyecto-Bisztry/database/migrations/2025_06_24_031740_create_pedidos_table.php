<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id('pedi_id');
            $table->unsignedBigInteger('clie_id');
            $table->date('pedi_fecha')->nullable();
            $table->string('esta_cod', 3)->nullable();
            $table->string('ciud_cod', 3)->nullable();
            $table->text('pedi_direccion')->nullable();
            $table->date('pedi_fecha_envio')->nullable();
            $table->date('pedi_fecha_entrega')->nullable();
            $table->decimal('pedi_total', 10, 2)->nullable();
            $table->decimal('pedi_costo_envio', 8, 2)->nullable();
            $table->string('meto_cod', 3)->nullable();
            $table->unsignedBigInteger('serv_id')->nullable();
            $table->timestamps();

            $table->foreign('clie_id')->references('clie_id')->on('clientes')->onDelete('cascade');
            $table->foreign('esta_cod')->references('esta_cod')->on('estados_pedidos')->onDelete('set null');
            $table->foreign('ciud_cod')->references('ciud_cod')->on('ciudades')->onDelete('set null');
            $table->foreign('meto_cod')->references('meto_cod')->on('metodos_pago')->onDelete('set null');
            $table->foreign('serv_id')->references('serv_id')->on('servicios_entrega')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
};