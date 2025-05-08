<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use App\Models\Barang;

class KategoriBarangControllers extends Controller
{
    public function filterByCategory($id)
    {
        $kategoris = KategoriBarang::all();
        $images = [
            asset('images/gadgets.png'),
            asset('images/shopping.png'),
            asset('images/electric-appliances.png'),
            asset('images/stationery.png'),
            asset('images/hobbies.png'),
            asset('images/stroller.png'),
            asset('images/gadgets.png'),
            asset('images/sport-car.png'),
            asset('images/workspace.png'),
            asset('images/cosmetics.png'),
        ];
        $selectedKategori = KategoriBarang::findOrFail($id);
        $barang = Barang::where('id_kategori', $id)->get();
        $title = $selectedKategori->nama_kategori;

        return view('shop.category', compact('kategoris', 'barang', 'images', 'title', 'selectedKategori'));
    }

    public function index()
    {
        $kategoribarang = KategoriBarang::all();
        return view('shop', compact('kategoribarang'));
    }

    public function update(Request $request, $id)
    {
        $kategoribarang = KategoriBarang::find($id);
        if (!$kategoribarang) {
            return response()->json(['message' => 'kategoribarang tidak ditemukan'], 404);
        }

        $validateData = $request->validate([
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
        $kategoribarang = KategoriBarang::find($id);
        if (!$kategoribarang) {
            return response()->json(['message' => 'kategoribarang tidak ditemukan'], 404);
        }

        $kategoribarang->delete();
        return response()->json(['message' => 'kategoribarang berhasil dihapus']);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $results = KategoriBarang::where('nama_kategori', 'LIKE', '%' . $keyword . '%')->get();

        return response()->json($results);
    }
}