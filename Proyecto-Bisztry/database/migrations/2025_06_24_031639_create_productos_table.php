<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('productos', function (Blueprint $table) {
            $table->id('prod_cod');
            $table->unsignedBigInteger('cate_id')->nullable();
            $table->string('prod_nombre');
            $table->boolean('prod_estado')->default(true);
            $table->timestamps(); // Reemplaza prod_fecha_created
            $table->foreign('cate_id')->references('cate_id')->on('categorias');
        });
    }
    public function down() { Schema::dropIfExists('productos'); }
};