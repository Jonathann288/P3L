<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\Keranjang;
use App\Models\Alamat;
use App\Models\transaksipenjualan;
use App\Models\Pegawai;
use App\Models\Komisi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TransaksiPenjualanControllers extends Controller
{
    public function tambahKeranjang(Request $request, $id)
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            return redirect()->back()->with('error', 'Anda harus login terlebih dahulu.');
        }

        $barang = Barang::find($id);
        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }

        // Gunakan session key yang unik untuk setiap pembeli
        $keranjangKey = 'keranjang_' . $pembeli->id;
        $keranjang = session()->get($keranjangKey, []);

        // Cek apakah item sudah ada di keranjang
        if (isset($keranjang[$barang->id_barang])) {
            return redirect()->back()->with('error', 'Item sudah ada di keranjang Anda!');
        }

        // Tambahkan item baru dengan quantity tetap 1
        $keranjang[$barang->id_barang] = [
            'id_barang' => $barang->id_barang,
            'nama_barang' => $barang->nama_barang,
            'harga_barang' => $barang->harga_barang,
            'jumlah' => 1 // Selalu 1, tidak bisa diubah
        ];

        session([$keranjangKey => $keranjang]);

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang!');
    }

    public function hapusDariKeranjang(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            return redirect()->back()->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Tambahkan fallback untuk parameter 'id' jika 'id_barang' tidak ada
        $id_barang = $request->input('id_barang', $request->input('id'));

        if (!$id_barang) {
            return redirect()->back()->with('error', 'Parameter ID barang tidak valid.');
        }

        $keranjangKey = 'keranjang_' . $pembeli->id;
        $keranjang = session()->get($keranjangKey, []);

        if (isset($keranjang[$id_barang])) {
            unset($keranjang[$id_barang]);
            session()->put($keranjangKey, $keranjang);
            return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
        }

        // Debugging - log isi keranjang dan ID yang dicari
        \Log::info('Keranjang content: ', $keranjang);
        \Log::info('ID Barang yang dicari: ' . $id_barang);

        return redirect()->back()->with('error', 'Item tidak ditemukan di keranjang.');
    }

    // HAPUS atau DISABLE method updateQuantity karena quantity tidak bisa diubah
    public function updateQuantity(Request $request)
    {
        // Karena setiap item hanya bisa 1, method ini tidak diperlukan
        // Return error response
        return response()->json([
            'error' => 'Quantity tidak dapat diubah. Setiap item hanya bisa ditambahkan sekali.'
        ], 400);
    }

    public function tampilKeranjang(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Gunakan session key yang unik untuk setiap pembeli
        $keranjangKey = 'keranjang_' . $pembeli->id;
        $keranjang = session()->get($keranjangKey, []);

        // Filter berdasarkan search jika ada
        $search = $request->input('search');
        if ($search) {
            $keranjang = array_filter($keranjang, function ($item) use ($search) {
                return stripos($item['nama_barang'], $search) !== false;
            });
        }

        return view('pembeli.cartPembeli', compact('keranjang', 'pembeli'));
    }

    public function searchCart(Request $request)
    {
        return $this->tampilKeranjang($request);
    }

    public function cartPembeli()
    {
        return $this->tampilKeranjang(request());
    }

    public function checkOutPembeli()
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Gunakan session key yang unik untuk setiap pembeli
        $keranjangKey = 'keranjang_' . $pembeli->id;
        $keranjang = session()->get($keranjangKey, []);

        if (empty($keranjang)) {
            return redirect()->route('keranjang.tampil')->with('error', 'Keranjang Anda kosong.');
        }

        $alamat = $pembeli->alamat;

        // Hitung subtotal (semua item quantity = 1)
        $subtotal = collect($keranjang)->sum(function ($item) {
            return $item['harga_barang']; // Tidak perlu dikali jumlah karena selalu 1
        });

        return view('pembeli.checkOutPembeli', compact('keranjang', 'pembeli', 'subtotal', 'alamat'));
    }

    public function clearCart()
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            return redirect()->back()->with('error', 'Anda harus login terlebih dahulu.');
        }

        $keranjangKey = 'keranjang_' . $pembeli->id;
        session()->forget($keranjangKey);

        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan.');
    }

    public function showCheckout()
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Gunakan session key yang unik untuk setiap pembeli
        $keranjangKey = 'keranjang_' . $pembeli->id;
        $keranjang = session()->get($keranjangKey, []);

        if (empty($keranjang)) {
            return redirect()->route('keranjang.tampil')->with('error', 'Keranjang Anda kosong.');
        }

        $alamat = $pembeli->alamat;

        // Hitung subtotal (semua item quantity = 1)
        $subtotal = collect($keranjang)->sum(function ($item) {
            return $item['harga_barang']; // Tidak perlu dikali jumlah karena selalu 1
        });

        return view('pembeli.checkOutPembeli', compact('keranjang', 'pembeli', 'subtotal', 'alamat'));
    }

    public function prosesCheckout(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();
        if (!$pembeli) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // 1. Buat ID transaksi baru
        $lastTransaction = transaksipenjualan::where('id', 'like', 'TP%')
            ->orderBy('id', 'desc')->first();

        $newId = !$lastTransaction
            ? 'TP01'
            : 'TP' . str_pad(((int) substr($lastTransaction->id, 2)) + 1, 2, '0', STR_PAD_LEFT);

        // 2. Validasi request
        $validator = Validator::make($request->all(), [
            'metode_pengantaran' => 'required|in:ambil_sendiri,diantar_kurir',
            'selected_address' => 'required_if:metode_pengantaran,diantar_kurir|nullable|exists:alamat,id',
            'poin_digunakan' => 'nullable|integer|min:0|max:' . $pembeli->total_poin,
        ], [
            'metode_pengantaran.required' => 'Silakan pilih metode pengantaran.',
            'selected_address.required_if' => 'Silakan pilih alamat pengiriman.',
            'selected_address.exists' => 'Alamat tidak ditemukan.',
            'poin_digunakan.max' => 'Poin yang digunakan tidak boleh melebihi total poin Anda (' . number_format($pembeli->total_poin, 0, ',', '.') . ' poin).',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 3. Ambil keranjang dari session
        $keranjangKey = 'keranjang_' . $pembeli->id;
        $keranjang = session($keranjangKey, []);
        if (empty($keranjang)) {
            return redirect()->route('pembeli.cart')->with('error', 'Keranjang Anda kosong.');
        }

        // 4. Inisialisasi dan hitung total
        $subtotal = collect($keranjang)->sum('harga_barang');
        $totalItems = count($keranjang);

        $ongkir = $request->metode_pengantaran === 'diantar_kurir' && $subtotal < 1500000 ? 100000 : 0;
        $poin_digunakan = (int) $request->input('poin_digunakan', 0);
        $diskon_poin = min($poin_digunakan * 100, $subtotal);
        $total = $subtotal + $ongkir - $diskon_poin;

        // PERBAIKAN: Hapus sistem random poin, hanya simpan poin yang digunakan
        // $poin_didapat = floor($total / 10000); // DIHAPUS

        $tanggal_kirim = $request->metode_pengantaran === 'diantar_kurir' ? now() : null;
        $id_pegawai = $request->metode_pengantaran === 'diantar_kurir' ? 5 : 4;

        $data = [
            'id' => $newId,
            'id_pembeli' => $pembeli->id_pembeli,
            'id_pegawai' => $id_pegawai,
            'tanggal_transaksi' => now(),
            'metode_pengantaran' => $request->metode_pengantaran,
            'tanggal_lunas' => null,
            'bukti_pembayaran' => null,
            'status_pembayaran' => 'Belum Lunas',
            'poin' => $poin_digunakan, // PERBAIKAN: Simpan poin yang digunakan (terpotong)
            'poin_digunakan' => $poin_digunakan,
            'diskon_poin' => $diskon_poin,
            'tanggal_kirim' => $tanggal_kirim,
            'ongkir' => $ongkir,
            'status_transaksi' => 'diproses'
        ];

        // 5. Simpan transaksi dan detail secara atomic
        $transaksi = null;
        DB::beginTransaction();
        try {
            // Kurangi poin pembeli
            if ($poin_digunakan > 0) {
                $pembeli->refresh();
                if ($poin_digunakan > $pembeli->total_poin) {
                    throw new \Exception('Poin Anda tidak mencukupi.');
                }
                $pembeli->decrement('total_poin', $poin_digunakan);
            }

            // Simpan header transaksi
            $transaksi = transaksipenjualan::create($data);

            // Simpan detail dan update status barang
            foreach ($keranjang as $id_barang => $item) {
                $barang = Barang::find($id_barang);
                if (!$barang)
                    continue;

                $hargaBarang = $barang->harga_barang;
                $porsi = $subtotal > 0 ? ($hargaBarang / $subtotal) : (1 / $totalItems);
                $ongkirItem = $porsi * $ongkir;
                $diskonItem = $porsi * $diskon_poin;
                $totalHargaFinal = $hargaBarang + $ongkirItem - $diskonItem;

                $transaksi->detailTransaksi()->create([
                    'id_barang' => $id_barang,
                    'jumlah' => 1,
                    'harga' => $hargaBarang,
                    'subtotal' => $hargaBarang,
                    'total_harga' => round($totalHargaFinal),
                ]);

                $barang->status_barang = 'laku';
                $barang->save();
            }

            session()->forget($keranjangKey);
            DB::commit();

            return redirect()->route('pembayaranPembeli', ['id' => $transaksi->id])
                ->with('success', 'Checkout berhasil. ID transaksi: ' . $transaksi->id .
                    ($poin_digunakan > 0 ? '. Poin sebesar ' . number_format($poin_digunakan, 0, ',', '.') . ' telah digunakan.' : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pembeli.cart')
                ->withInput()
                ->with('error', 'Gagal checkout: ' . $e->getMessage());
        }
    }


    public function showPembayaran($id)
    {
        try {
            $pembeli = Auth::guard('pembeli')->user();
            if (!$pembeli) {
                return redirect()->route('login');
            }

            $transaksi = transaksipenjualan::with(['detailTransaksi', 'pembeli'])
                ->where('id', $id)
                ->first();

            if (!$transaksi) {
                return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
            }

            // Cek jika transaksi sudah expired
            $expiredTime = $transaksi->tanggal_transaksi->addMinutes(1);
            if (now() > $expiredTime && $transaksi->status_pembayaran == 'Belum Lunas') {
                $this->cleanupExpiredTransaction($transaksi);
                return redirect()->back()->with('error', 'Waktu pembayaran habis. Poin telah dikembalikan.');
            }

            $expired_time = $expiredTime->toIso8601String();
            return view('pembeli.pembayaranPembeli', compact('transaksi', 'pembeli', 'expired_time'));

        } catch (\Exception $e) {
            \Log::error('Error in showPembayaran: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Method untuk membersihkan transaksi yang sudah expired
     */
     private function cleanupExpiredTransaction($transaksi)
    {
        DB::beginTransaction();
        try {
            \Log::info('Starting cleanup for expired transaction: ' . $transaksi->id);

            // 1. Kembalikan poin terlebih dahulu
            if ($transaksi->poin > 0) {
                $pembeli = $transaksi->pembeli;
                $pembeli->total_poin += $transaksi->poin;
                $pembeli->save();

                \Log::info('Poin dikembalikan', [
                    'pembeli_id' => $pembeli->id_pembeli,
                    'poin_dikembalikan' => $transaksi->poin,
                    'total_poin_sekarang' => $pembeli->total_poin
                ]);
            }

            // 2. Kembalikan status barang
            foreach ($transaksi->detailTransaksi as $detail) {
                Barang::where('id_barang', $detail->id_barang)
                    ->update(['status_barang' => 'tidak laku']);
            }

            // 3. Hapus detail transaksi
            $transaksi->detailTransaksi()->delete();
            \Log::info('Detail transaksi dihapus untuk transaksi: ' . $transaksi->id);

            // 4. Update status transaksi
            $transaksi->update([
                'status_pembayaran' => 'dibatalkan',
                'status_transaksi' => 'dibatalkan',
                'poin_digunakan' => 0,
                'diskon_poin' => 0
            ]);

            DB::commit();
            \Log::info('Cleanup completed for transaction: ' . $transaksi->id);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Cleanup failed: ' . $e->getMessage());
            throw $e;
        }
    }


    public function prosesPembayaran(Request $request, $id)
    {
        try {
            $pembeli = Auth::guard('pembeli')->user();
            if (!$pembeli) {
                return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
            }

            // Cari transaksi
            $transaksi = transaksipenjualan::where('id', $id)
                ->where('id_pembeli', $pembeli->id_pembeli)
                ->first();
            if (!$transaksi) {
                return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
            }

            $expiredTime = $transaksi->tanggal_transaksi->copy()->addMinutes(1);
            if (now()->greaterThanOrEqualTo($expiredTime)) {
                // Panggil method untuk cleanup transaksi expired
                $this->cleanupExpiredTransaction($transaksi);
                return redirect()->back()->with('error', 'Waktu pembayaran telah habis. Poin Anda telah dikembalikan.');
            }

            // Validasi file upload
            $request->validate([
                'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            ], [
                'payment_proof.required' => 'Bukti pembayaran harus diupload.',
                'payment_proof.file' => 'File yang diupload tidak valid.',
                'payment_proof.mimes' => 'File harus berformat JPG, JPEG, PNG, atau PDF.',
                'payment_proof.max' => 'Ukuran file maksimal 5MB.',
            ]);

            DB::beginTransaction();
            try {
                // Upload file
                $file = $request->file('payment_proof');
                $fileName = time() . '_' . $pembeli->id_pembeli . '_' . $file->getClientOriginalName();
                // Simpan file ke storage/app/public/bukti_pembayaran
                $filePath = $file->storeAs('bukti_pembayaran', $fileName, 'public');
                // PERBAIKAN: Update transaksi dengan bukti pembayaran
                $transaksi->update([
                    'bukti_pembayaran' => $fileName,
                    'status_pembayaran' => 'Menunggu Konfirmasi',
                    'status_transaksi' => 'sedang disiapkan',
                    'tanggal_lunas' => now(), // PERBAIKAN: Set tanggal_lunas ketika upload bukti
                ]);

                // Kembalikan poin jika pembayaran gagal sebelumnya
                if ($transaksi->poin_digunakan > 0 && $transaksi->status_pembayaran === 'Belum Lunas') {
                    $transaksi->poin_digunakan = 0;
                    $transaksi->save();

                    // Kembalikan poin pembeli
                    $pembeli = Auth::guard('pembeli')->user();
                    $pembeli->total_poin += $transaksi->poin_digunakan;
                    $pembeli->save();
                }

                DB::commit();
                \Log::info('Bukti pembayaran berhasil diupload', [
                    'transaksi_id' => $transaksi->id,
                    'file_name' => $fileName,
                    'pembeli_id' => $pembeli->id_pembeli,
                    'tanggal_lunas' => $transaksi->tanggal_lunas
                ]);
                return redirect()->route('pembeli.cart')->with('success', 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.');
            } catch (\Exception $e) {
                DB::rollBack();
                // Hapus file jika sudah terupload tapi gagal save ke database
                if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                throw $e;
            }
        } catch (\Exception $e) {
            \Log::error('Error upload bukti pembayaran: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengupload bukti pembayaran: ' . $e->getMessage());
        }
    }

    // Method untuk menampilkan bukti pembayaran (opsional)
    public function lihatBuktiPembayaran($id)
    {
        try {
            $pembeli = Auth::guard('pembeli')->user();

            if (!$pembeli) {
                return redirect()->route('login');
            }

            $transaksi = transaksipenjualan::where('id', $id)
                ->where('id_pembeli', $pembeli->id_pembeli)
                ->first();

            if (!$transaksi || !$transaksi->bukti_pembayaran) {
                return redirect()->back()->with('error', 'Bukti pembayaran tidak ditemukan.');
            }

            $filePath = storage_path('app/public/bukti_pembayaran/' . $transaksi->bukti_pembayaran);

            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'File bukti pembayaran tidak ditemukan.');
            }

            return response()->file($filePath);

        } catch (\Exception $e) {
            \Log::error('Error lihat bukti pembayaran: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menampilkan bukti pembayaran.');
        }
    }

    public function tambahKeranjangAjax(Request $request, $id)
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login terlebih dahulu.'
                ], 401);
            }
            return redirect()->back()->with('error', 'Anda harus login terlebih dahulu.');
        }

        try {
            $barang = Barang::findOrFail($id);

            // Gunakan session key yang unik untuk setiap pembeli
            $keranjangKey = 'keranjang_' . $pembeli->id;
            $keranjang = session()->get($keranjangKey, []);

            // Cek apakah item sudah ada di keranjang
            if (isset($keranjang[$barang->id_barang])) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Item sudah ada di keranjang Anda!'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Item sudah ada di keranjang Anda!');
            }

            // Tambah item baru dengan quantity selalu 1
            $keranjang[$barang->id_barang] = [
                'id_barang' => $barang->id_barang,
                'nama_barang' => $barang->nama_barang,
                'harga_barang' => $barang->harga_barang,
                'jumlah' => 1 // Selalu 1, tidak bisa diubah
            ];

            session([$keranjangKey => $keranjang]);

            // Return JSON response untuk AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item berhasil ditambahkan ke keranjang',
                    'cart_count' => $this->getCartCountValue()
                ]);
            }

            // Jika bukan AJAX, redirect seperti biasa
            return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang!');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan item ke keranjang'
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menambahkan item ke keranjang');
        }
    }

    /**
     * Get cart count value (untuk internal use)
     */
    private function getCartCountValue()
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            return 0;
        }

        $keranjangKey = 'keranjang_' . $pembeli->id;
        $keranjang = session()->get($keranjangKey, []);

        return count($keranjang); // Menghitung jumlah item unik
    }

    /**
     * Get cart count (untuk AJAX request) - Update method yang sudah ada
     */
    public function getCartCount()
    {
        $pembeli = Auth::guard('pembeli')->user();

        if (!$pembeli) {
            return response()->json(['count' => 0]);
        }

        $keranjangKey = 'keranjang_' . $pembeli->id;
        $keranjang = session()->get($keranjangKey, []);

        return response()->json([
            'count' => count($keranjang), // Jumlah item unik
            'total_quantity' => count($keranjang) // Total quantity sama dengan jumlah item karena setiap item = 1
        ]);
    }

    public function generateOrderNumber($transaksi)
    {
        $year = date('Y', strtotime($transaksi->tanggal_transaksi));
        $month = date('m', strtotime($transaksi->tanggal_transaksi));
        $transactionId = $transaksi->id;

        return $year . '.' . $month . '.' . $transactionId;
    }

    public function showVerifikasi()
    {
        try {
            $pegawaiLogin = Auth::guard('pegawai')->user();

            // Ambil transaksi dengan eager loading
            $transaksiMenunggu = TransaksiPenjualan::with(['detailTransaksi.barang', 'pembeli'])
                ->where('status_pembayaran', 'menunggu konfirmasi')
                ->orderBy('tanggal_transaksi', 'desc')
                ->get();

            $transaksiTerverifikasi = TransaksiPenjualan::with(['detailTransaksi.barang', 'pembeli'])
                ->where('status_pembayaran', 'Lunas')
                ->orderBy('tanggal_transaksi', 'desc')
                ->get();

            return view('CustomerService.DashboardVerifikasiItem', compact(
                'transaksiMenunggu',
                'transaksiTerverifikasi',
                'pegawaiLogin'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuka halaman verifikasi: ' . $e->getMessage());
        }
    }

    public function rejectTransaction(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $transaksi = TransaksiPenjualan::where('id', $id)->firstOrFail();

            // Validasi: hanya bisa ditolak jika status masih pending
            if ($transaksi->status_pembayaran !== 'Menunggu Konfirmasi') {
                return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
            }

            $transaksi->update([
                'status_pembayaran' => 'ditolak',
                'status_transaksi' => 'dibatalkan',
                'id_pegawai' => auth()->guard('pegawai')->user()->id_pegawai
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil ditolak.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Transaksi dengan ID ' . $id . ' tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menolak transaksi: ' . $e->getMessage());
        }
    }



    public function approveTransaction(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Ambil transaksi beserta relasi pembeli dan detail transaksi
            $transaksi = TransaksiPenjualan::with(['pembeli', 'detailTransaksi'])->where('id', $id)->firstOrFail();

            // Validasi: hanya bisa di-approve jika status masih "Menunggu Konfirmasi"
            if ($transaksi->status_pembayaran !== 'Menunggu Konfirmasi') {
                return redirect()->back()->with('error', 'Transaksi ini sudah diproses sebelumnya.');
            }

            // Hitung total transaksi dari detailTransaksi
            // $subtotal = $transaksi->detailTransaksi->sum('total_harga');
            // $totalSetelahDiskon = $subtotal - $transaksi->diskon_poin;
            
            $totalHargaBarang = $transaksi->detailTransaksi->sum(function ($detail) {
                return $detail->barang ? $detail->barang->harga_barang : 0;
            });

            $poinDidapat = floor($totalHargaBarang / 10000);


            // Hitung poin yang didapat (1 poin per 10.000)
            // $poinDidapat = floor($totalSetelahDiskon / 10000);

            // // Tambahkan bonus poin 20% jika subtotal > 500.000
            // if ($subtotal > 500000) {
            //     $bonusPoin = floor($poinDidapat * 0.20);
            //     $poinDidapat += $bonusPoin;
            // }

            if ($totalHargaBarang > 500000) {
                $bonus = round($poinDidapat * 0.2);
                $poinDidapat += $bonus;
            }


            // Update status transaksi dan simpan poin ke kolom poin_dapat
            $transaksi->update([
                'status_pembayaran' => 'Lunas',
                'status_transaksi' => 'Di siapkan',
                'poin_dapat' => $poinDidapat
            ]);

            // Logging poin yang didapat untuk tracking
            \Log::info('Poin disimpan di transaksi', [
                'transaksi_id' => $transaksi->id,
                'pembeli_id' => $transaksi->pembeli->id_pembeli,
                'poin_dapat' => $poinDidapat,
                'totalHarga' => $totalHargaBarang,
                'bonus_applicable' => $totalHargaBarang > 500000
            ]);

            DB::commit();

            $message = 'Transaksi berhasil di-approve.';
            if ($poinDidapat > 0) {
                $message .= ' Pembeli mendapat ' . number_format($poinDidapat, 0, ',', '.') . ' poin (tersimpan di data transaksi).';
            }

            return redirect()->back()->with('success', $message);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Transaksi dengan ID ' . $id . ' tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error approve transaction: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengapprove transaksi: ' . $e->getMessage());
        }
    }


     public function showTransaksiKirim()
    {
        try {
            $pegawaiLogin = Auth::guard('pegawai')->user();

            // Ambil transaksi dengan eager loading
            $transaksiAntar = TransaksiPenjualan::with(['detailTransaksi.barang', 'pembeli', 'kurir'])
                ->where('metode_pengantaran', 'diantar_kurir')
                ->orderBy('tanggal_transaksi', 'desc')
                ->get();

            $transaksiAmbilsendiri = TransaksiPenjualan::with(['detailTransaksi.barang', 'pembeli'])
                ->where('metode_pengantaran', 'ambil_sendiri')
                ->orderBy('tanggal_transaksi', 'desc')
                ->get();
            $kurirList = Pegawai::where('id_jabatan', 5)->get();
            return view('gudang.DashboardShowTransaksiAntarAmbil', compact(
                'transaksiAntar',
                'transaksiAmbilsendiri',
                'pegawaiLogin',
                'kurirList'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuka halaman verifikasi: ' . $e->getMessage());
        }
    }

    public function jadwalkanPengiriman(Request $request)
    {
        $request->validate([
            'id_transaksi_penjualan' => 'required|exists:transaksipenjualan,id_transaksi_penjualan',
            'id_pegawai' => 'required|exists:pegawai,id_pegawai',
            'tanggal_kirim' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $timezone = 'Asia/Jakarta';

        $tanggalKirim = Carbon::createFromFormat('Y-m-d\TH:i', $request->tanggal_kirim, $timezone);
        $batasWaktuHariIni = Carbon::today($timezone)->setHour(16)->setMinute(0)->setSecond(0);

        if ($tanggalKirim->isToday() && $tanggalKirim->greaterThan($batasWaktuHariIni)) {
            $tanggalKirim = Carbon::tomorrow($timezone)->setHour(8)->setMinute(0)->setSecond(0);
        }

        $transaksi = transaksipenjualan::findOrFail($request->id_transaksi_penjualan);

        $transaksi->update([
            'id_pegawai' => $request->id_pegawai,
            'tanggal_kirim' => $tanggalKirim,
            'status_transaksi' => 'dijadwalkan',
        ]);

        return back()->with('success', 'Jadwal pengiriman berhasil disimpan untuk tanggal ' . $tanggalKirim->translatedFormat('d M Y H:i') . '.');
    }

    public function jadwalAmbil(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksipenjualan,id_transaksi_penjualan',
            'tanggal_ambil' => 'required|date|after_or_equal:today',
        ]);

            $transaksi = TransaksiPenjualan::where('id_transaksi_penjualan', $request->transaksi_id)->first();

        $transaksi->update([
            'tanggal_ambil' => $request->tanggal_ambil,
        ]);

        return back()->with('success', 'Tanggal pengambilan berhasil dijadwalkan.');
    }

    public function konfirmasiTerima($id)
    {
        DB::beginTransaction();

        try {
            $transaksi = TransaksiPenjualan::with([
                'pembeli',
                'detailTransaksi.barang.detailTransaksiPenitipan.transaksiPenitipan.penitip',
                'detailTransaksi.barang.detailTransaksiPenitipan.transaksiPenitipan.pegawai'
            ])
            ->where('id_transaksi_penjualan', $id)
            ->firstOrFail();

            foreach ($transaksi->detailTransaksi as $detail) {
                $barang = $detail->barang;
                $harga = $detail->total_harga;

                $penitipan = optional($barang->detailTransaksiPenitipan)->transaksiPenitipan;
                $penitip = optional($penitipan)->penitip;
                $pegawai = optional($penitipan)->pegawai;

                // Cek apakah barang diperpanjang (asumsikan ada field `diperpanjang`)
                $isDiperpanjang = optional($barang)->diperpanjang == 1;

                // Komisi ReuseMart
                $komisi_reusemart = $isDiperpanjang ? 0.30 * $harga : 0.20 * $harga;

                // Komisi Hunter
                $komisi_hunter = $pegawai ? 0.05 * $harga : 0;

                // Jika ada hunter dan diperpanjang, ReuseMart hanya dapat 25%
                if ($pegawai && $isDiperpanjang) {
                    $komisi_reusemart = 0.25 * $harga;
                }

                // Bonus Penitip (barang terjual <= 7 hari sejak penitipan)
                $bonus_penitip = 0;
                if ($penitipan && isset($penitipan->tanggal_penitipan, $transaksi->tanggal_transaksi)) {
                    $tanggal_penitipan = Carbon::parse($penitipan->tanggal_penitipan);
                    $tanggal_transaksi = Carbon::parse($transaksi->tanggal_transaksi);

                    if ($tanggal_penitipan->diffInDays($tanggal_transaksi) <= 7) {
                        $bonus_penitip = 0.10 * $komisi_reusemart;
                    }
                }

                // Komisi Penitip
                $komisi_penitip = $harga - $komisi_reusemart - $komisi_hunter + $bonus_penitip;

                // Tambahkan saldo ke penitip
                if ($penitip) {
                    $penitip->saldo_penitip += $komisi_penitip;
                    $penitip->save();
                }

                $id_terakhir = Komisi::max('id');
                $angka_terakhir = (int) filter_var($id_terakhir, FILTER_SANITIZE_NUMBER_INT);
                $id_baru = 'KMS' . str_pad($angka_terakhir + 1, 4, '0', STR_PAD_LEFT);

                Komisi::create([
                    'id' => $id_baru, // ini wajib karena tidak ada default value
                    'id_transaksi_penjualan' => $transaksi->id_transaksi_penjualan,
                    'id_penitip' => $penitip->id_penitip ?? null,
                    'id_pegawai' => $pegawai->id_pegawai ?? null,
                    'komisi_penitip' => $komisi_penitip,
                    'komisi_reusemart' => $komisi_reusemart,
                    'komisi_hunter' => $komisi_hunter,
                ]);
            }

            // Tambahkan poin ke akun pembeli
            if ($transaksi->poin_dapat > 0) {
                $pembeli = $transaksi->pembeli;
                $pembeli->total_poin += $transaksi->poin_dapat;
                $pembeli->save();
            }

            $transaksi->update([
                'status_transaksi' => 'transaksi selesai',
            ]);

            DB::commit();
            return back()->with('success', 'Transaksi dikonfirmasi dan semua komisi serta poin berhasil dihitung.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return back()->with('error', 'Transaksi tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function konfirmasiDanCetakNota($id)
    {
        try {
            // Ambil data transaksi beserta relasi
            $transaksi = transaksipenjualan::with(['pembeli', 'pegawai', 'kurir', 'detailTransaksi.barang'])->findOrFail($id);

            // Cek apakah no_nota sudah ada, kalau belum generate dan simpan
            if (empty($transaksi->no_nota)) {
                // Ambil nomor urut terakhir berdasarkan id_transaksi_penjualan
                $lastTransaction = DB::table('transaksipenjualan')->orderBy('id_transaksi_penjualan', 'desc')->first();
                $lastNumber = $lastTransaction ? $lastTransaction->id_transaksi_penjualan : 0;
                $newNumber = $lastNumber + 1;

                $year = Carbon::now()->format('Y');
                $month = Carbon::now()->format('m');

                // Format no nota
                $noNota = $year . '.' . $month . '.' . $newNumber;

                // Simpan no_nota ke database
                $transaksi->no_nota = $noNota;
                $transaksi->save();
            }

            // Siapkan data untuk nota
            $data = [
                'transaksi' => $transaksi,
                'tanggal_cetak' => Carbon::now()->format('d F Y H:i:s'),
                'perusahaan' => [
                    'nama' => 'ReUse Mart',
                    'alamat' => 'Jl. Green Eco Park No. 456 Yogyakarta',
                    'telepon' => '(0274) 123-4567',
                    'email' => 'info@reusermart.com'
                ]
            ];

            // Generate PDF nota
            $pdf = PDF::loadView('gudang.NotaAmbilSendiri', $data)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true
                ]);

            // Nama file, bisa pakai no_nota supaya lebih mudah
            $namaFile = 'Nota_Transaksi_' . $transaksi->no_nota . '_' . now()->format('YmdHis') . '.pdf';

            // Return sebagai file download
            return $pdf->download($namaFile);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencetak nota: ' . $e->getMessage());
        }
    }

    public function CetakNota($id)
    {
        try {
            // Ambil data transaksi beserta relasi
            $transaksi = transaksipenjualan::with(['pembeli', 'pegawai', 'kurir', 'detailTransaksi.barang'])->findOrFail($id);

            // Cek apakah no_nota sudah ada, kalau belum generate dan simpan
            if (empty($transaksi->no_nota)) {
                // Ambil nomor urut terakhir berdasarkan id_transaksi_penjualan
                $lastTransaction = DB::table('transaksipenjualan')->orderBy('id_transaksi_penjualan', 'desc')->first();
                $lastNumber = $lastTransaction ? $lastTransaction->id_transaksi_penjualan : 0;
                $newNumber = $lastNumber + 1;

                $year = Carbon::now()->format('Y');
                $month = Carbon::now()->format('m');

                // Format no nota
                $noNota = $year . '.' . $month . '.' . $newNumber;

                // Simpan no_nota ke database
                $transaksi->no_nota = $noNota;
                $transaksi->save();
            }

            // Siapkan data untuk nota
            $data = [
                'transaksi' => $transaksi,
                'tanggal_cetak' => Carbon::now()->format('d F Y H:i:s'),
                'perusahaan' => [
                    'nama' => 'ReUse Mart',
                    'alamat' => 'Jl. Green Eco Park No. 456 Yogyakarta',
                    'telepon' => '(0274) 123-4567',
                    'email' => 'info@reusermart.com'
                ]
            ];

            // Generate PDF nota
            $pdf = PDF::loadView('gudang.NotaAntarKurir', $data)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true
                ]);

            // Nama file, bisa pakai no_nota supaya lebih mudah
            $namaFile = 'Nota_Transaksi_' . $transaksi->no_nota . '_' . now()->format('YmdHis') . '.pdf';

            // Return sebagai file download
            return $pdf->download($namaFile);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencetak nota: ' . $e->getMessage());
        }
    }

    public function cekStatusHangus(Request $request, $id)
    {
        $transaksi = TransaksiPenjualan::with('detailTransaksi.barang')->findOrFail($id);

        if (!$transaksi->tanggal_ambil) {
            Log::info("Cek status hangus - transaksi ID {$id}: tanggal ambil belum ditentukan.");
            return redirect()->back()->with('error', 'Tanggal ambil belum ditentukan.');
        }

        $tanggalAmbil = Carbon::parse($transaksi->tanggal_ambil);
        $sekarang = Carbon::now();

        // Hitung selisih hari (float, dengan tanda)
        $selisihHari = $sekarang->floatDiffInDays($tanggalAmbil, false);

        Log::info("Cek status hangus - transaksi ID {$id}: tanggal ambil = {$tanggalAmbil}, sekarang = {$sekarang}, selisih hari = {$selisihHari}");

        // Cek apakah sekarang sudah lewat 2 hari dari tanggal ambil
        if ($sekarang->greaterThan($tanggalAmbil->copy()->addDays(2)) && strtolower($transaksi->status_transaksi) !== 'hangus') {
            Log::info("Cek status hangus - transaksi ID {$id}: status akan diubah ke Hangus.");

            DB::transaction(function () use ($transaksi) {
                $transaksi->status_transaksi = 'Hangus';
                $transaksi->save();

                foreach ($transaksi->detailTransaksi as $detail) {
                    if ($detail->barang) {
                        $detail->barang->status_barang = 'di donasikan';
                        $detail->barang->save();
                    }
                }
            });

            Log::info("Cek status hangus - transaksi ID {$id}: status dan barang berhasil diupdate.");

            return redirect()->back()->with('success', 'Status transaksi dan barang berhasil diubah menjadi Hangus dan barang untuk donasi.');
        }

        Log::info("Cek status hangus - transaksi ID {$id}: belum melewati batas waktu 2 hari atau sudah Hangus.");

        return redirect()->back()->with('info', 'Transaksi belum melewati batas waktu 2 hari atau sudah Hangus.');
    }

}