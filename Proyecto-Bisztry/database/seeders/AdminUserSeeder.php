<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Busca un usuario con este email, y si no existe, lo crea.
        User::firstOrCreate(
            ['email' => 'jdipialesi@utn.edu.ec'],
            [
                'name' => 'Admin',
                'password' => Hash::make('juan'),
            ]
        );
    }
}