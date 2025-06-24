<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('servicios_entrega', function (Blueprint $table) {
            $table->id('serv_id');
            $table->string('serv_nombre', 25)->nullable();
            $table->decimal('serv_costo', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servicios_entrega');
    }
};