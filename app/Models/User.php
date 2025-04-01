<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'alliance',
        'alliance_locked',
        'kingdom_id'
    ];

    protected $casts = [
        'alliance_locked' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    // Relationships
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
        return $this->hasMany(AwardDistribution::class);
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
