<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePenitip
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('penitip')->check()) {
            return $next($request);
        }
    
        return redirect()->route('login')->with('message', 'Anda harus login sebagai Pembeli untuk mengakses halaman ini.');
    }
}


