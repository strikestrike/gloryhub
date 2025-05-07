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

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (!$user->gameData || $user->gameData->count() === 0) {
            return redirect()->route('game-data.edit');
        }

        if (!session()->has('selected_castle')) {
            $castles = $user->gameData()->get();

            if ($castles->count() === 1) {
                session(['selected_castle' => $castles->first()->id]);
                return redirect()->route('/');
            }

            if (!$request->routeIs('game-data.show_castles') && !$request->routeIs('logout')) {
                return redirect()->route('game-data.show_castles');
            }
        }

        return $next($request);
    }
}
