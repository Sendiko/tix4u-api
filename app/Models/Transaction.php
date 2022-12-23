<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'amount_of_ticket',
        'ticket_price',
        'total_price',
        'concert_name',
        'concert_address',
        'concert_date',
        'currency',
    ];

}
