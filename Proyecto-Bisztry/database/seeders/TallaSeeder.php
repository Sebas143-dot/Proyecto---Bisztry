<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TallaSeeder extends Seeder
{
    public function run()
    {
        DB::table('tallas')->insert([
            ['talla_cod' => 'S', 'tall_detalle' => 'Small'],
            ['talla_cod' => 'M', 'tall_detalle' => 'Medium'],
            ['talla_cod' => 'L', 'tall_detalle' => 'Large'],
            ['talla_cod' => 'X', 'tall_detalle' => 'Extra Large'],
        ]);
    }
}