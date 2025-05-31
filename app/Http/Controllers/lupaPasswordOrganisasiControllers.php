<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Hash;


class lupaPasswordOrganisasiControllers extends Controller
{
    // Menampilkan form lupa password
    public function showLinkForm()
    {
        return view("LupaPasswordOrg.lupaPasswordOrganisasi");
    }

    // Menangani proses pengiriman link reset password ke email
    public function lupaPasswordOrganisasiPost(Request $request)
    {
        $request->validate([
            'email_organisasi' => 'required|email|exists:organisasi,email_organisasi'
        ]);

        $token = Str::random(65);

        DB::table("password_forgot")->insert([
            "email_organisasi" => $request->email_organisasi,
            "token" => $token,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        // Kirim email dengan token
        Mail::send("LupaPasswordOrg.PesanLupaPasswordOrganisasi", ["token" => $token], function ($message) use ($request) {
            $message->to($request->email_organisasi);
            $message->subject("Reset Password Organisasi");
        });

        return redirect()->route('loginOrganisasi')->with('success', 'Berhasil mengirimkan link ke email anda.');
    }

    public function showResetPasswordForm($token)
    {
        return view('LupaPasswordOrg.resetPasswordOrganisasi', compact('token'));
    }

    public function resetPasswordOrganisasi(Request $request)
    {
        // Validasi data
        $request->validate([
            'password_organisasi' => 'required|string|min:8|confirmed',
            'token' => 'required|string',
        ]);

        // Cek apakah token valid
        $tokenData = DB::table('password_forgot')->where('token', $request->token)->first();

        if (!$tokenData) {
            return back()->withErrors(['token' => 'Token tidak valid']);
        }

        // Temukan organisasi berdasarkan email dari token
        $organisasi = Organisasi::where('email_organisasi', $tokenData->email_organisasi)->first();

        if (!$organisasi) {
            return back()->withErrors(['email' => 'Organisasi tidak ditemukan']);
        }


        // Hapus token setelah berhasil reset
        DB::table('password_forgot')->where('token', $request->token)->delete();

        return redirect()->route('loginOrganisasi')->with('success', 'Password berhasil diperbarui.');
    }
}