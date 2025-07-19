<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagoSeeder extends Seeder
{
    public function run()
    {
        // Solo inserta si la tabla de metodos_pago está vacía
        if (DB::table('metodos_pago')->count() === 0) {
            DB::table('metodos_pago')->insert([
                ['meto_cod' => 'EFE', 'medo_detale' => 'Efectivo'],
                ['meto_cod' => 'TRA', 'medo_detale' => 'Transferencia'],
                ['meto_cod' => 'TAR', 'medo_detale' => 'Tarjeta'],
                ['meto_cod' => 'PAY', 'medo_detale' => 'PayPal'],
            ]);
        } else {
            $this->command->info('Métodos de pago ya existentes. Saltando la inserción.');
        }
    }
}