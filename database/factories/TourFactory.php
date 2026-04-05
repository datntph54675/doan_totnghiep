<?php

namespace Database\Factories;

use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tour>
 */
class TourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'policy' => $this->faker->paragraph(),
            'supplier' => $this->faker->company(),
            'image' => $this->faker->imageUrl(),
            'price' => $this->faker->numberBetween(1000000, 10000000),
            'duration' => $this->faker->numberBetween(1, 10),
            'status' => 'active',
        ];
    }
}
