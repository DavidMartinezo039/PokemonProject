<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSetFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'image' => $this->faker->imageUrl(150, 150, 'abstract', true, 'logo'),
            'user_id' => User::factory(),
        ];
    }
}
