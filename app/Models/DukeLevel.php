<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DukeLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'castle',
        'range',
        'stables',
        'barracks'
    ];

    public $timestamps = false;

    // Query Scopes
    public function scopeForBuilding($query, $buildingType)
    {
        return $query->select('level', $buildingType);
    }

    // Helper Methods
    public static function getRequirements($level)
    {
        return static::firstWhere('level', $level);
    }
}
