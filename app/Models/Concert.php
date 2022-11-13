<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    use HasFactory;

    protected $fillable = [
        'concert_id',
        'concert_name',
        'concert_date',
        'concert_time',
        'stage',
        'seat_capacity',
        'seat_number',
        'seat_level'
    ];

}
