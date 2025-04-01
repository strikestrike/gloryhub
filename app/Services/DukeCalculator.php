<?php

namespace App\Services;

use App\Models\DukeLevel;
use App\Models\User;

class DukeCalculator
{
    public function calculateNeededDukes($currentLevel, $targetLevel, $buildingType)
    {
        return DukeLevel::whereBetween('level', [$currentLevel + 1, $targetLevel])
            ->sum($buildingType);
    }

    public function totalDukesNeeded(User $user)
    {
        $data = $user->gameData;

        return $this->calculateNeededDukes($data->castle_level, 50, 'castle')
            + $this->calculateNeededDukes($data->range_level, 50, 'range')
            + $this->calculateNeededDukes($data->stables_level, 50, 'stables')
            + $this->calculateNeededDukes($data->barracks_level, 50, 'barracks')
            - $data->duke_badges;
    }
}
