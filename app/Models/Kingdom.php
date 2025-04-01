<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kingdom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subscription_level',
        'current_king_id'
    ];

    // Relationships
    public function alliances()
    {
        return $this->hasMany(Alliance::class);
    }

    public function king()
    {
        return $this->belongsTo(User::class, 'current_king_id');
    }

    // Helper Methods
    public function activeAlliance()
    {
        return $this->alliances()->whereHas('members', function ($query) {
            $query->where('role', 'king');
        })->first();
    }
}
