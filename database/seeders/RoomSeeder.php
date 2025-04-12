<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $room = new Room;
        $room->type = 'Suite Aurora Boreal';
        $room->price = 350.00;
        $room->description = 'Suite de lujo con techo de cristal para admirar la aurora boreal en Laponia.';
        $room->hotel_id = 1;
        $room->save();
        
        $room = new Room;
        $room->type = 'Cabaña de Nieve';
        $room->price = 280.00;
        $room->description = 'Cabaña privada en medio del bosque nevado, equipada con chimenea y sauna.';
        $room->hotel_id = 1;
        $room->save();
        
        $room = new Room;
        $room->type = 'Habitación Deluxe de Hielo';
        $room->price = 400.00;
        $room->description = 'Habitación esculpida en hielo con muebles térmicos y vista panorámica de la nieve.';
        $room->hotel_id = 1;
        $room->save();
        
        $room = new Room;
        $room->type = 'Villa sobre el agua';
        $room->price = 500.00;
        $room->description = 'Villa privada sobre el mar con piscina infinita y acceso directo al océano.';
        $room->hotel_id = 2;
        $room->save();
        
        $room = new Room;
        $room->type = 'Bungalow en la playa';
        $room->price = 450.00;
        $room->description = 'Bungalow de lujo con terraza privada y acceso directo a la arena blanca.';
        $room->hotel_id = 2;
        $room->save();
        
        $room = new Room;
        $room->type = 'Suite Ocean View';
        $room->price = 600.00;
        $room->description = 'Suite exclusiva con jacuzzi en el balcón y vistas espectaculares del atardecer.';
        $room->hotel_id = 2;
        $room->save();


    }
}
