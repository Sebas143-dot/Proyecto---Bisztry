<?php

namespace Database\Factories;

use App\Models\Cliente; // Asegúrate de que este sea el namespace correcto de tu modelo Cliente
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cliente::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'clie_nombre' => fake()->firstName(),
            'clie_apellido' => fake()->lastName(),
            'clie_identificacion' => fake()->unique()->numerify('##########'), // 10 dígitos numéricos únicos
            'clie_direccion' => fake()->address(),
            'clie_telefono' => fake()->unique()->phoneNumber(),
            'clie_email' => fake()->unique()->safeEmail(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}