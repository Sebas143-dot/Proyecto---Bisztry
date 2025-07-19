<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('provincias', function (Blueprint $table) {
            $table->string('prov_cod', 3)->primary();
            $table->string('prov_nombre', 50)->nullable();
        });
    }
    public function down() { Schema::dropIfExists('provincias'); }
};