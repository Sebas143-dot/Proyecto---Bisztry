<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detalles_pedidos', function (Blueprint $table) {
            $table->id('det_pedi_id');
            $table->unsignedBigInteger('var_id');
            $table->unsignedBigInteger('pedi_id');
            $table->integer('cantidad');
            $table->timestamps();

            $table->foreign('var_id')->references('var_id')->on('variantes_prod')->onDelete('cascade');
            $table->foreign('pedi_id')->references('pedi_id')->on('pedidos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalles_pedidos');
    }
};