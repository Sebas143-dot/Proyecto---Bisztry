<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {
        $metodos = [
            // CORRECCIÓN: Se cambió la llave 'meto_detalle' a 'medo_detale' para coincidir con la migración.
            ['meto_cod' => 'EFE', 'medo_detale' => 'Efectivo'],
            ['meto_cod' => 'TAR', 'medo_detale' => 'Tarjeta de Crédito/Débito'],
            ['meto_cod' => 'TRA', 'medo_detale' => 'Transferencia Bancaria'],
            ['meto_cod' => 'PAY', 'medo_detale' => 'PayPal'],
        ];

        // Usamos upsert para evitar errores si los datos ya existen.
        // 1er argumento: Los datos.
        // 2do argumento: La columna única para buscar duplicados.
        // 3er argumento: Las columnas que se deben actualizar si se encuentra un duplicado.
        DB::table('metodos_pago')->upsert($metodos, ['meto_cod'], ['medo_detale']);
    }
}
