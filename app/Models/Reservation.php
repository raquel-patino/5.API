<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Reservation extends Model
{
    protected $fillable=[
        'check_in',
        'check_out',
        'number_guests',
        'price',
        'user_id',
        'hotel_id',
        'room_id'
    ];

public function user(){
    return $this->belongsTo(User::class, 'user_id');

}

public function hotel(){
    return $this->belongsTo(Hotel::class);
}

public function room(){
    return $this->belongsTo(Room::class);
}


    
}

