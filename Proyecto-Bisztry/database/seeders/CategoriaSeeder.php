<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        DB::table('categorias')->insert([
            ['cate_detalle' => 'Camisetas'],
            ['cate_detalle' => 'Sudaderas'],
            ['cate_detalle' => 'Gorras'],
            ['cate_detalle' => 'Pantalones'],
            ['cate_detalle' => 'Accesorios'],
        ]);
    }
}