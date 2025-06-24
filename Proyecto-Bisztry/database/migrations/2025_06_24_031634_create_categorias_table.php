<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id('cate_id');
            $table->string('cate_detalle', 25)->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('categorias'); }
};