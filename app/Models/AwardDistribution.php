<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardDistribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'award_type',
        'kingdom_level',
        'quantity',
        'awarded_at'
    ];

    protected $casts = [
        'awarded_at' => 'datetime'
    ];

    // Constants
    const AWARD_TYPES = [
        'CROWN',
        'CONQUEROR',
        'DEFENDER',
        'SUPPORT'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kingdom()
    {
        return $this->belongsTo(Kingdom::class);
    }

    // Query Scopes
    public function scopeForKingdomLevel($query, $level)
    {
        return $query->where('kingdom_level', $level);
    }
}
