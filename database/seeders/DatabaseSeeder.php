<?php

namespace Database\Seeders;

use App\Models\Room;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $users = User::factory()->count(3)->create();
    
        
        $hotels = Hotel::factory()->count(3)->create();
    
        
        foreach ($hotels as $hotel) {
            
            $rooms = Room::factory()->count(3)->create([
                'hotel_id' => $hotel->id
            ]);
    
            Reservation::factory()->count(2)->create([
                'hotel_id' => $hotel->id,
                'room_id' => $rooms->random()->id,
                'user_id' => $users->random()->id
            ]);
        }
    }
}
