<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GameDataMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user instanceof \App\Models\User) {
            Auth::logout();
            return redirect()->route('login');
        }

        if (!$user->isSuperAdmin() && !$user->gameData) {
            return redirect()->route('game-data.edit');
        }
        return $next($request);
    }
}
