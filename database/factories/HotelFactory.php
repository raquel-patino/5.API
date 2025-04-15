<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    protected $model = Hotel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'name'=> fake()->city(). 'Hotel',
        'description'=> fake()->realText(200),
        'street_type'=> fake()->streetSuffix(),
        'street_name'=>fake()->streetName(),
        'number'=>fake()->numberBetween(0, 100),
        'postcode'=>fake()->postcode(),
        'city'=>fake()->city(),
        'country'=>fake()->country(),
        'telephone_number'=>fake()->phoneNumber(),
        ];
    }
}
