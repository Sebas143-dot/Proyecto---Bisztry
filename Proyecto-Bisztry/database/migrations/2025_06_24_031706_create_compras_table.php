<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id('comp_id');
            $table->string('prov_ruc', 14)->nullable();
            $table->date('comp_feccha')->nullable();
            $table->decimal('comp_precio_total', 10, 2)->nullable();
            $table->string('comp_factura_num', 17)->nullable();
            $table->timestamps();
            
            $table->foreign('prov_ruc')->references('prov_ruc')->on('proveedores')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('compras');
    }
};