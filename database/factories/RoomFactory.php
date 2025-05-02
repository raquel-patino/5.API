<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roomTypes= ['double', 'superior','suite'];
        return [
            'type'=> $roomTypes[rand(0,2)],
            'price'=> fake()->numberBetween(100,200),
            'description'=>fake()->text(200),
        ];
    }
}
