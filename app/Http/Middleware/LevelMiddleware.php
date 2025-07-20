<?php

namespace App\Http\Middleware;

use App\Constants\Level;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $level)
    {
        if (Auth::check() && Auth::user()->id_level == $level) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
