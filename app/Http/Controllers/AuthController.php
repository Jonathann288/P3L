<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Organisasi;
use App\Models\Pembeli;
use App\Models\Penitip;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginFormPegawai()
    {
        return view('loginDashboard');  // Ganti dengan path view login yang sesuai
    }

    public function loginPegawai(Request $request)
    {
        $request->validate([
            'email_pegawai' => 'required|email',
            'password_pegawai' => 'required'
        ]);

        // Cari pegawai berdasarkan email
        $pegawai = Pegawai::with('jabatan')->where('email_pegawai', $request->email_pegawai)->first();

        // Cek apakah pegawai ada dan passwordnya cocok
        if (!$pegawai || !Hash::check($request->password_pegawai, $pegawai->password_pegawai)) {
            return back()->withErrors(['email_pegawai' => 'Email atau password salah']);
        }

        // Login menggunakan session
        Auth::guard('pegawai')->login($pegawai);

        // Arahkan ke halaman sesuai dengan jabatan
        switch ($pegawai->jabatan->nama_jabatan) {
            case 'Admin':
                return redirect()->route('admin.Dashboard');
            case 'Customer Service':
                return redirect()->route('CustomerService.DashboardCS');
            case 'Owner':
                return redirect()->route('owner.DashboardOwner');
            case 'Gudang':
                return redirect()->route('gudang.DashboardGudang');
            case 'Hunter':
                return redirect()->route('hunter.DashboardHunter');
            case 'Kurir':
                return redirect()->route('kurir.DashboardKurir');
            default:
                return redirect()->route('loginDashboard')->with('error', 'Jabatan tidak dikenal.');
        }
    }

    public function logoutPegawai()
    {
        Auth::guard('pegawai')->logout();
        
        // Hapus sesi pembeli jika diperlukan
        session()->forget('pegawai');

        // Redirect ke halaman login pembeli
        return redirect()->route('loginDashboard');
    }

    public function showLoginOrganisasi()
    {
        return view('loginOrganisasi');
    }

    public function loginOrganisasi(Request $request)
    {
        $request->validate([
            'email_organisasi' => 'required|email',
            'password_organisasi' => 'required',
        ]);
    
        // Mencari organisasi berdasarkan email
        $organisasi = Organisasi::where('email_organisasi', $request->email_organisasi)->first();
    
        if (!$organisasi || !\Hash::check($request->password_organisasi, $organisasi->password_organisasi)) {
            return back()->withErrors(['email_organisasi' => 'Email atau password salah'])->withInput();
        }
    
        // LOGIN menggunakan GUARD 'organisasi'
        Auth::guard('organisasi')->login($organisasi);
    
        // Redirect ke halaman setelah login berhasil
        return redirect()->intended(route('organisasi.donasi-organisasi'));
    }

    public function logoutOrganisasi()
    {
        Auth::guard('organisasi')->logout();
        
        // Hapus sesi pembeli jika diperlukan
        session()->forget('organisasi');

        // Redirect ke halaman login pembeli
        return redirect()->route('loginOrganisasi');
    }

    public function showLoginForm()
    {
        return view('loginPembeli');  // Tampilkan view loginPembeli.blade.php
    }

    // Proses login
    public function loginPembeli(Request $request)
    {
        // Validasi input form login
        $request->validate([
            'email_pembeli' => 'required|email',
            'password_pembeli' => 'required'
        ]);

        // Mencari pembeli berdasarkan email
        $pembeli = Pembeli::where('email_pembeli', $request->email_pembeli)->first();

        // Jika pembeli tidak ditemukan atau password salah
        if (!$pembeli || !Hash::check($request->password_pembeli, $pembeli->password_pembeli)) {
            return back()->withErrors(['email_pembeli' => 'Email atau password salah']);
        }

        // Login pembeli menggunakan guard 'pembeli'
        Auth::guard('pembeli')->login($pembeli);

        // Redirect ke halaman setelah login berhasil
        return redirect()->intended(route('pembeli.Shop-Pembeli'));  // Ganti dengan route tujuan setelah login
    }

    public function logoutPembeli()
    {
        // Menggunakan guard pembeli untuk logout
        Auth::guard('pembeli')->logout();
        
        // Hapus sesi pembeli jika diperlukan
        session()->forget('pembeli');

        // Redirect ke halaman login pembeli
        return redirect()->route('loginPembeli');
    }


    public function showLoginFormPenitip()
    {
        return view('loginPenitip');
    }

    public function loginPenitip(Request $request) 
    {
        $request->validate([
            'email_penitip' => 'required|email',
            'password_penitip' => 'required'
        ]);

        $penitip = Penitip::where('email_penitip', $request->email_penitip)->first();

        if (!$penitip || !Hash::check($request->password_penitip, $penitip->password_penitip)) {
            return back()->withErrors(['email_penitip' => 'Email atau password salah']);
        }

        // LOGIN menggunakan GUARD 'penitip'
        Auth::guard('penitip')->login($penitip);

        // Redirect ke halaman setelah login berhasil
        return redirect()->intended(route('penitip.Shop-Penitip'));
    }

        public function logoutPenitip()
    {
        // Menggunakan guard pembeli untuk logout
        Auth::guard('penitip')->logout();
        
        // Hapus sesi pembeli jika diperlukan
        session()->forget('penitip');

        // Redirect ke halaman login pembeli
        return redirect()->route('loginPenitip');
    }

}
