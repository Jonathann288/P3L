<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\transaksipenitipan;
use App\Models\DetailTransaksiPenitipan;
use App\Models\KategoriBarang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BarangControllersApi extends Controller
{
    public function apiShowShop()
    {
        // Ambil semua kategori
        $kategoris = KategoriBarang::all();

        // Ambil semua barang dengan status 'tidak laku'
        $barang = Barang::where('status_barang', 'tidak laku')->get();

        // List gambar kategori (URL lengkap)
        $images = [
            asset('images/gadgets.png'),
            asset('images/shopping.png'),
            asset('images/electric-appliances.png'),
            asset('images/stationery.png'),
            asset('images/hobbies.png'),
            asset('images/stroller.png'),
            asset('images/sport-car.png'),
            asset('images/agriculture.png'),
            asset('images/workspace.png'),
            asset('images/cosmetics.png'),
        ];

        // Kembalikan data dalam format JSON
        return response()->json([
            'kategoris' => $kategoris,
            'barang' => $barang,
            'images' => $images,
        ]);
    }

    public function showDetailApi($id_barang)
    {
        $barang = Barang::find($id_barang);

        if (!$barang || $barang->status_barang !== 'tidak laku') {
            return response()->json([
                'message' => 'Barang tidak ditemukan atau sudah laku/didonasikan.'
            ], 404);
        }

        $isElektronik = $barang->id_kategori == 1;

        return response()->json([
            'barang' => $barang,
            'isElektronik' => $isElektronik,
        ]);
    }

}