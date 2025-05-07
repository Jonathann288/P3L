<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePembeli
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user() instanceof \App\Models\Pembeli) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized: Only Pembeli can access this route'], 401);
    }
}
