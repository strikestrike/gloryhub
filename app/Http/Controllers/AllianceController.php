<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllianceController extends Controller
{
    public function show()
    {
        $users = User::where('alliance', Auth::user()->alliance)
            ->with('gameData')
            ->get();

        return view('alliance.show', compact('users'));
    }
}
