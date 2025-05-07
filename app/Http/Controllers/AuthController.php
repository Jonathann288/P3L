<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;
use App\Models\Organisasi;
use App\Models\Pembeli;
use App\Models\Penitip;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginPegawai(Request $request)
    {
        $request->validate([
            'email_pegawai' => 'required|email',
            'password_pegawai' => 'required'
        ]);

        $pegawai = Pegawai::with('jabatan')->where('email_pegawai', $request->email_pegawai)->first();

        if (!$pegawai || !Hash::check($request->password_pegawai, $pegawai->password_pegawai)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        // Tambah validasi jabatan
        if (!$pegawai->jabatan) {
            return response()->json(['message' => 'Akun tidak memiliki jabatan yang valid'], 403);
        }

        $token = $pegawai->createToken('Personal Access Token', [$pegawai->jabatan->nama_jabatan])->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'pegawai' => $pegawai,
            'role' => $pegawai->jabatan->nama_jabatan,
            'token' => $token
        ]);
    }

    public function showLoginOrganisasi()
    {
        return view('loginOrganisasi');
    }

    public function loginOrganisasi(Request $request)
    {
        $request->validate([
            'emailOrganisasi' => 'required|email',
            'passwordOrganisasi' => 'required',
        ]);

        $organisasi = Organisasi::where('email_organisasi', $request->emailOrganisasi)->first();

        if ($organisasi && Hash::check($request->passwordOrganisasi, $organisasi->password_organisasi)) {
            // Login sukses: simpan data ke session
            session(['organisasi' => $organisasi]);

            return redirect()->route('dashboardOrganisasi');
        }

        return back()->withErrors(['emailOrganisasi' => 'Email atau password salah'])->withInput();
    }

    public function logoutOrganisasi()
    {
        session()->forget('organisasi');
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
            return back()->withErrors(['email_pembeli' => 'Email atau password salah.']);
        }

        // Login pembeli menggunakan Auth
        Auth::login($pembeli);

        // Redirect ke halaman setelah login berhasil
        return redirect()->intended('/shop');  // Ganti dengan URL tujuan setelah login berhasil
    }

    public function loginPenitip(Request $request)
    {
        $request->validate([
            'email_penitip' => 'required|email',
            'password_penitip' => 'required'
        ]);

        $penitip = Penitip::where('email_penitip', $request->email_penitip)->first();

        if (!$penitip || !Hash::check($request->password_penitip, $penitip->password_penitip)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $token = $penitip->createToken('Personal Access Token', ['penitip'])->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'penitip' => $penitip,
            'role' => 'penitip',
            'token' => $token
        ]);
    }

}
