<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AccessRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public function showRegistrationFormFromToken($token)
    {
        $invitation = AccessRequest::where('token', $token)->first();

        if (!$invitation) {
            return redirect()->route('login')->withErrors(['token' => 'Invalid or expired registration token.']);
        }

        if ($invitation->expires_at && Carbon::parse($invitation->expires_at)->isPast()) {
            return redirect()->route('login')->withErrors(['token' => 'This registration token has expired.']);
        }

        if ($invitation->is_used) {
            return redirect()->route('login')->withErrors(['token' => 'This token has already been used.']);
        }

        return view('auth.register', [
            'token' => $token,
            'email' => $invitation->email,
            'kingdom' => $invitation->kingdom ?? null,
            'alliance' => $invitation->alliance ?? null,
            'player_name' => $invitation->player_name ?? null,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'token' => ['required', 'string'],
        ]);

        $invitation = AccessRequest::where('token', $request->token)->first();

        if (!$invitation || $invitation->is_used || ($invitation->expires_at && Carbon::parse($invitation->expires_at)->isPast())) {
            return redirect()->route('login')->withErrors(['token' => 'Invalid, expired, or already used token.']);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $invitation->is_used = true;
        $invitation->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
