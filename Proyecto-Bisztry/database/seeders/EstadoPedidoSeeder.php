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
        // Definimos los estados iniciales que necesita la aplicación,
        // coincidiendo con la estructura de tu tabla.
        $estados = [
            ['esta_cod' => 'CAN', 'esta_detalle' => 'Cancelado'],
            ['esta_cod' => 'ENT', 'esta_detalle' => 'Entregado'],
            ['esta_cod' => 'ENV', 'esta_detalle' => 'Enviado'],
            ['esta_cod' => 'PEN', 'esta_detalle' => 'Pendiente'],
            ['esta_cod' => 'PRO', 'esta_detalle' => 'Procesando'],
        ];

        // Insertamos los estados en la tabla 'estados_pedidos'.
        // Usamos 'firstOrCreate' o similar no es tan directo con DB::table,
        // así que es mejor asegurarse de que la tabla esté vacía o usar un 'truncate'.
        // Por ahora, una simple inserción funcionará si la tabla está vacía.
        DB::table('estados_pedidos')->insert($estados);
    }
}