<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable= [
        'name',
        'description',
        'street_type',
        'street_name',
        'number',
        'postcode',
        'city',
        'country',
        'telephone_number'

    ];

public function rooms(){
    return $this->hasMany(Room::class);
}

public function reservations(){
    return $this->hasMany(Reservation::class);
}
}
