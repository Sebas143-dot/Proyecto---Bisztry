<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash; // ¡Asegúrate de añadir esta línea!
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password; // Mantén esta propiedad para Hash::make

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array // Cambié a public function definition(): array para coincidir con la convención de tipos de Laravel 9+
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'), // ¡Línea de la contraseña actualizada!
            'remember_token' => Str::random(10),
            'is_admin' => false, // ¡Añadido: los usuarios de la factory no serán admin!
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified(): static // Cambié a public function unverified(): static para coincidir con la convención de tipos de Laravel 9+
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}