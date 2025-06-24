<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedorSeeder extends Seeder
{
    public function run()
    {
        DB::table('proveedores')->insert([
            [
                'prov_ruc' => '1791234567001',
                'prov_nombre' => 'Textiles Andinos S.A.',
                'prov_contacto' => 'Carlos Andrade',
                'prov_telefono' => '022555666',
                'prov_email' => 'ventas@textilesandinos.com',
            ],
            [
                'prov_ruc' => '0998765432001',
                'prov_nombre' => 'Insumos Costeros Cia. Ltda.',
                'prov_contacto' => 'Mariana Velez',
                'prov_telefono' => '042333444',
                'prov_email' => 'contacto@insumoscosteros.com',
            ],
        ]);
    }
}