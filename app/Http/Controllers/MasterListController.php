<?php

namespace App\Http\Controllers;

use App\Models\AccessRequest;
use App\Models\DukeLevel;
use App\Models\GameData;
use App\Services\BuildingNeedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MasterListController extends Controller
{
    public function show()
    {
        return view('master-list.show');
    }

    public function getSoretedUsers(BuildingNeedService $buildingNeedService)
    {
        $user = Auth::user();

        if (!$user instanceof \App\Models\User) {
            Auth::logout();
            return redirect()->route('login');
        }

        $records = GameData::select([
            'user_id',
            'castle_name',
            'castle_level',
            'range_level',
            'stables_level',
            'barracks_level',
            'duke_badges',
            'target_building',
            'target_level',
            'updated_at',
        ])
            ->with('user:id,name,email,role')
            ->whereHas('user', function ($q) {
                $q->whereIn('role', ['player', 'king']);
            })->get();

        $data = $records->map(function ($member) use ($buildingNeedService) {
            return [
                'user_id' => $member->user_id,
                'name' => $member->user->name,
                'email' => $member->user->email,
                'castle_name' => $member->castle_name,
                'castle_level' => $member->castle_level,
                'range_level' => $member->range_level,
                'stables_level' => $member->stables_level,
                'barracks_level' => $member->barracks_level,
                'duke_badges' => $member->duke_badges,
                'target_building' => $member->target_building,
                'target_level' => $member->target_level,
                'updated_at' => $member->updated_at->format('Y-m-d H:i:s'),
                'alliance' => AccessRequest::where('email', $member->user->email)->where('status', 'approved')->first()->alliance ?? null,
                'overall_level' => min([
                    $member->castle_level,
                    $member->range_level,
                    $member->stables_level,
                    $member->barracks_level,
                ]),
                'castle_needed' => in_array($member->target_building, ['castle', 'overall']) ? $buildingNeedService->getBuildingLevelNeed('castle', $member->castle_level, $member->target_level) : 0,
                'stables_needed' => in_array($member->target_building, ['stables', 'overall']) ? $buildingNeedService->getBuildingLevelNeed('stables', $member->stables_level, $member->target_level) : 0,
                'barracks_needed' => in_array($member->target_building, ['barracks', 'overall']) ? $buildingNeedService->getBuildingLevelNeed('barracks', $member->barracks_level, $member->target_level) : 0,
                'range_needed' => in_array($member->target_building, ['range', 'overall']) ? $buildingNeedService->getBuildingLevelNeed('range', $member->range_level, $member->target_level) : 0,
                'total_needed' => $buildingNeedService->calculateTotalNeeded($member, $member->target_level),
            ];
        });

        // Sort entire collection by total_needed BEFORE pagination
        $sorted = $data->sortBy('total_needed')->values();

        return DataTables::of($sorted)->make(true);
    }
}
