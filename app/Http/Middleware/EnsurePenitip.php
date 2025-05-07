<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsurePenitip
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user() instanceof \App\Models\Penitip) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized: Only Penitip can access this route'], 401);
    }
}
