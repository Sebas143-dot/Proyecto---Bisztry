<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detalles_compras', function (Blueprint $table) {
            $table->id('det_con_id');
            $table->unsignedBigInteger('comp_id');
            $table->unsignedBigInteger('var_id');
            $table->integer('comp_cantidad');
            $table->decimal('comp_precio_unit', 8, 2);
            $table->timestamps();

            $table->foreign('comp_id')->references('comp_id')->on('compras')->onDelete('cascade');
            $table->foreign('var_id')->references('var_id')->on('variantes_prod')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalles_compras');
    }
};