<?php

namespace App\Http\Controllers;

use App\Models\AccessRequest;
use App\Models\AwardAssignment;
use App\Models\GameData;
use App\Services\BuildingNeedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DistributionController extends Controller
{
    public function show()
    {
        return view('distribution.index');
    }

    public function getDistributionData(Request $request, BuildingNeedService $buildingNeedService)
    {
        $level = (int) $request->input('kingdomLevel', 1);
        $awards = config('game.kingdom_awards')[$level] ?? [];

        $user = Auth::user();

        if (!$user instanceof \App\Models\User) {
            Auth::logout();
            return redirect()->route('login');
        }

        $members = GameData::select([
            'id',
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
            ->with('user:id,name,email')->get()->map(function ($member) use ($buildingNeedService) {
                $alliance = AccessRequest::where('email', $member->user->email)
                    ->where('status', 'approved')
                    ->first()->alliance ?? null;

                return [
                    'game_data_id' => $member->id,
                    'user_id' => $member->user_id,
                    'alliance' => $alliance,
                    'name' => $member->user->name,
                    'castle_name' => $member->castle_name,
                    'total_needed' => $buildingNeedService->calculateTotalNeeded($member),
                ];
            })->sortBy('total_needed')->values()->all();

        $existingAssignments = AwardAssignment::where('kingdom_level', $level)->get();

        $awardDistribution = [];
        $assignedUserIds = [];
        $memberIndex = 0;

        foreach ($awards as $awardType => $count) {
            $typeLower = strtolower($awardType);

            for ($position = 0; $position < $count; $position++) {
                $assigned = $existingAssignments
                    ->where('type', $typeLower)
                    ->where('position', $position)
                    ->first();

                $defaultUserId = null;

                if ($existingAssignments->isEmpty()) {
                    if ($typeLower !== 'crown') {
                        while ($memberIndex < count($members)) {
                            $potentialUserId = $members[$memberIndex]['game_data_id'];
                            $memberIndex++;

                            if (!in_array($potentialUserId, $assignedUserIds)) {
                                $defaultUserId = $potentialUserId;
                                $assignedUserIds[] = $defaultUserId;
                                break;
                            }
                        }
                    }
                }

                $awardDistribution[] = [
                    'award_type' => ucfirst($typeLower),
                    'type' => $typeLower,
                    'game_data_id' => $assigned ? $assigned->game_data_id : (($user->isSuperAdmin() || $user->isKing()) ? $defaultUserId : null),
                    'position' => $position,
                ];
            }
        }

        return response()->json([
            'awards' => $awardDistribution,
            'players' => $members,
            'has_saved' => $existingAssignments->isNotEmpty(),
        ]);
    }

    public function saveAssignment(Request $request)
    {
        $request->validate([
            'game_data_id' => 'required|exists:game_data,id',
            'type' => 'required|in:' . implode(',', AwardAssignment::getTypes()),
            'kingdom_level' => 'required|integer|min:' . config('game.kingdom_levels.min') . '|max:' . config('game.kingdom_levels.max'),
            'position' => 'required|integer|min:0',
        ]);

        $gameDataId = $request->input('game_data_id');
        $type = $request->input('type');
        $kingdomLevel = $request->input('kingdom_level');
        $position = $request->input('position');
        $assignedBy = Auth::id();

        $gameData = GameData::findOrFail($gameDataId);
        $userId = $gameData->user_id;

        AwardAssignment::where('type', $type)
            ->where('kingdom_level', $kingdomLevel)
            ->where('position', $position)
            ->delete();

        $assignment = AwardAssignment::create([
            'user_id' => $userId,
            'game_data_id' => $gameDataId,
            'type' => $type,
            'kingdom_level' => $kingdomLevel,
            'position' => $position,
            'assigned_by' => $assignedBy,
            'assigned_at' => now(),
        ]);

        return response()->json(['success' => true, 'assignment_id' => $assignment->id]);
    }

    public function saveBulkAssignments(Request $request)
    {
        $user = Auth::user();

        if (!$user instanceof \App\Models\User) {
            Auth::logout();
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'kingdom_level' => 'required|integer',
            'assignments' => 'required|array',
            'assignments.*.type' => 'required|string',
            'assignments.*.game_data_id' => 'required|exists:game_data,id',
            'assignments.*.position' => 'required|integer',
        ]);

        $kingdomLevel = $validated['kingdom_level'];

        AwardAssignment::where('kingdom_level', $kingdomLevel)->delete();

        foreach ($validated['assignments'] as $assignment) {
            $gameData = GameData::findOrFail($assignment['game_data_id']);
            $userId = $gameData->user_id;

            AwardAssignment::create([
                'type' => strtolower($assignment['type']),
                'user_id' => $userId,
                'game_data_id' => $assignment['game_data_id'],
                'position' => $assignment['position'],
                'kingdom_level' => $kingdomLevel,
                'assigned_by' => $user->id,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function resetAllAssignments(Request $request)
    {
        $validated = $request->validate([
            'kingdom_level' => 'required|integer',
        ]);

        AwardAssignment::where('kingdom_level', $validated['kingdom_level'])->delete();

        return response()->json(['success' => true]);
    }
}
