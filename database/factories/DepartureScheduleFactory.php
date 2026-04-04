<?php

namespace Database\Factories;

use App\Models\DepartureSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DepartureSchedule>
 */
class DepartureScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+6 months');
        $endDate = $this->faker->dateTimeBetween($startDate, $startDate->format('Y-m-d H:i:s') . ' +10 days');

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_people' => $this->faker->numberBetween(10, 50),
            'available_spots' => $this->faker->numberBetween(5, 20),
            'meeting_point' => $this->faker->address(),
            'notes' => $this->faker->optional()->sentence(),
            'status' => 'active',
        ];
    }
}
