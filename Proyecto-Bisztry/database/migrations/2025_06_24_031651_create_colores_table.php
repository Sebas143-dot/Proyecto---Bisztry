<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('colores', function (Blueprint $table) {
            $table->smallIncrements('color_id');
            $table->string('col_detalle', 25)->nullable();
        });
    }
    public function down() { Schema::dropIfExists('colores'); }
};