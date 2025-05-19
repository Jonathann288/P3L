<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckJabatan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $jabatan
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $jabatan)
    {
        // Ambil data user dari guard pegawai
        $pegawai = Auth::guard('pegawai')->user();

        // Cek apakah user punya jabatan yang cocok
        if ($pegawai && $pegawai->jabatan && $pegawai->jabatan->nama_jabatan === $jabatan) {
            return $next($request);
        }

        // Jika tidak cocok, arahkan ke halaman 403 (Forbidden)
        return redirect()->route('login'); // Atau bisa redirect ke halaman lain sesuai keinginan
    }
}
