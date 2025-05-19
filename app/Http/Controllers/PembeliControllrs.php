<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembeli;
use App\Models\TransaksiPenjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PembeliControllrs extends Controller
{
    public function showRegisterForm()
    {
        return view('registerPembeli'); // Pastikan view ini ada di resources/views/pembeli/register.blade.php
    }

    public function registerPembeli(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nomor_telepon_pembeli' => 'required|string|max:20',
            'email_pembeli' => 'required|string|email|max:255|unique:pembeli,email_pembeli',
            'password_pembeli' => 'required|string|min:8',
            'foto_pembeli' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'total_poin' => 'nullable|integer'
        ]);

        // Generate ID unik
        $lastPembeli = DB::table('pembeli')
            ->select('id')
            ->where('id', 'like', 'PB%')
            ->orderByDesc('id')
            ->first();

        $newNumber = $lastPembeli ? ((int) substr($lastPembeli->id, 2)) + 1 : 1;
        $newId = 'PB' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $foto_pembeli_path = null;
        if ($request->hasFile('foto_pembeli')) {
            $foto_pembeli_path = $request->file('foto_pembeli')->store('images', 'public');
        }

        $pembeli = Pembeli::create([
            'id' => $newId,
            'nama_pembeli' => $request->nama_pembeli,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nomor_telepon_pembeli' => $request->nomor_telepon_pembeli,
            'email_pembeli' => $request->email_pembeli,
            'password_pembeli' => Hash::make($request->password_pembeli),
            'foto_pembeli' => $foto_pembeli_path,
            'total_poin' => $request->total_poin ?? 0
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function show()
    {
        try {
            // Ambil data pembeli yang sedang login
            $pembeli = Auth::guard('pembeli')->user();

            // Return view dengan data pembeli
            return view('pembeli.profilPembeli', compact('pembeli'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showHistory()
    {
        try {
            // Ambil data pembeli yang sedang login
            $pembeli = Auth::guard('pembeli')->user();
            $transaksiPenjualan = $pembeli->transaksiPenjualan ?? collect();
            // Return view dengan data pembeli
            return view('pembeli.historyPembeli', compact('pembeli','transaksiPenjualan'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {   
        try{
            $request->validate([
                'id_pembeli' => 'required|exists:pembeli,id_pembeli',
                'nama_pembeli' => 'required|string|max:255',
                'email_pembeli' => 'required|email',
                'nomor_telepon_pembeli' => 'required',
                'tanggal_lahir' => 'required|date',
            ]);

            $pembeli = Pembeli::find($request->id_pembeli);

            if (!$pembeli) {
                return redirect()->back()->with('error', 'Data Pembeli tidak ditemukan!');
            }

            $pembeli->nama_pembeli = $request->nama_pembeli;
            $pembeli->email_pembeli = $request->email_pembeli;
            $pembeli->nomor_telepon_pembeli = $request->nomor_telepon_pembeli;
            $pembeli->tanggal_lahir = $request->tanggal_lahir;
            $pembeli->save();

            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateFoto(Request $request, $id)
    {
        $request->validate([
            'foto_pembeli' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $pembeli = Pembeli::findOrFail($id);

        if ($request->hasFile('foto_pembeli')) {
            $imageName = time() . '.' . $request->foto_pembeli->extension();
            $request->foto_pembeli->move(public_path('uploads'), $imageName);
            $pembeli->foto_pembeli = 'uploads/' . $imageName;
        }

        $pembeli->save();
        
        return redirect()->route('pembeli.profilPembeli')->with('success', 'Foto profil berhasil diupdate.');
    }


}