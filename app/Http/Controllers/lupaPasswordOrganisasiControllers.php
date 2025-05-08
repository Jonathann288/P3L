<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class lupaPasswordOrganisasiControllers extends Controller
{
    public function showLinkForm()
    {
        return view("LupaPasswordOrg.lupaPasswordOrganisasi");
    }

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

        Mail::send("LupaPasswordOrg.PesanLupaPasswordOrganisasi", ["token" => $token], function($message) use ($request){
            $message->to($request->email_organisasi);
            $message->subject("Reset Password Organisasi");
        });

        return redirect()->route('loginOrganisasi')->with('success', 'Berhasil mengirimkan link ke email anda.');
    }
}