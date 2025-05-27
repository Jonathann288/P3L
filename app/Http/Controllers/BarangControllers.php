<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class BarangControllers extends Controller
{
    // Tampilkan semua barang 
    public function showShop()
    {
        // Ambil semua kategori
        $kategoris = KategoriBarang::all();

        // Ambil semua barang
        $barang = Barang::all();

        // List gambar kategori (pastikan urutannya benar)
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

        // Kirim data ke view
        return view('shop', compact('kategoris', 'barang', 'images'));
    }

    public function showShopPembeli()
    {
        // Ambil semua kategori
        $kategoris = KategoriBarang::all();

        // Ambil semua barang
        $barang = Barang::all();

        // List gambar kategori (pastikan urutannya benar)
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

        // Kirim data ke view
        return view('pembeli.Shop-Pembeli', compact('kategoris', 'barang', 'images'));
    }

    public function showShopPenitip()
    {
        // Ambil semua kategori
        $kategoris = KategoriBarang::all();

        // Ambil semua barang
        $barang = Barang::all();

        // List gambar kategori (pastikan urutannya benar)
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

        // Kirim data ke view
        return view('penitip.Shop-Penitip', compact('kategoris', 'barang', 'images'));
    }

    public function showDetail($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        return view('shop.detail_barang', compact('barang'));
    }
    public function showDetailPembeli($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        return view('pembeli.detail_barangPembeli', compact('barang'));
    }
    public function showDetailPenitip($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        return view('penitip.detail_barangPenitip', compact('barang'));
    }

    // Simpan barang baru
    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|integer',
            'nama_barang' => 'required|string|max:255',
            'harga_barang' => 'required|numeric',
            'deskripsi_barang' => 'nullable|string',
            'foto_barang' => 'nullable|string',
            'status_barang' => 'nullable|string',
            'rating_barang' => 'nullable|numeric',
            'berat_barang' => 'nullable|numeric',
            'garansi_barang' => 'nullable|date',
            'masa_penitipan' => 'nullable|integer',
        ]);

        // Generate ID otomatis (format B1, B2, ...)
        $lastBarang = Barang::orderBy('id', 'desc')->first();
        $newNumber = $lastBarang && preg_match('/^B(\d+)$/', $lastBarang->id, $m) ? (int) $m[1] + 1 : 1;
        $generatedId = 'B' . $newNumber;

        $barang = Barang::create(array_merge($request->all(), ['id' => $generatedId]));

        return response()->json(['message' => 'Barang berhasil ditambahkan', 'data' => $barang], 201);
    }

    // Detail barang
    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return response()->json($barang);
    }

    // Update barang
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kategori' => 'required|integer',
            'nama_barang' => 'required|string|max:255',
            'harga_barang' => 'required|numeric',
            'deskripsi_barang' => 'nullable|string',
            'foto_barang' => 'nullable|string',
            'status_barang' => 'nullable|string',
            'rating_barang' => 'nullable|numeric',
            'berat_barang' => 'nullable|numeric',
            'garansi_barang' => 'nullable|date',
            'masa_penitipan' => 'nullable|integer',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update($request->all());

        return response()->json(['message' => 'Barang berhasil diperbarui', 'data' => $barang]);
    }

    // Hapus barang
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return response()->json(['message' => 'Barang berhasil dihapus']);
    }

    // Tampilkan daftar barang untuk gudang

    public function showDaftarBarang()
    {
        // Ambil semua barang dengan relasi kategori dan informasi pegawai
        $barang = Barang::with(['kategoribarang'])->get();

        // Untuk setiap barang, cari pegawai yang bertanggung jawab melalui transaksi penitipan
        $barang = $barang->map(function ($item) {
            // Cari transaksi penitipan terbaru untuk barang ini
            $transaksi = \App\Models\TransaksiPenitipan::whereHas('detailTransaksi', function ($query) use ($item) {
                $query->where('id_barang', $item->id_barang);
            })->with('pegawai')->latest('tanggal_penitipan')->first();

            $item->pegawai_penanggungjawab = $transaksi ? $transaksi->pegawai : null;

            return $item;
        });

        $pegawai = auth()->user()->pegawai ?? null;


        // Ambil semua kategori untuk modal edit
        $kategoris = \App\Models\KategoriBarang::all();

        return view('gudang.DaftarBarang', compact('barang', 'pegawai', 'kategoris'));
    }

    // Tampilkan detail barang untuk gudang
    public function showDetailBarangGudang($id_barang)
    {
        $barang = Barang::with(['kategoribarang'])->findOrFail($id_barang);

        $pegawai = auth()->user()->pegawai ?? null;
        if (!$pegawai) {
            $pegawai = (object) [
                'nama_pegawai' => 'Admin Gudang',
                'email_pegawai' => 'admin@gudang.com',
                'nomor_telepon_pegawai' => '08123456789'
            ];
        }

        return view('gudang.DetailBarang', compact('barang', 'pegawai'));
    }

    // Tampilkan form edit barang
    public function showEditBarang($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        $kategoris = KategoriBarang::all();

        $pegawai = auth()->user()->pegawai ?? null;
        if (!$pegawai) {
            $pegawai = (object) [
                'nama_pegawai' => 'Admin Gudang',
                'email_pegawai' => 'admin@gudang.com',
                'nomor_telepon_pegawai' => '08123456789'
            ];
        }

        return view('gudang.EditBarang', compact('barang', 'kategoris', 'pegawai'));
    }

    // Update barang dari form gudang
    public function updateBarangGudang(Request $request, $id_barang)
    {
        $request->validate([
            'id_kategori' => 'required|integer',
            'nama_barang' => 'required|string|max:255',
            'harga_barang' => 'required|numeric',
            'deskripsi_barang' => 'nullable|string',
            'foto_barang' => 'nullable|string',
            'status_barang' => 'nullable|string',
            'rating_barang' => 'nullable|numeric|between:0,5',
            'berat_barang' => 'nullable|numeric',
            'garansi_barang' => 'nullable|date',
            'masa_penitipan' => 'nullable|integer',
        ]);

        $barang = Barang::findOrFail($id_barang);
        $barang->update($request->all());

        return redirect()->route('gudang.DaftarBarang')->with('success', 'Barang berhasil diperbarui');
    }
}