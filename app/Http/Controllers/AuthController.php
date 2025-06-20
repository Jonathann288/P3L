<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Organisasi;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\PasswordResetToken;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{

    public function loginGabungan(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Coba login sebagai Pegawai
        $pegawai = Pegawai::with('jabatan')->where('email_pegawai', $request->email)->first();
        if ($pegawai && Hash::check($request->password, $pegawai->password_pegawai)) {
            Auth::guard('pegawai')->login($pegawai);

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
                    return redirect()->route('login')->with('error', 'Jabatan tidak dikenal.');
            }
        }

        // Coba login sebagai Pembeli
        $pembeli = Pembeli::where('email_pembeli', $request->email)->first();
        if ($pembeli && Hash::check($request->password, $pembeli->password_pembeli)) {
            Auth::guard('pembeli')->login($pembeli);
            return redirect()->intended(route('pembeli.Shop-Pembeli'))->with('success', 'Login berhasil! Selamat datang di ReUseMart');
        }

        // Coba login sebagai Penitip
        $penitip = Penitip::where('email_penitip', $request->email)->first();
        if ($penitip && Hash::check($request->password, $penitip->password_penitip)) {
            Auth::guard('penitip')->login($penitip);
            return redirect()->intended(route('penitip.Shop-Penitip'))->with('success', 'Login berhasil! Selamat datang di ReUseMart Penitip yang kami hormati');
        }

        // Jika semua gagal
        return back()->withErrors(['email' => 'Email atau password salah']);
    }


    public function showLoginFormPegawai()
    {
        return view('login');  // Ganti dengan path view login yang sesuai
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
                return redirect()->route('login')->with('error', 'Jabatan tidak dikenal.');
        }
    }

    public function logoutPegawai()
    {
        Auth::guard('pegawai')->logout();

        // Hapus sesi pembeli jika diperlukan
        session()->forget('pegawai');

        // Redirect ke halaman login pembeli
        return redirect()->route('login');
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
        return redirect()->intended(route('organisasi.donasi-organisasi'))->with('success', 'Login berhasil! Selamat datang di Request Donasi ReUseMart');
    }

    public function logoutOrganisasi()
    {
        Auth::guard('organisasi')->logout();

        // Hapus sesi pembeli jika diperlukan
        session()->forget('organisasi');

        // Redirect ke halaman login pembeli
        return redirect()->route('loginOrganisasi')->with('success', 'Berhasil Logout! sampai jumpa dilain waktu');
    }

    public function showLoginForm()
    {
        return view('login');  // Tampilkan view loginPembeli.blade.php
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
        return redirect()->intended(route('pembeli.Shop-Pembeli'))->with('success', 'Login berhasil! Selamat datang di ReUseMart');  // Ganti dengan route tujuan setelah login
    }

    public function logoutPembeli()
    {
        // Menggunakan guard pembeli untuk logout
        Auth::guard('pembeli')->logout();

        // Hapus sesi pembeli jika diperlukan
        session()->forget('pembeli');

        // Redirect ke halaman login pembeli
        return redirect()->route('login')->with('success', 'Berhasil Logout! sampai jumpa dilain waktu');
    }


    public function showLoginFormPenitip()
    {
        return view('login');
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
        return redirect()->intended(route('penitip.Shop-Penitip'))->with('success', 'Login berhasil! Selamat datang di ReUseMart Penitip yang kami Hormati');
    }

    public function logoutPenitip()
    {
        // Menggunakan guard pembeli untuk logout
        Auth::guard('penitip')->logout();

        // Hapus sesi pembeli jika diperlukan
        session()->forget('penitip');

        // Redirect ke halaman login pembeli
        return redirect()->route('login')->with('success', 'Berhasil Logout! sampai jumpa dilain waktu');
    }

    public function forgotPassword()
    {
        return view('LupaPassword.forgotPassword');
    }

    public function forgotPasswordAct(Request $request)
    {
        $customMessages = [
            'email.exists' => 'Email tidak terdaftar.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
        ];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], $customMessages);

        $validator->after(function ($validator) use ($request) {
            $email = $request->email;

            $exists = DB::table('penitip')->where('email_penitip', $email)->exists()
                || DB::table('pembeli')->where('email_pembeli', $email)->exists()
                || DB::table('organisasi')->where('email_organisasi', $email)->exists();

            if (!$exists) {
                $validator->errors()->add('email', 'Email tidak terdaftar di sistem.');
            }
        });

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $token = \Str::random(60);

        PasswordResetToken::updateOrCreate(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
            ]
        );

        Log::info("Menyimpan token reset", [
            'email' => $request->email,
            'token' => $token
        ]);


        Mail::to($request->email)->send(new ResetPasswordMail($token));

        // Kirim email reset (di sini hanya simulasi redirect)
        return redirect()->route('forgotPassword')->with('status', 'Link reset password telah dikirim ke email Anda.');
    }
    public function validasiForgotPasswordAct(Request $request)
    {
        // Log awal untuk memastikan fungsi terpanggil
        Log::info('Form reset password diterima', ['token' => $request->token]);

        // Validasi password
        $customMessages = [
            'password.required' => 'Password tidak boleh kosong.',
            'password.min' => 'Password minimal 6 karakter.',
        ];

        $request->validate([
            'password' => 'required|min:8',
        ], $customMessages);

        // Cari token di tabel password_reset_tokens
        $token = PasswordResetToken::where('token', $request->token)->first();

        if (!$token || !$token->email) {
            Log::warning('Token tidak valid atau tidak memiliki email', ['token' => $request->token]);
            return redirect()->route('login')->with('error', 'Token tidak valid atau sudah kedaluwarsa.');
        }

        $email = $token->email;

        // Pastikan email berupa string
        if (!is_string($email)) {
            Log::error('Email dari token bukan string', ['email' => $email]);
            return redirect()->route('login')->with('error', 'Format email tidak valid.');
        }

        // Coba update password di salah satu dari 3 tabel
        $updated = false;

        if (DB::table('penitip')->where('email_penitip', $email)->exists()) {
            $affected = DB::table('penitip')
                ->where('email_penitip', $email)
                ->update(['password_penitip' => Hash::make($request->password)]);
            Log::info("RESET password penitip", ['email' => $email, 'rows' => $affected]);
            $updated = true;
        } elseif (DB::table('pembeli')->where('email_pembeli', $email)->exists()) {
            $affected = DB::table('pembeli')
                ->where('email_pembeli', $email)
                ->update(['password_pembeli' => Hash::make($request->password)]);
            Log::info("RESET password pembeli", ['email' => $email, 'rows' => $affected]);
            $updated = true;
        } elseif (DB::table('organisasi')->where('email_organisasi', $email)->exists()) {
            $affected = DB::table('organisasi')
                ->where('email_organisasi', $email)
                ->update(['password_organisasi' => Hash::make($request->password)]);
            Log::info("RESET password organisasi", ['email' => $email, 'rows' => $affected]);
            $updated = true;
        }

        if (!$updated) {
            Log::warning("Email tidak ditemukan di ketiga tabel", ['email' => $email]);
            return redirect()->route('login')->with('error', 'Email tidak ditemukan.');
        }

        // Hapus token setelah berhasil reset
        $token->delete();
        Log::info('Token berhasil dihapus setelah reset', ['email' => $email]);

        // Redirect ke halaman signin dengan pesan sukses
        return redirect()->route('login')->with('status', 'Password berhasil diperbarui.');
    }
    public function validasiForgotPassword(Request $request, $token)
    {
        $getToken = PasswordResetToken::where('token', $token)->first();
        if (!$getToken) {
            return redirect()->route('login')->with('failed', 'Token tidak valid');
        }
        return view('LupaPassword.validasiToken', compact('token'));
    }

}