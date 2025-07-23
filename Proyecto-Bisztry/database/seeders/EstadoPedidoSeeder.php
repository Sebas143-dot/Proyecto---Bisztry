<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoPedidoSeeder extends Seeder
{
    public function run()
    {
        // Solo inserta si la tabla de estados_pedidos está vacía
        if (DB::table('estados_pedidos')->count() === 0) {
            DB::table('estados_pedidos')->insert([
                ['esta_cod' => 'PEN', 'esta_detalle' => 'Pendiente'],   // Código corto
                ['esta_cod' => 'PRO', 'esta_detalle' => 'Procesando'],  // Código corto
                ['esta_cod' => 'ENV', 'esta_detalle' => 'Enviado'],     // Código corto
                ['esta_cod' => 'ENT', 'esta_detalle' => 'Entregado'],   // Código corto
                ['esta_cod' => 'CAN', 'esta_detalle' => 'Cancelado'],   // Código corto
            ]);
        } else {
            $this->command->info('Estados de pedido ya existentes. Saltando la inserción.');
        }
    }
}