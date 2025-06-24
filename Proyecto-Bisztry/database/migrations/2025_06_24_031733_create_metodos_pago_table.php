<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->string('meto_cod', 3)->primary();
            $table->string('medo_detale', 25)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('metodos_pago');
    }
};