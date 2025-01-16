<?php

namespace Database\Factories;

use App\Models\Set;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Set>
 */
class SetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(), // ID único
            'name' => $this->faker->words(3, true), // Nombre del set
            'series' => $this->faker->words(2, true), // Serie a la que pertenece
            'printedTotal' => $this->faker->numberBetween(50, 300), // Total impreso
            'total' => $this->faker->numberBetween(50, 300), // Total de cartas
            'legalities' => [ // Legalidades en formato array
                'unlimited' => $this->faker->randomElement(['Legal', 'Banned']),
                'standard' => $this->faker->randomElement(['Legal', 'Banned']),
            ],
            'ptcgoCode' => strtoupper($this->faker->lexify('???')), // Código del set
            'releaseDate' => $this->faker->date('Y-m-d'), // Fecha de lanzamiento
            'updatedAt' => $this->faker->date('Y-m-d'), // Fecha de última actualización
            'images' => [ // Imágenes en formato array
                'logo' => $this->faker->imageUrl(200, 200, 'abstract', true, 'logo'),
                'symbol' => $this->faker->imageUrl(100, 100, 'abstract', true, 'symbol'),
            ],
        ];
    }
}
