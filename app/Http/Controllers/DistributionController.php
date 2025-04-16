<?php

namespace App\Http\Controllers;

use App\Models\AwardAssignment;
use App\Models\GameData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DistributionController extends Controller
{
    public function show()
    {
        return view('distribution.index');
    }

    public function getDistributionData(Request $request)
    {
        $level = (int) $request->input('kingdomLevel', 1);
        $awards = config('game.kingdom_awards')[$level] ?? [];

        $user = Auth::user();

        if (!$user instanceof \App\Models\User) {
            Auth::logout();
            return redirect()->route('login');
        }

        if ($user->isSuperAdmin()) {
            $members = GameData::with('user:id,name')
                ->get()
                ->map(function ($member) {
                    return [
                        'user_id' => $member->user_id,
                        'name' => $member->user->name,
                        'total_needed' => $this->calculateDukesNeeded($member),
                    ];
                })
                ->sortBy('total_needed')
                ->values()
                ->all();
        } else {
            $members = GameData::whereIn('user_id', $user->alliance->members->pluck('id'))
                ->with('user:id,name')
                ->get()
                ->map(function ($member) {
                    return [
                        'user_id' => $member->user_id,
                        'name' => $member->user->name,
                        'total_needed' => $this->calculateDukesNeeded($member),
                    ];
                })
                ->sortBy('total_needed')
                ->values()
                ->all();
        }

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
                            $potentialUserId = $members[$memberIndex]['user_id'];
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
                    'user_id' => $assigned ? $assigned->user_id : $defaultUserId,
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
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:' . implode(',', AwardAssignment::getTypes()),
            'kingdom_level' => 'required|integer|min:' . config('game.kingdom_levels.min') . '|max:' . config('game.kingdom_levels.max'),
            'position' => 'required|integer|min:0',
        ]);

        $userId = $request->input('user_id');
        $type = $request->input('type');
        $kingdomLevel = $request->input('kingdom_level');
        $position = $request->input('position');
        $assignedBy = Auth::id();

        AwardAssignment::where('type', $type)
            ->where('kingdom_level', $kingdomLevel)
            ->where('position', $position)
            ->delete();

        $assignment = AwardAssignment::create([
            'user_id' => $userId,
            'type' => $type,
            'kingdom_level' => $kingdomLevel,
            'position' => $position,
            'assigned_by' => $assignedBy,
            'assigned_at' => now(),
        ]);

        Log::info("Award '{$type}' [pos {$position}] assigned to user {$userId} at level {$kingdomLevel} by {$assignedBy}");

        return response()->json(['success' => true, 'assignment_id' => $assignment->id]);
    }

    public function saveBulkAssignments(Request $request)
    {
        $validated = $request->validate([
            'kingdom_level' => 'required|integer',
            'assignments' => 'required|array',
            'assignments.*.type' => 'required|string',
            'assignments.*.user_id' => 'required|exists:users,id',
            'assignments.*.position' => 'required|integer',
        ]);

        $kingdomLevel = $validated['kingdom_level'];
        $assignerId = auth()->id();

        AwardAssignment::where('kingdom_level', $kingdomLevel)->delete();

        foreach ($validated['assignments'] as $assignment) {
            AwardAssignment::create([
                'type' => strtolower($assignment['type']),
                'user_id' => $assignment['user_id'],
                'position' => $assignment['position'],
                'kingdom_level' => $kingdomLevel,
                'assigned_by' => $assignerId,
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

    private function calculateDukesNeeded($member)
    {
        $targetLevel = config('game.max_level');

        $castleNeeded = $this->getBuildingLevelNeed('castle', $member->castle_level, $targetLevel);
        $rangeNeeded = $this->getBuildingLevelNeed('range', $member->range_level, $targetLevel);
        $stablesNeeded = $this->getBuildingLevelNeed('stables', $member->stables_level, $targetLevel);
        $barracksNeeded = $this->getBuildingLevelNeed('barracks', $member->barracks_level, $targetLevel);

        return max(
            $castleNeeded + $rangeNeeded + $stablesNeeded + $barracksNeeded - $member->duke_badges,
            0
        );
    }

    private function getBuildingLevelNeed($buildingType, $currentLevel, $targetLevel)
    {
        return \App\Models\DukeLevel::whereBetween('level', [$currentLevel + 1, $targetLevel])
            ->sum($buildingType);
    }
}
