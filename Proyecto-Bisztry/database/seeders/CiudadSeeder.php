<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CiudadSeeder extends Seeder
{
    public function run()
    {
        // Solo inserta si la tabla de ciudades está vacía
        if (DB::table('ciudades')->count() === 0) {
            DB::table('ciudades')->insert([
                // Pichincha
                ['ciud_cod' => '171', 'ciud_nombre' => 'Quito', 'prov_cod' => '17'],
                ['ciud_cod' => '172', 'ciud_nombre' => 'Sangolquí', 'prov_cod' => '17'],
                // Guayas
                ['ciud_cod' => '091', 'ciud_nombre' => 'Guayaquil', 'prov_cod' => '09'],
                ['ciud_cod' => '092', 'ciud_nombre' => 'Durán', 'prov_cod' => '09'],
                ['ciud_cod' => '093', 'ciud_nombre' => 'Samborondón', 'prov_cod' => '09'],
                // Azuay
                ['ciud_cod' => '011', 'ciud_nombre' => 'Cuenca', 'prov_cod' => '01'],
                // Imbabura
                ['ciud_cod' => '101', 'ciud_nombre' => 'Ibarra', 'prov_cod' => '10'],
                ['ciud_cod' => '102', 'ciud_nombre' => 'Otavalo', 'prov_cod' => '10'],
                // Tungurahua
                ['ciud_cod' => '181', 'ciud_nombre' => 'Ambato', 'prov_cod' => '18'],
                // Manabí
                ['ciud_cod' => '131', 'ciud_nombre' => 'Manta', 'prov_cod' => '13'],
                ['ciud_cod' => '132', 'ciud_nombre' => 'Portoviejo', 'prov_cod' => '13'],
            ]);
        } else {
            $this->command->info('Ciudades ya existentes. Saltando la inserción.');
        }
    }
}