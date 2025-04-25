<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckIfUserIsActive
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;

        if (!$user instanceof \App\Models\User) {
            Log::info("check user fail:", $user);
            Auth::logout();
            return redirect()->route('login');
        }

        if ($user->isKing() || $user->isPlayer() && $user->is_active === 0) {
            Auth::logout();

            Log::info("User {$user->email} attempted to log in while inactive.");

            session()->flash('error', 'Your account is inactive and requires approval to log in.');

            return redirect()->route('login');
        }
    }
}
