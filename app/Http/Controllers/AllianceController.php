<?php

namespace App\Http\Controllers;

use App\Models\DukeLevel;
use App\Models\GameData;
use App\Services\BuildingNeedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AllianceController extends Controller
{
    public function show()
    {
        return view('alliance.show');
    }

    public function getUpdatedMembers(BuildingNeedService $buildingNeedService)
    {
        $user = Auth::user();

        if (!$user instanceof \App\Models\User) {
            Auth::logout();
            return redirect()->route('login');
        }

        
        $query = GameData::where('user_id', $user->id)
            ->select([
                'user_id',
                'castle_level',
                'range_level',
                'stables_level',
                'barracks_level',
                'duke_badges',
                'updated_at',
                'target_building',
                'target_level',
            ])
            ->with('user:id,name');

        $dataTable = DataTables::of($query)
            ->addColumn('name', fn($member) => $member->user->name)
            ->addColumn('castle_needed', fn($m) => ($m->target_building !== 'castle' && $m->target_building !== 'overall') ? 0 : $buildingNeedService->getBuildingLevelNeed('castle', $m->castle_level, $m->target_level))
            ->addColumn('stables_needed', fn($m) => ($m->target_building !== 'stables' && $m->target_building !== 'overall') ? 0 : $buildingNeedService->getBuildingLevelNeed('stables', $m->stables_level, $m->target_level))
            ->addColumn('barracks_needed', fn($m) => ($m->target_building !== 'barracks' && $m->target_building !== 'overall') ? 0 : $buildingNeedService->getBuildingLevelNeed('barracks', $m->barracks_level, $m->target_level))
            ->addColumn('range_needed', fn($m) => ($m->target_building !== 'range' && $m->target_building !== 'overall') ? 0 : $buildingNeedService->getBuildingLevelNeed('range', $m->range_level, $m->target_level))
            ->addColumn('total_needed', fn($m) => $buildingNeedService->calculateTotalNeeded($m));

        $customSortMap = [];

        return $this->applyCustomSorting(request(), $dataTable, $customSortMap)->make(true);
    }
}
