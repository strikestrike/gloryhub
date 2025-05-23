<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\AwardAssignment;
use App\Models\GameData;
use App\Models\User;
use App\Models\Setting;
use App\Services\BuildingNeedService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{

    public function dashboard(BuildingNeedService $buildingNeedService)
    {
        $user = Auth::user();

        if (!$user instanceof \App\Models\User) {
            Auth::logout();
            return redirect()->route('login');
        }

        $selectedCastleId = session('selected_castle');

        $userGameData = GameData::where('id', $selectedCastleId)
            ->select([
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
            ->first();

        $castleNeeded = $stablesNeeded = $barracksNeeded = $rangeNeeded = $totalNeeded = 0;

        if ($userGameData) {
            $target = $userGameData->target_building;
            $level = $userGameData->target_level;

            $castleNeeded = ($target !== 'castle' && $target !== 'overall') ? 0 :
                $buildingNeedService->getBuildingLevelNeed('castle', $userGameData->castle_level, $level);

            $stablesNeeded = ($target !== 'stables' && $target !== 'overall') ? 0 :
                $buildingNeedService->getBuildingLevelNeed('stables', $userGameData->stables_level, $level);

            $barracksNeeded = ($target !== 'barracks' && $target !== 'overall') ? 0 :
                $buildingNeedService->getBuildingLevelNeed('barracks', $userGameData->barracks_level, $level);

            $rangeNeeded = ($target !== 'range' && $target !== 'overall') ? 0 :
                $buildingNeedService->getBuildingLevelNeed('range', $userGameData->range_level, $level);

            $totalNeeded = $buildingNeedService->calculateTotalNeeded($userGameData);
        }

        $totalAwards = AwardAssignment::count();
        $kingdomLevels = config('game.kingdom_levels.max', 0);
        $unassignedAwards = AwardAssignment::whereNull('user_id')->count();
        $totalPlayers = User::whereIn('role', ['player', 'king'])->count();
        $playerCastles = GameData::where('user_id', $user->id)->get();

        return view('dashboard', [
            'totalAwards'       => $totalAwards,
            'kingdomLevels'     => $kingdomLevels,
            'unassignedAwards'  => $unassignedAwards,
            'totalPlayers'      => $totalPlayers,
            'castleNeeded'      => $castleNeeded,
            'stablesNeeded'     => $stablesNeeded,
            'barracksNeeded'    => $barracksNeeded,
            'rangeNeeded'       => $rangeNeeded,
            'totalNeeded'       => $totalNeeded,
            'castleName'        => $userGameData?->castle_name ?? '',
            'playerCastles'     => $playerCastles,
        ]);
    }

    public function destroyCastle($id)
    {
        $castle = GameData::findOrFail($id);

        if ($castle->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $castle->delete();

        $remainingCastles = GameData::where('user_id', Auth::id())->get();
        $selectedCastleId = session('selected_castle');

        if ($remainingCastles->count() === 1) {
            $newCastle = $remainingCastles->first();
            session(['selected_castle' => $newCastle->id]);

            return redirect()->route('/')->with('status', 'Castle deleted. New castle selected.');
        }

        if ($selectedCastleId == $id) {
            session()->forget('selected_castle');

            return redirect()->route('game-data.show_castles')->with('status', 'Castle deleted. Please select a new castle.');
        }

        return redirect()->route('/')->with('status', 'Castle deleted successfully.');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateAppName(Request $request): RedirectResponse
    {
        $request->validate([
            'app_name' => ['required', 'string', 'max:100'],
        ]);

        Setting::setValue('app_name', $request->input('app_name'));

        return back()->with('status', 'app-name-updated');
    }
}
