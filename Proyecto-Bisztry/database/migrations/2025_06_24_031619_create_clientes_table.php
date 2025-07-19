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
            $table->string('clie_identificacion', 20)->unique(); // ¡COLUMNA AÑADIDA Y ÚNICA!
                                                                  // 20 es una longitud razonable para identificaciones.
            $table->string('clie_email', 100)->unique();         // Cambiado a 100 y ÚNICO
            $table->string('clie_telefono', 20)->unique();       // Añadido ÚNICO
            $table->string('ciud_cod', 3)->nullable();
            $table->text('clie_direccion')->nullable();
            $table->date('clie_fecha_nac')->nullable();
            $table->timestamps();
            
            // Asegúrate de que la tabla 'ciudades' exista y 'ciud_cod' sea su clave primaria
            // y que la migración de ciudades se ejecute ANTES que esta.
            $table->foreign('ciud_cod')->references('ciud_cod')->on('ciudades');
        });
    }

    public function down() {
        Schema::dropIfExists('clientes');
    }
};