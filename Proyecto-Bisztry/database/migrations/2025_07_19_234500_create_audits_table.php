<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event');
            $table->morphs('auditable'); // auditable_id, auditable_type
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->nullableMorphs('user'); // <-- ¡ESTA ES LA LÍNEA CRÍTICA: user_id, user_type!
            $table->string('url')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 1023)->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('audits');
    }
};