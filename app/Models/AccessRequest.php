<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'kingdom',
        'alliance',
        'player_name',
        'email',
        'status',
        'token',
        'token_expires_at',
    ];
}
