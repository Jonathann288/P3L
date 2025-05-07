<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembeli;
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

        return redirect()->route('loginPembeli')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    public function show()
    {
        try {
            $pembeli = Auth::user();

            return response()->json([
                "status" => true,
                "message" => "Get Successful",
                "pembeli" => $pembeli
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Something went wrong",
                "pembeli" => $e->getMessage()
            ], 400);
        }
    }

    public function update(Request $request)
    {
        try {
            $pembeli = Auth::guard('pembeli')->user();
            if (!$pembeli) {
                return response()->json(['message' => 'Pembeli tidak ditemukan'], 404);
            }

            $validatedData = $request->validate([
                'nama_pembeli' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'nomor_telepon_pembeli' => 'required|string|max:20',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('profile_picture')) {
                // Hapus gambar lama jika bukan default
                if ($pembeli->foto_pembeli && $pembeli->foto_pembeli !== 'images/default.png') {
                    Storage::disk('public')->delete($pembeli->foto_pembeli);
                }

                $validatedData['foto_pembeli'] = $request->file('profile_picture')->store('images', 'public');
            }

            $pembeli->update($validatedData);

            return response()->json([
                'pembeli' => $pembeli,
                'message' => 'Data pembeli berhasil diperbarui'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Terjadi kesalahan saat memperbarui data pembeli",
                "error" => $e->getMessage()
            ], 400);
        }
    }


}