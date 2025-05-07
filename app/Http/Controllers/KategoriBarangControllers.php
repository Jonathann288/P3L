<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kategoribarang;

class KategoriBarangControllers extends Controller
{
    public function index()
    {
        $kategoribarang = kategoribarang::all();
        return response()->json($kategoribarang);
    }

    public function update(Request $request, $id)
    {
        $kategoribarang = kategoribarang::find($id);
        if (!$kategoribarang) {
            return response()->json(['message' => 'kategoribarang tidak ditemukan'], 404);
        }

        $validateData = $request-> validate([
            'nama_sub_kategori' => 'required|string|max:255',
        ]);

        $kategoribarang->update($validateData);

        return response()->json([
            'kategoribarang' => $kategoribarang,
            'message' => 'kategoribarang berhasil diperbarui'
        ]);
    }

    public function destroy($id)
    {
        $kategoribarang = kategoribarang::find($id);
        if (!$kategoribarang) {
            return response()->json(['message' => 'kategoribarang tidak ditemukan'], 404);
        }

        $kategoribarang->delete();
        return response()->json(['message' => 'kategoribarang berhasil dihapus']);
    }

    // Cari organisasi berdasarkan nama
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $results = kategoribarang::where('nama_kategori', 'LIKE', '%' . $keyword . '%')->get();

        return response()->json($results);
    }

    
}
