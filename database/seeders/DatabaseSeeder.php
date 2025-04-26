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
        
            $this->call([
                AdminUserSeeder::class,
                HotelSeeder::class,
                RoomSeeder::class,
                PassportSeeder::class,
            ]);
        }
    
}
