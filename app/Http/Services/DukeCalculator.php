<?php

namespace App\Services;

use App\Models\DukeLevel;
use App\Models\User;

class DukeCalculator
{
    public function totalDukesNeeded(User $user)
    {
        $data = $user->gameData;
        $requirements = DukeLevel::where('level', '>', $data->castle_level)
            ->orderBy('level')
            ->get();

        return $requirements->sum(function ($level) {
            return $level->castle + $level->stables + $level->barracks + $level->range;
        }) - $data->duke_badges;
    }
}
