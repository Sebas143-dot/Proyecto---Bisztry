<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Añadimos la columna después de 'password'. Será un booleano (true/false)
            // y por defecto, todos los nuevos usuarios no serán administradores.
            $table->boolean('is_admin')->default(false)->after('password');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};