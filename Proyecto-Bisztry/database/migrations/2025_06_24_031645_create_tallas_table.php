<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tallas', function (Blueprint $table) {
            $table->string('talla_cod', 1)->primary();
            $table->string('tall_detalle', 25)->nullable();
        });
    }
    public function down() { Schema::dropIfExists('tallas'); }
};