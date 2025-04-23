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
    public function edit(Request $request)
    {
        $user = Auth::user();
        if (!$user instanceof \App\Models\User) {
            Auth::logout();
            return redirect()->route('login');
        }
        
        if ($request->query('fresh')) {
            $gameData = new GameData();
        } else {
            $selectedCastleId = session('selected_castle');
            $gameData = $selectedCastleId
                ? $user->gameData()->find($selectedCastleId)
                : new GameData();
        }

        $dukeLevels = DukeLevel::all()->keyBy('level');

        return view('game-data.edit', [
            'gameData' => $gameData,
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

        $creatingNew = $request->input('fresh');

        if ($creatingNew || !session('selected_castle')) {
            $newGameData = $user->gameData()->create($validated);
            session(['selected_castle' => $newGameData->id]);
        } else {
            $gameDataId = session('selected_castle');
            $gameData = GameData::where('user_id', $user->id)->find($gameDataId);

            if ($gameData) {
                $gameData->update($validated);
            } else {
                $newGameData = $user->gameData()->create($validated);
                session(['selected_castle' => $newGameData->id]);
            }
        }

        return redirect()->route('/')
            ->with('success', 'Game data saved successfully!');
    }


    public function showCastles()
    {
        $user = Auth::user();
        $castles = $user->gameData;

        if ($castles->isEmpty()) {
            return redirect()->route('game-data.edit');
        }

        return view('game-data.show_castles', ['castles' => $castles]);
    }

    public function selectCastle(Request $request)
    {
        $user = Auth::user();
        if (!$user instanceof \App\Models\User) {
            Auth::logout();
            return redirect()->route('login');
        }

        $castle = $user->gameData()->find($request->castle_id);

        if ($castle) {
            session(['selected_castle' => $castle->id]);
            return redirect()->route('/');
        }

        return redirect()->route('game-data.show_castles')->withErrors(['castle_id' => 'Invalid castle selected.']);
    }
}
