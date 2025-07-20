<?php

namespace App\Http\Middleware;

use App\Constants\Level;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                if ($user->id_level === Level::ADMINISTRATOR_ID) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->id_level === Level::PETUGAS_ID) {
                    return redirect()->route('petugas.dashboard');
                } elseif ($user->id_level === Level::PELANGGAN_ID) {
                    return redirect()->route('pelanggan.dashboard');
                }

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
