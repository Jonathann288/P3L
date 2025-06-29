<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksipenitipan;
use App\Models\DetailTransaksiPenitipan;
use App\Models\KategoriBarang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BarangControllers extends Controller
{
    // Tampilkan semua barang 
    public function showShop()
    {
        // Ambil semua kategori
        $kategoris = KategoriBarang::all();

        // Ambil semua barang
        $barang = Barang::where('status_barang', 'tidak laku')->get();

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

    public function showDonasi()
    {
        // Ambil semua kategori
        $kategoris = KategoriBarang::all();

        // Ambil semua barang
        $barang = Barang::where('status_barang', 'di donasikan')->get();

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
        return view('donasi', compact('kategoris', 'barang', 'images'));
    }

    public function showDonasiOrganisasi()
    {
        // Ambil semua kategori
        $kategoris = KategoriBarang::all();

        // Ambil semua barang
        $barang = Barang::where('status_barang', 'di donasikan')->get();

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
        return view('organisasi.donasi-organisasi', compact('kategoris', 'barang', 'images'));
    }
    // sampe ini

    public function showShopPembeli()
    {
        // Ambil semua kategori
        $kategoris = KategoriBarang::all();

        // Ambil semua barang
        $barang = Barang::where('status_barang', 'tidak laku')->get();

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
        $barang = Barang::where('status_barang', 'tidak laku')->get();

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
        if ($barang->status_barang !== 'tidak laku') {
            abort(404, 'Barang tidak ditemukan atau sudah laku/didonasikan.');
        }
        $isElektronik = $barang->id_kategori == 1;
        return view('shop.detail_barang', compact('barang','isElektronik'));
    }

    public function showDetailDonasi($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        
        if ($barang->status_barang !== 'di donasikan') {
            abort(404, 'Barang tidak ditemukan atau sudah laku/didonasikan.');
        }
        $isElektronik = $barang->id_kategori == 1;
        return view('donasi.detail_barang_donasi', compact('barang','isElektronik'));
    }

    public function showDetailDonasiOranisasi($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        if ($barang->status_barang !== 'di donasikan') {
            abort(404, 'Barang tidak ditemukan atau sudah laku/didonasikan.');
        }
        $isElektronik = $barang->id_kategori == 1;
        return view('organisasi.detail_barang_donasi', compact('barang','isElektronik'));
    }

    public function showDetailPembeli($id_barang)
    {
        $pembeli = Auth::guard('pembeli')->user();
        // Eager load kategori, diskusi with relations to pembeli and pegawai
        $barang = Barang::with(['kategori', 'diskusi.pembeli', 'diskusi.pegawai'])
            ->findOrFail($id_barang);

        if ($barang->status_barang !== 'tidak laku') {
            abort(404, 'Barang tidak ditemukan atau sudah laku/didonasikan.');
        }

        // Load diskusi with proper sorting (newest first)
        $barang->setRelation('diskusi', $barang->diskusi->sortByDesc('tanggal_diskusi'));
        $isElektronik = $barang->id_kategori == 1;
        return view('pembeli.detail_barangPembeli', compact('barang','pembeli','isElektronik'));
    }

    public function showDetailPenitip($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        if ($barang->status_barang !== 'tidak laku') {
            abort(404, 'Barang tidak ditemukan atau sudah laku/didonasikan.');
        }
        $isElektronik = $barang->id_kategori == 1;
        return view('penitip.detail_barangPenitip', compact('barang','isElektronik'));
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

    public function search(Request $request)
    {
        {
            $request->validate([
                'nama_barang' => 'nullable|string|max:255',
            ]);

            $namaBarang = $request->nama_barang;

            $query = Barang::query();

            if ($namaBarang) {
                $query->where('nama_barang', 'LIKE', "%{$namaBarang}%");
            }

            $barang = $query->get();
            $kategoris = KategoriBarang::all();
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

            return view('shop', compact('barang','kategoris','images'));
        }

    }

    // COPY INI
     public function showDaftarBarang()
    {
        // Ambil semua barang dengan relasi kategori dan informasi pegawai
        $barang = Barang::with(['kategoribarang'])->get();

        // Untuk setiap barang, cari pegawai yang bertanggung jawab melalui transaksi penitipan
        $barang = $barang->map(function ($item) {
            // Cari transaksi penitipan terbaru untuk barang ini
            $transaksi = Transaksipenitipan::whereHas('DetailTransaksiPenitipan', function ($query) use ($item) {
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
        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategoribarang,id_kategori',
            'nama_barang' => 'required|string|max:255',
            'harga_barang' => 'required|numeric|min:0',
            'berat_barang' => 'nullable|numeric|min:0',
            'status_barang' => 'required|string',
            'masa_penitipan' => 'nullable|integer|min:1',
            'deskripsi_barang' => 'nullable|string',
            'rating_barang' => 'nullable|numeric|min:0|max:5',
            'garansi_barang' => 'nullable|date',
            'foto_barang' => 'nullable|array|max:5',
            'foto_barang.*' => 'file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {
            $barang = Barang::findOrFail($id_barang);

            // Handle foto upload jika ada
            if ($request->hasFile('foto_barang')) {
                // Hapus foto lama
                $oldFotos = $barang->foto_barang;
                if (is_array($oldFotos)) {
                    foreach ($oldFotos as $oldFoto) {
                        if (file_exists(public_path($oldFoto))) {
                            unlink(public_path($oldFoto));
                        }
                    }
                }

                // Upload foto baru
                $fotoPaths = [];
                foreach ($request->file('foto_barang') as $index => $foto) {
                    $filename = 'barang_' . time() . '_' . $index . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $foto->move(public_path('images'), $filename);
                    $fotoPaths[] = 'images/' . $filename;
                }
                $barang->foto_barang = $fotoPaths;
            }

            // Update data barang
            $barang->update([
                'id_kategori' => $validated['id_kategori'],
                'nama_barang' => $validated['nama_barang'],
                'harga_barang' => $validated['harga_barang'],
                'berat_barang' => $validated['berat_barang'],
                'status_barang' => $validated['status_barang'],
                'masa_penitipan' => $validated['masa_penitipan'],
                'deskripsi_barang' => $validated['deskripsi_barang'],
                'rating_barang' => $validated['rating_barang'],
                'garansi_barang' => $validated['garansi_barang'],
            ]);

            return redirect()->route('gudang.DaftarBarang')->with('success', 'Barang berhasil diupdate!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}