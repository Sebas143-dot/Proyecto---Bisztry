<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('ciudades', function (Blueprint $table) {
            $table->string('ciud_cod', 3)->primary();
            $table->string('ciud_nombre', 50)->nullable();
            $table->string('prov_cod', 3);
            $table->foreign('prov_cod')->references('prov_cod')->on('provincias');
        });
    }
    public function down() { Schema::dropIfExists('ciudades'); }
};