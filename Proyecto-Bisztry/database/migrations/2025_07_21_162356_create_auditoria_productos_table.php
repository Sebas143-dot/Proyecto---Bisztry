<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditoria_productos', function (Blueprint $table) {
            $table->id('audi_id');
            $table->unsignedBigInteger('prod_cod_afectado'); // Para saber qué producto cambió
            $table->string('usuario_modificador')->default('sistema'); // Quién hizo el cambio
            $table->string('campo_modificado'); // Qué columna específica cambió
            $table->text('valor_antiguo')->nullable(); // Cuál era el valor antes del cambio
            $table->text('valor_nuevo')->nullable(); // Cuál es el nuevo valor
            $table->timestamp('fecha_modificacion')->useCurrent(); // Cuándo ocurrió el cambio
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria_productos');
    }
};