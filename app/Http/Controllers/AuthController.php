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

    // public function showLoginOrganisasi()
    // {
    //     return view('organisasi.loginOrganisasi');
    // }

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

    public function loginPembeli(Request $request)
    {
        $request->validate([
            'email_pembeli' => 'required|email',
            'password_pembeli' => 'required'
        ]);

        $pembeli = Pembeli::where('email_pembeli', $request->email_pembeli)->first();

        if (!$pembeli || !Hash::check($request->password_pembeli, $pembeli->password_pembeli)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $token = $pembeli->createToken('Personal Access Token', ['pembeli'])->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'pembeli' => $pembeli,
            'role' => 'pembeli',
            'token' => $token
        ]);
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
