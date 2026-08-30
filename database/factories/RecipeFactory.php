<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recipe>
 */
class RecipeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'instructions' => fake()->paragraph(),
            'servings' => fake()->numberBetween(1, 8),
            'prep_minutes' => fake()->numberBetween(5, 45),
            'cook_minutes' => fake()->numberBetween(10, 90),
        ];
    }
}
