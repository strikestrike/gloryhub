<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRegistrationTokenIsPresent
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->route('token')) {
            return redirect('/questionnaire');
        }

        return $next($request);
    }
}
