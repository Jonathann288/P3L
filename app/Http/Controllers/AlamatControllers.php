<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alamat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AlamatControllers extends Controller
{
    // Menampilkan semua data alamat
    public function index()
    {
        // Ambil ID pembeli yang sedang login
        $pembeli = Auth::guard('pembeli')->user();
    
        // Ambil alamat milik pembeli tersebut
        $alamat = Alamat::where('id_pembeli', $pembeli->id_pembeli)->get();
    
        // Cek apakah pembeli sudah memiliki alamat
        if ($alamat->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat belum diisi.'
            ]);
        }
    
        // Jika alamat ditemukan, kembalikan datanya
        return response()->json([
            'success' => true,
            'data' => $alamat
        ]);
    }


    // Menampilkan form tambah alamat
    // public function create()
    // {
    //     return view('alamat.create');
    // }

    // Menyimpan data alamat baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_jalan' => 'required|string',
            'kode_pos' => 'required|integer',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'status_default' => 'required|string',
            'kabupaten' => 'required|string',
            'deskripsi_alamat' => 'required|string',
        ]);
    
        $lastAlamat = DB::table('alamat')
            ->select('id')
            ->where('id', 'like', 'ALT%')
            ->orderByDesc('id')
            ->first();
    
        if ($lastAlamat) {
            $lastNumber = (int) substr($lastAlamat->id, 3); // Ambil angka setelah "pb"
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
    
        $newId = 'ALT' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
    
        $alamat = Alamat::create([
            'id' => $newId,
            'id_pembeli' => Auth::id(),
            'nama_jalan' => $request->nama_jalan,
            'kode_pos' => $request->kode_pos,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'status_default' => $request->status_default,
            'kabupaten' => $request->kabupaten,
            'deskripsi_alamat' => $request->deskripsi_alamat,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Data alamat berhasil ditambahkan.',
            'data' => $alamat
        ]);
    }
    

    // Menampilkan form edit alamat
    public function edit($id_alamat)
    {
        $alamat = Alamat::findOrFail($id_alamat);
        return view('alamat.edit', compact('alamat'));
    }

    // Mengupdate data alamat
    public function update(Request $request, $id_alamat)
    {

        $alamat = Alamat::where('id_alamat', $id_alamat)->where('id_pembeli', Auth::id())->first();

        if (!$alamat) {
            return response()->json(['message' => 'Alamat tidak ditemukan atau bukan milik Anda'], 404);
        }

        $request->validate([
            'nama_jalan' => 'required|string',
            'kode_pos' => 'required|integer',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'status_default' => 'required|string',
            'kabupaten' => 'required|string',
            'deskripsi_alamat' => 'required|string',
        ]);

        $alamat = Alamat::findOrFail($id_alamat);
        $alamat->update($request->only([
            'nama_jalan', 'kode_pos', 'kecamatan', 'kelurahan', 'status_default', 'kabupaten', 'deskripsi_alamat'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Data alamat berhasil diperbarui.',
            'data' => $alamat
        ]);
    }

    public function destroy($id)
    {
        $alamat = Alamat::findOrFail($id);
        $alamat->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Data alamat berhasil dihapus.'
        ]);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $results = Alamat::where('status_default', 'LIKE', '%' . $keyword . '%')->get();

        return response()->json($results);
    }
}