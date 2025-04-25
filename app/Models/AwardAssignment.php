<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AwardAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_data_id',
        'type',
        'kingdom_level',
        'position',
        'assigned_by'
    ];

    protected $casts = [
        'assigned_at' => 'datetime'
    ];

    public static function validationRules(): array
    {
        return [
            'game_data_id' => 'required|exists:game_data,id',
            'type' => 'required|in:' . implode(',', self::getTypes()),
            'kingdom_level' => 'required|integer|min:' . config('game.kingdom_levels.min') . '|max:' . config('game.kingdom_levels.max'),
            'position' => 'required|integer|min:0',
            'assigned_by' => 'required|exists:users,id'
        ];
    }

    public static function getTypes(): array
    {
        return config('game.award_types');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gameData(): BelongsTo
    {
        return $this->belongsTo(GameData::class);
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function scopeForKingdomLevel($query, int $level)
    {
        return $query->where('kingdom_level', $level);
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
