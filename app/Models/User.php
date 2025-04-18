<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'alliance_id',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function alliance()
    {
        return $this->belongsTo(Alliance::class);
    }

    public function gameData()
    {
        return $this->hasOne(GameData::class);
    }

    public function kingdom()
    {
        return $this->belongsTo(Kingdom::class);
    }

    public function awards()
    {
        return $this->hasMany(AwardAssignment::class);
    }

    // Helper Methods
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isKing()
    {
        return $this->role === 'king';
    }
}
