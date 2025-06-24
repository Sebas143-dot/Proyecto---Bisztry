<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('clie_id');
            $table->string('clie_nombre', 50)->nullable();
            $table->string('clie_apellido', 50)->nullable();
            $table->string('clie_email', 50)->nullable();
            $table->string('clie_telefono', 20)->nullable();
            $table->string('ciud_cod', 3)->nullable();
            $table->text('clie_direccion')->nullable();
            $table->date('clie_fecha_nac')->nullable();
            $table->timestamps();
            $table->foreign('ciud_cod')->references('ciud_cod')->on('ciudades');
        });
    }
    public function down() { Schema::dropIfExists('clientes'); }
};