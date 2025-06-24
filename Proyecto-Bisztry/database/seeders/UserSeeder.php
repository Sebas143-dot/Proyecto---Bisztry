<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creamos tu usuario administrador
        DB::table('users')->insert([
            'name' => 'Juan Administrador',
            'email' => 'jdipialesi@utn.edu.ec', // Tu email del login
            'password' => Hash::make('password'), // La contraseña será 'password', pero encriptada
            'is_admin' => true, // Marcamos esta cuenta como administrador
            'email_verified_at' => now(), // Verificamos el email inmediatamente
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}