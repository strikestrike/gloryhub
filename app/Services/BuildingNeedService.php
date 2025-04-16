<?php

namespace App\Services;

use App\Models\DukeLevel;

class BuildingNeedService
{
    public function getBuildingLevelNeed(string $buildingType, int $currentLevel, int $targetLevel): int
    {
        return DukeLevel::whereBetween('level', [$currentLevel + 1, $targetLevel])
            ->sum($buildingType);
    }

    public function calculateTotalNeeded($gameData): int
    {
        $targetLevel = $gameData->target_level ?? config('game.max_level');

        $castle = $this->getBuildingLevelNeed('castle', $gameData->castle_level, $targetLevel);
        $range = $this->getBuildingLevelNeed('range', $gameData->range_level, $targetLevel);
        $stables = $this->getBuildingLevelNeed('stables', $gameData->stables_level, $targetLevel);
        $barracks = $this->getBuildingLevelNeed('barracks', $gameData->barracks_level, $targetLevel);

        return max($castle + $range + $stables + $barracks - $gameData->duke_badges, 0);
    }
}
