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
            $alamat = $pembeli->alamat ?? collect();

            // Return view dengan data alamat
            return view('pembeli.AlamatPembeli', compact('pembeli','alamat'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try{
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

            return redirect()->route('pembeli.AlamatPembeli')->with('success', 'Alamat berhasil ditambahkan.');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jalan' => 'required|string|max:255',
            'kode_pos' => 'required|integer',
            'kecamatan' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'deskripsi_alamat' => 'nullable|string|max:255',
        ]);

        try {
            // Ambil data alamat berdasarkan ID dan milik pembeli yang sedang login
            $alamat = Alamat::where('id', $id)
                            ->where('id_pembeli', Auth::guard('pembeli')->id())
                            ->firstOrFail();

            // Update data alamat
            $alamat->update([
                'nama_jalan' => $request->nama_jalan,
                'kode_pos' => $request->kode_pos,
                'kecamatan' => $request->kecamatan,
                'kelurahan' => $request->kelurahan,
                'kabupaten' => $request->kabupaten,
                'deskripsi_alamat' => $request->deskripsi_alamat,
            ]);

            return redirect()->back()->with('success', 'Alamat berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        // Ambil data alamat berdasarkan ID dan milik pembeli yang sedang login
        $alamat = Alamat::where('id', $id)
                        ->where('id_pembeli', Auth::guard('pembeli')->id())
                        ->first();
        
        if ($alamat) {
            $alamat->delete();
            return redirect()->route('pembeli.AlamatPembeli')->with('success', 'Alamat berhasil dihapus.');
        } else {
            return redirect()->route('pembeli.AlamatPembeli')->with('error', 'Alamat tidak ditemukan atau tidak berhak menghapus.');
        }
    }

    public function search(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_jalan' => 'nullable|string|max:255',
            'status_default' => 'nullable|string|max:255'
        ]);

        // Ambil parameter pencarian
        $namaJalan = $request->nama_jalan;
        $statusDefault = $request->status_default;

        // Query pencarian
        $query = Alamat::where('id_pembeli', Auth::guard('pembeli')->id());

        if ($namaJalan) {
            $query->where('nama_jalan', 'LIKE', "%{$namaJalan}%");
        }

        if ($statusDefault) {
            $query->where('status_default', $statusDefault);
        }

        $alamat = $query->get();

        $pembeli = Auth::guard('pembeli')->user();
        // Redirect ke view dengan hasil pencarian
        return view('pembeli.Alamatpembeli', compact('alamat', 'pembeli'));
    }


}