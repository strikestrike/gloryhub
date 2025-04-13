<?php

namespace App\Http\Controllers;

use App\Models\DukeLevel;
use App\Models\GameData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MasterListController extends Controller
{
    public function show()
    {
        return view('master-list.show');
    }

    public function getSoretedUsers(Request $request)
    {
        $targetLevel = 50;

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
            ->addColumn('castle_level', function ($member) {
                return min([
                    $member->castle_level,
                    $member->range_level,
                    $member->stables_level,
                    $member->barracks_level,
                ]);
            })
            ->addColumn('duke_needed', function ($member) use ($targetLevel) {
                $castle_needed = $this->getBuildingLevelNeed('castle', $member->castle_level, $targetLevel);
                $stables_needed = $this->getBuildingLevelNeed('stables', $member->stables_level, $targetLevel);
                $barracks_needed = $this->getBuildingLevelNeed('barracks', $member->barracks_level, $targetLevel);
                $range_needed = $this->getBuildingLevelNeed('range', $member->range_level, $targetLevel);

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
