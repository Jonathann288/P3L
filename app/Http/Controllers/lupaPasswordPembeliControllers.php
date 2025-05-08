<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class lupaPasswordPembeliControllers extends Controller
{
    public function showLinkFormPembeli()
    {
        return view("LupaPasswordPembeli.lupaPasswordPembeli");
    }

    public function lupaPasswordPembeliPost(Request $request)
    {
        $request->validate([
            'email_pembeli' => 'required|email|exists:pembeli,email_pembeli'
        ]);
    
        $token = Str::random(65);
    
        DB::table("password_forgot_pembeli_tabel")->insert([
            "email_pembeli" => $request->email_pembeli,
            "token" => $token,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        Mail::send("LupaPasswordPembeli.PesanLupaPasswordPembeli", ["token" => $token], function($message) use ($request){
            $message->to($request->email_pembeli);
            $message->subject("Reset Password Pembeli");
        });

        return redirect()->route('loginPembeli')->with('success', 'Berhasil mengirimkan link ke email anda.');
    }
}