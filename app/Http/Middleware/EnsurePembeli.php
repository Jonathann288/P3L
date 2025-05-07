<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePembeli
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('pembeli')->check()) {
            return $next($request);
        }
    
        return redirect()->route('loginPembeli')->with('message', 'Anda harus login sebagai Pembeli untuk mengakses halaman ini.');
    }
}

