<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id(); // ID único para cada registro de auditoría
            $table->morphs('auditable'); // auditable_id (id del modelo) y auditable_type (tipo de modelo: App\Models\User, App\Models\Producto, etc.)
            $table->string('event')->index(); // Tipo de evento: created, updated, deleted, restored
            $table->foreignId('user_id')->nullable()->index()->constrained('users'); // ID del usuario que realizó el cambio (puede ser nulo si no hay usuario logueado)
            $table->text('old_values')->nullable(); // Valores antiguos del registro (JSON)
            $table->text('new_values')->nullable(); // Nuevos valores del registro (JSON)
            $table->text('url')->nullable(); // URL desde donde se realizó el cambio
            $table->ipAddress('ip_address')->nullable(); // Dirección IP del usuario
            $table->string('user_agent', 1023)->nullable(); // User Agent del navegador
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audits');
    }
};
