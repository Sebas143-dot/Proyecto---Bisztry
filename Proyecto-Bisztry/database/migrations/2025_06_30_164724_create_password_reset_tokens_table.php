<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Este método crea la tabla para almacenar los tokens de reseteo de contraseña.
     */
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            // El email del usuario que solicitó el reseteo. Es la clave primaria.
            $table->string('email')->primary();
            // El token seguro y único que se envía por email.
            $table->string('token');
            // La fecha en que se creó el token, para que pueda expirar.
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     * Este método elimina la tabla si necesitas deshacer la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
