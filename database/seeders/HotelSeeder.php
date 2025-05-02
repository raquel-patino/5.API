<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotel= new Hotel();

        $hotel->name= 'Luxury Laponia';
        $hotel->description= 'Ubicado en el corazón de Laponia, este hotel de lujo ofrece una experiencia invernal inigualable.
        Con elegantes cabañas de cristal, permite admirar la aurora boreal desde la comodidad de tu suite.';
        $hotel->street_type='Avenida';
        $hotel->street_name= 'Aurora Boreal';
        $hotel->number= 25;
        $hotel->postcode= '2345CD';
        $hotel->city='Rovaniemi';
        $hotel->country= 'Finlandia';
        $hotel->telephone_number= 358165432;

        $hotel->save();

        
        $hotel= new Hotel();

        $hotel->name= 'Luxury Perla Azul';
        $hotel->description= 'Sumérgete en un paraíso exclusivo en Maldivas, 
        donde lujosas villas flotantes te ofrecen vistas inigualables del océano. Disfruta de playas privadas, 
        gastronomía gourmet y un servicio 5 estrellas.';
        $hotel->street_type='Isla';
        $hotel->street_name= 'Perla Azul';
        $hotel->number= 7;
        $hotel->postcode= '27005';
        $hotel->city='Malé';
        $hotel->country= 'Maldivas';
        $hotel->telephone_number= 900785634;

        $hotel->save();
    }
}
