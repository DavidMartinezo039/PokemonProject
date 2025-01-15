<?php

namespace Database\Factories;

use App\Models\Rarity;
use App\Models\Set;
use App\Models\Supertype;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'name' => $this->faker->words(2, true),
            'supertype_id' => Supertype::factory(),
            'level' => $this->faker->randomElement(['Basic', 'Stage 1', 'Stage 2']),
            'hp' => $this->faker->numberBetween(30, 250),
            'evolvesFrom' => $this->faker->randomElement([null, $this->faker->words(2, true)]),
            'evolvesTo' => [$this->faker->words(2, true)],
            'rules' => [$this->faker->sentence()],
            'ancientTrait' => ['name' => $this->faker->word, 'effect' => $this->faker->sentence()],
            'abilities' => [
                [
                    'name' => $this->faker->word,
                    'text' => $this->faker->sentence(),
                    'type' => $this->faker->randomElement(['Poke-Body', 'Poke-Power']),
                ],
            ],
            'attacks' => [
                [
                    'name' => $this->faker->word,
                    'damage' => $this->faker->numberBetween(10, 120),
                    'cost' => [$this->faker->randomElement(['Grass', 'Fire', 'Water'])],
                    'text' => $this->faker->sentence(),
                ],
            ],
            'weaknesses' => [
                ['type' => $this->faker->word, 'value' => $this->faker->randomElement(['x2', '-20'])],
            ],
            'resistances' => [
                ['type' => $this->faker->word, 'value' => $this->faker->randomElement(['-20', '-30'])],
            ],
            'retreatCost' => [$this->faker->randomElement(['Colorless', 'Darkness'])],
            'convertedRetreatCost' => $this->faker->numberBetween(0, 5),
            'set_id' => Set::factory(),
            'number' => $this->faker->unique()->numberBetween(1, 150),
            'artist' => $this->faker->name(),
            'rarity_id' => Rarity::factory(),
            'flavorText' => $this->faker->sentence(),
            'nationalPokedexNumbers' => [$this->faker->numberBetween(1, 898)],
            'legalities' => ['standard' => 'Legal', 'expanded' => 'Banned'],
            'regulationMark' => $this->faker->randomLetter(),
            'images' => [
                'large' => $this->faker->imageUrl(300, 300, 'pokemon', true, 'card_large'),
                'small' => $this->faker->imageUrl(150, 150, 'pokemon', true, 'card_small'),
            ],
            'tcgplayer' => [
                'url' => $this->faker->url,
                'prices' => [
                    'normal' => ['low' => $this->faker->randomFloat(2, 0.1, 10), 'high' => $this->faker->randomFloat(2, 10, 100)],
                    'holofoil' => ['low' => $this->faker->randomFloat(2, 0.5, 20), 'high' => $this->faker->randomFloat(2, 20, 200)],
                ],
            ],
            'cardmarket' => [
                'url' => $this->faker->url,
                'prices' => [
                    'averageSellPrice' => $this->faker->randomFloat(2, 0.5, 20),
                    'lowPrice' => $this->faker->randomFloat(2, 0.1, 5),
                    'trendPrice' => $this->faker->randomFloat(2, 1, 25),
                ],
            ],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
