<?php

namespace App\Services;

use App\Models\Hotel;

class HotelService
{
    public static function getHotelsByCountry(?string $country)
    {
        return $country 
            ? Hotel::where('country', $country)->get() 
            : Hotel::all();
    }
    
    public static function abortIfHotelsIsEmpty($hotels)
    {
        if ($hotels->isEmpty()) {
            $places = Hotel::pluck('country');
            return response()->json([
                'error'=> 'We don’t have available hotels in that location',
                'message'=>'Check-out our available locations: ' . $places->join(', ')
            ], 404);
        }
        return null;
    }
    
}
?>