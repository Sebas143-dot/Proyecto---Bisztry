<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoPedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            ['esta_cod' => 'CAN', 'esta_detalle' => 'Cancelado'],
            ['esta_cod' => 'ENT', 'esta_detalle' => 'Entregado'],
            ['esta_cod' => 'ENV', 'esta_detalle' => 'Enviado'],
            ['esta_cod' => 'PEN', 'esta_detalle' => 'Pendiente'],
            ['esta_cod' => 'PRO', 'esta_detalle' => 'Procesando'],
        ];

        // Usamos upsert para insertar o actualizar, evitando errores de duplicados.
        // 1er argumento: Los datos.
        // 2do argumento: La columna única para buscar duplicados ('esta_cod').
        // 3er argumento: Las columnas que se deben actualizar si se encuentra un duplicado.
        DB::table('estados_pedidos')->upsert($estados, ['esta_cod'], ['esta_detalle']);
    }
}