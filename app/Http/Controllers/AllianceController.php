<?php

namespace App\Http\Controllers;

use App\Models\DukeLevel;
use App\Models\GameData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AllianceController extends Controller
{
    public function show()
    {
        return view('alliance.show');
    }

    public function getUpdatedMembers(Request $request)
    {
        $targetLevel = $request->input('targetLevel');
        $buildingType = $request->input('buildingType');

        $query = GameData::whereIn('user_id', auth()->user()->alliance->members->pluck('id'))
            ->select([
                'user_id',
                'castle_level',
                'range_level',
                'stables_level',
                'barracks_level',
                'duke_badges',
                'updated_at',
            ])
            ->with('user:id,name');

        return DataTables::of($query)
            ->addColumn('name', fn($member) => $member->user->name)
            ->addColumn('castle_needed', function ($member) use ($targetLevel, $buildingType) {
                return $buildingType === 'castle' || $buildingType === 'all'
                    ? $this->getBuildingLevelNeed('castle', $member->castle_level, $targetLevel)
                    : 0;
            })
            ->addColumn('stables_needed', function ($member) use ($targetLevel, $buildingType) {
                return $buildingType === 'stables' || $buildingType === 'all'
                    ? $this->getBuildingLevelNeed('stables', $member->stables_level, $targetLevel)
                    : 0;
            })
            ->addColumn('barracks_needed', function ($member) use ($targetLevel, $buildingType) {
                return $buildingType === 'barracks' || $buildingType === 'all'
                    ? $this->getBuildingLevelNeed('barracks', $member->barracks_level, $targetLevel)
                    : 0;
            })
            ->addColumn('range_needed', function ($member) use ($targetLevel, $buildingType) {
                return $buildingType === 'range' || $buildingType === 'all'
                    ? $this->getBuildingLevelNeed('range', $member->range_level, $targetLevel)
                    : 0;
            })
            ->addColumn('total_needed', function ($member) use ($targetLevel, $buildingType) {
                $castle_needed = $buildingType === 'castle' || $buildingType === 'all'
                    ? $this->getBuildingLevelNeed('castle', $member->castle_level, $targetLevel) : 0;

                $stables_needed = $buildingType === 'stables' || $buildingType === 'all'
                    ? $this->getBuildingLevelNeed('stables', $member->stables_level, $targetLevel) : 0;

                $barracks_needed = $buildingType === 'barracks' || $buildingType === 'all'
                    ? $this->getBuildingLevelNeed('barracks', $member->barracks_level, $targetLevel) : 0;

                $range_needed = $buildingType === 'range' || $buildingType === 'all'
                    ? $this->getBuildingLevelNeed('range', $member->range_level, $targetLevel) : 0;

                return max(
                    $castle_needed + $stables_needed + $barracks_needed + $range_needed - $member->duke_badges,
                    0
                );
            })
            ->make(true);
    }


    private function getBuildingLevelNeed($buildingType, $currentLevel, $targetLevel)
    {
        $needs = 0;

        $levels = DukeLevel::whereBetween('level', [$currentLevel + 1, $targetLevel])->get();

        foreach ($levels as $level) {
            $needs += $level->{$buildingType};
        }

        return max($needs, 0);
    }
}
