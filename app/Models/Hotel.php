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

public function room(){
    return $this->hasMany(Room::class);
}

public function reservation(){
    return $this->hasMany(Reservation::class);
}
}
