<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'num_people' => $this->faker->numberBetween(1, 10),
            'total_price' => $this->faker->numberBetween(1000000, 10000000),
            'status' => $this->faker->randomElement(['upcoming', 'ongoing', 'completed', 'cancelled']),
            'admin_confirmed' => $this->faker->boolean(),
            'payment_status' => $this->faker->randomElement(['unpaid', 'deposit', 'paid', 'refunded']),
            'payment_method' => $this->faker->randomElement(['momo', 'vnpay', 'bank_transfer']),
            'note' => $this->faker->optional()->sentence(),
            'expires_at' => $this->faker->optional()->dateTimeBetween('now', '+7 days'),
        ];
    }
}
