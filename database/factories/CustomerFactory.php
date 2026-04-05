<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fullname' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'birthdate' => $this->faker->date(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'id_number' => $this->faker->unique()->numerify('##########'),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
