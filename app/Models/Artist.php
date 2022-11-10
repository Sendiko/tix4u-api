<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Artist extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        "artist_fullname",
        "artis_stagename",
        "age",
        "gender",
        "music_genre",
        "username",
        "email"
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
