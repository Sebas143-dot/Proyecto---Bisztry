<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoPedidoSeeder extends Seeder
{
    public function run()
    {
        DB::table('estados_pedidos')->insert([
            ['esta_cod' => 'PEN', 'esta__detalle' => 'Pendiente'],
            ['esta_cod' => 'PRO', 'esta__detalle' => 'Procesando'],
            ['esta_cod' => 'ENV', 'esta__detalle' => 'Enviado'],
            ['esta_cod' => 'ENT', 'esta__detalle' => 'Entregado'],
            ['esta_cod' => 'CAN', 'esta__detalle' => 'Cancelado'],
        ]);
    }
}