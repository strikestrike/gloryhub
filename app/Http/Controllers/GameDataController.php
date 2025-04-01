<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameDataController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'castle_level' => 'required|integer|between:45,50',
            'range_level' => 'required|integer|between:45,50',
            'stables_level' => 'required|integer|between:45,50',
            'barracks_level' => 'required|integer|between:45,50',
            'duke_badges' => 'required|integer|min:0'
        ]);

        $request->user()->gameData()->updateOrCreate([], $validated);

        return redirect()->route('alliance.view');
    }
}
