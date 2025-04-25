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
        'is_active',
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
        return $this->hasMany(GameData::class);
    }

    public function kingdom()
    {
        return $this->belongsTo(Kingdom::class);
    }

    public function awards()
    {
        return $this->hasMany(AwardAssignment::class);
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isKing()
    {
        return $this->role === 'king';
    }

    public function isPlayer()
    {
        return $this->role === 'player';
    }

    public function isActive()
    {
        return $this->is_active === 1;
    }
}
