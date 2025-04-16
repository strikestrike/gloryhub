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
        $building = $gameData->target_building;

        $total = 0;

        if ($building === 'castle' || $building === 'overall') {
            $total += $this->getBuildingLevelNeed('castle', $gameData->castle_level, $targetLevel);
        }

        if ($building === 'range' || $building === 'overall') {
            $total += $this->getBuildingLevelNeed('range', $gameData->range_level, $targetLevel);
        }

        if ($building === 'stables' || $building === 'overall') {
            $total += $this->getBuildingLevelNeed('stables', $gameData->stables_level, $targetLevel);
        }

        if ($building === 'barracks' || $building === 'overall') {
            $total += $this->getBuildingLevelNeed('barracks', $gameData->barracks_level, $targetLevel);
        }

        return max($total - $gameData->duke_badges, 0);
    }
}
