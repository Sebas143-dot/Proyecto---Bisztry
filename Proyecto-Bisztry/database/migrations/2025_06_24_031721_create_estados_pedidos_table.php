<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('estados_pedidos', function (Blueprint $table) {
            $table->string('esta_cod', 3)->primary();
            $table->string('esta_detalle', 25)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estados_pedidos');
    }
};