<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('variantes_prod', function (Blueprint $table) {
            $table->id('var_id');
            $table->string('talla_cod', 1)->nullable();
            $table->unsignedSmallInteger('color_id')->nullable();
            $table->unsignedBigInteger('prod_cod')->nullable();
            $table->string('sku', 25)->unique()->nullable();
            $table->integer('var_stok_actual')->default(0);
            $table->integer('var_stock_min')->default(0);
            $table->decimal('var_precio', 8, 2)->default(0);
            $table->timestamps();

            $table->foreign('talla_cod')->references('talla_cod')->on('tallas')->onDelete('set null');
            $table->foreign('color_id')->references('color_id')->on('colores')->onDelete('set null');
            $table->foreign('prod_cod')->references('prod_cod')->on('productos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('variantes_prod');
    }
};