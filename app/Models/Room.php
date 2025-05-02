<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
   use HasFactory;
   
 protected $fillable= [
    'type',
    'price',
    'description',
    'hotel_id'
 ];

 public function hotel(){
    return $this->belongsTo(Hotel::class);
 }
 
}
