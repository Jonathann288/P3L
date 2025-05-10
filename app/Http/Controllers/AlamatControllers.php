<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alamat;
use App\Models\Pembeli;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AlamatControllers extends Controller
{
    // Menampilkan semua data alamat
    public function showAlamat()
    {
        try {
            // Ambil data pembeli yang sedang login
            $pembeli = Auth::guard('pembeli')->user();

            // Ambil data alamat yang berelasi dengan pembeli
            $alamat = ($pembeli)->alamat ?? collect();

            // Return view dengan data alamat
            return view('pembeli.AlamatPembeli', compact('pembeli','alamat'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_jalan' => 'required|string|max:255',
            'kode_pos' => 'required|integer',
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'status_default' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'deskripsi_alamat' => 'required|string|max:255',
        ]);

        // Generate ID unik untuk alamat
        $lastAlamat = DB::table('alamat')
            ->select('id')
            ->where('id', 'like', 'ALT%')
            ->orderByDesc('id')
            ->first();

        $newNumber = $lastAlamat ? ((int) substr($lastAlamat->id, 3)) + 1 : 1;
        $newId = 'ALT' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

        // Simpan data alamat dengan ID yang sudah digenerate
        Alamat::create([
            'id' => $newId, // ID unik untuk alamat
            'id_pembeli' => Auth::guard('pembeli')->user()->id_pembeli,
            'nama_jalan' => $request->nama_jalan,
            'kode_pos' => $request->kode_pos,
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'status_default' => $request->status_default,
            'kabupaten' => $request->kabupaten,
            'deskripsi_alamat' => $request->deskripsi_alamat,
        ]);

        return redirect()->route('pembeli.alamatPembeli')->with('success', 'Alamat berhasil ditambahkan.');
    }


    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'nama_alamat' => 'required',
    //         'detail_alamat' => 'required',
    //     ]);

    //     $alamat = Alamat::find($id);
    //     $alamat->update([
    //         'nama_alamat' => $request->nama_alamat,
    //         'detail_alamat' => $request->detail_alamat,
    //     ]);

    //     return redirect()->route('pembeli.alamatPembeli')->with('success', 'Alamat berhasil diupdate.');
    // }

    // public function destroy($id)
    // {
    //     Alamat::destroy($id);
    //     return redirect()->route('pembeli.alamatPembeli')->with('success', 'Alamat berhasil dihapus.');
    // }

}