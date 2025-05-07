<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penitip;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenitipControllrs extends Controller
{
    public function registerPenitip(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'nomor_ktp' => 'required|string|max:255|unique:penitip,nomor_ktp',  // assuming 'penitips' table
            'email_penitip' => 'required|string|email|max:255|unique:penitip,email_penitip',
            'tanggal_lahir' => 'required|date',
            'password_penitip' => 'required|string|min:8',
            'nomor_telepon_penitip' => 'required|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'saldo_penitip' =>'nullable|float',
            'total_poin' => 'nullable|integer',
            'bagde' => 'nullable|string|max:255',
            'jumlah_penjualan' => 'nullable|integer',
            'rating_penitip' => 'nullable|float', // optional and image validation
        ]);
    
         // Generate ID baru
         $lastPenitip = DB::table('penitip')
         ->select('id')
         ->where('id', 'like', 'P%')
         ->orderByDesc('id')
         ->first();
 
        if ($lastPenitip) {
            $lastNumber = (int) substr($lastPenitip->id, 2); // Ambil angka setelah "pb"
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
    
        $newId = 'P' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $foto_profil_path = null;
        if ($request->hasFile('foto_profil')) {
            // Save the file to a directory and get the path
            $foto_profil_path = $request->file('foto_profil')->store('images', 'public');
        }
    
        // Simpan ke database
        $penitip = Penitip::create([
            'id' => $newId,
            'nama_penitip' => $request->nama_penitip,
            'nomor_ktp' => $request->nomor_ktp,
            'email_penitip' => $request->email_penitip,
            'tanggal_lahir' => $request-> tanggal_lahir,
            'password_penitip' => Hash::make($request->password_penitip),
            'nomor_telepon_penitip' => $request->nomor_telepon_penitip,
            'foto_profil' => $foto_profil_path,
            'saldo_penitip' => $request->saldo_penitip,
            'total_poin' => $request->total_poin,
            'bagde' => $request->bagde,
            'jumlah_penjualan' => $request->jumlah_penjualan,
            'rating_penitip' => $request->rating_penitip
        ]);
    
        return response()->json([
            'penitip' => $penitip,
            'message' => 'Penitip registered successfully'
        ], 201);
    }

    public function index()
    {
        $penitip = Penitip::all();
        return response()->json($penitip);
    }
    
    public function showLogin()
    {
        try{
            $penitip = Auth::user();

            return response()->json([
                "status" => true,
                "message" => "Get Successful",
                "penitip" => $penitip
            ],200);
        }
        catch(Exception $e){
            return response()->json([
                "status" => false,
                "message" => "Something went wrong",
                "penitip" => $e->getMessage()
            ], 400);
        }
    }

    public function show($id)
    {
        $penitip = Penitip::find($id);
        if (!$penitip) {
            return response()->json(['message' => 'Organisasi tidak ditemukan'], 404);
        }
        return response()->json($penitip);
    }

    public function update(Request $request, $id)
    {
        $penitip = Penitip::find($id);
        if (!$penitip) {
            return response()->json(['message' => 'Penitip tidak ditemukan'], 404);
        }
    
        $validateData = $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'nomor_telepon_penitip' => 'required|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Jika ada file gambar baru di-request
        if ($request->hasFile('foto_profil')) {
            // Hapus file lama jika bukan default
            if ($penitip->foto_profil && $penitip->foto_profil !== 'images/default.png') {
                \Storage::disk('public')->delete($penitip->foto_profil);
            }
    
            // Upload gambar baru
            $fotoBaru = $request->file('foto_profil')->store('images', 'public');
            $validateData['foto_profil'] = $fotoBaru;
        }
    
        $penitip->update($validateData);
    
        return response()->json([
            'penitip' => $penitip,
            'message' => 'Data penitip berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $penitip = Penitip::find($id);
        if (!$penitip) {
            return response()->json(['message' => 'Penitip tidak ditemukan'], 404);
        }

        $penitip->delete();
        return response()->json(['message' => 'Penitip berhasil dihapus']);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $results = Penitip::where('nama_penitip', 'LIKE', '%' . $keyword . '%')->get();

        return response()->json($results);
    }
    
}
