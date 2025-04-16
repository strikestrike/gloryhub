<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGameDataRequest;
use App\Models\DukeLevel;
use App\Models\GameData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GameDataController extends Controller
{
    public function edit()
    {
        $gameData = Auth::user()->gameData ?? new GameData();
        $alliance = Auth::user()->alliance;

        $dukeLevels = DukeLevel::all()->keyBy('level');

        return view('game-data.edit', [
            'gameData' => $gameData,
            'alliance' => $alliance ? $alliance->name : null,
            'maxLevel' => config('game.max_level'),
            'minLevel' => config('game.min_level'),
            'dukeLevels' => $dukeLevels,
        ]);
    }

    public function store(StoreGameDataRequest $request)
    {
        RateLimiter::attempt(
            'game-data-update:' . $request->ip(),
            5,
            function () {},
            60
        );

        $validated = $request->validated();

        $user = $request->user();

        // Check if the alliance is already set
        if ($user->alliance) {
            // Prevent updating the alliance if already set
            unset($validated['alliance']);
        } else {
            $allianceName = $validated['alliance'];
            $alliance = \App\Models\Alliance::firstOrCreate(
                ['name' => $allianceName],
                ['kingdom_id' => 1] // TODO: get kingdom id
            );

            $user->alliance_id = $alliance->id;
            $user->save();
        }

        $this->updateGameData($user, $validated);

        return redirect()->route('/')
            ->with('success', 'Game data updated successfully!');
    }

    private function updateGameData($user, array $validated): void
    {
        $user->gameData()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );
    }
}
