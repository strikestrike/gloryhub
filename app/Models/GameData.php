<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameData extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'castle_level',
        'range_level',
        'stables_level',
        'barracks_level',
        'duke_badges',
    ];

    protected $casts = [];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Validation Rules
    public static function validationRules()
    {
        return [
            'castle_level' => 'required|integer|between:45,50',
            'range_level' => 'required|integer|between:45,50',
            'stables_level' => 'required|integer|between:45,50',
            'barracks_level' => 'required|integer|between:45,50',
            'duke_badges' => 'required|integer|min:0',
        ];
    }

    // Business Logic
    public function calculateDukesNeeded()
    {
        $calculator = app(\App\Services\DukeCalculator::class);
        return $calculator->totalDukesNeeded($this->user);
    }
}
