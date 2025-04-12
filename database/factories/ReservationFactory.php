<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkIn = fake()->dateTimeBetween('now', '+1 month');
        $checkOut = (clone $checkIn)->modify('+'.rand(1, 7).' days');

        return [
        'check_in'=>$checkIn->format('Y-m-d'),
        'check_out'=> $checkOut->format('Y-m-d'),
        'number_guests'=>fake()->numberBetween(1,3),
        'price'=>fake()->numberBetween(100,600),
        ];
    }
}
