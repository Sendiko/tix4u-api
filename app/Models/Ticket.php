<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number', 
        'concert_name',
        'concert_date',
        'concert_time', 
        'concert_address', 
        'name_of_artist', 
        'price', 
        'currency',
        'seat_number',
        'stage', 
    ];

}
