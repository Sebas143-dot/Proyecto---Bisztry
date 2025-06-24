<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->string('prov_ruc', 14)->primary();
            $table->string('prov_nombre', 50)->nullable();
            $table->string('prov_contacto', 50)->nullable();
            $table->string('prov_telefono', 10)->nullable();
            $table->string('prov_email', 50)->nullable();
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('proveedores'); }
};