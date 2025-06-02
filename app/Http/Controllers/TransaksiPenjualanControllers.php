<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\Keranjang;
use App\Models\Alamat;
use App\Models\transaksipenjualan;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
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

        // Validasi poin yang digunakan
        $request->validate([
            'poin_digunakan' => 'nullable|integer|min:0|max:' . $pembeli->total_poin,
        ], [
            'poin_digunakan.max' => 'Poin yang digunakan tidak boleh melebihi total poin Anda (' . number_format($pembeli->total_poin, 0, ',', '.') . ' poin).',
            'poin_digunakan.min' => 'Poin yang digunakan tidak boleh kurang dari 0.',
            'poin_digunakan.integer' => 'Poin yang digunakan harus berupa angka.',
        ]);

        $keranjangKey = 'keranjang_' . $pembeli->id;
        $keranjang = session()->get($keranjangKey, []);
        if (empty($keranjang)) {
            return redirect()->route('pembeli.cart')->with('error', 'Keranjang Anda kosong.');
        }

        // Validasi request
        $validationRules = [
            'metode_pengantaran' => 'required|in:ambil_sendiri,diantar_kurir',
        ];
        if ($request->metode_pengantaran === 'diantar_kurir') {
            $validationRules['selected_address'] = 'required|exists:alamat,id';
        }
        $request->validate($validationRules, [
            'metode_pengantaran.required' => 'Silakan pilih metode pengantaran.',
            'metode_pengantaran.in' => 'Metode pengantaran tidak valid.',
            'selected_address.required' => 'Silakan pilih alamat pengiriman.',
            'selected_address.exists' => 'Alamat yang dipilih tidak valid.',
        ]);

        DB::beginTransaction();
        try {
            // Hitung total belanja (semua item quantity = 1)
            $subtotal = collect($keranjang)->sum(function ($item) {
                return $item['harga_barang']; // Tidak perlu dikali jumlah karena selalu 1
            });

            // Hitung ongkir
            $ongkir = 0;
            $metode_pengantaran = $request->metode_pengantaran;
            if ($metode_pengantaran === 'diantar_kurir') {
                $ongkir = $subtotal >= 1500000 ? 0 : 100000;
            }

            // Hitung poin yang digunakan (jika ada)
            $poin_digunakan = intval($request->input('poin_digunakan', 0));
            $diskon_poin = 0;

            // Validasi ulang poin yang digunakan (double check)
            if ($poin_digunakan > 0) {
                if ($poin_digunakan > $pembeli->total_poin) {
                    throw new \Exception('Poin yang digunakan melebihi total poin Anda.');
                }

                // Konversi poin ke diskon (contoh: 1 poin = Rp 100)
                $diskon_poin = $poin_digunakan * 100; // Sesuaikan dengan nilai konversi poin Anda
                if ($diskon_poin > $subtotal) {
                    $diskon_poin = $subtotal; // Jangan sampai diskon melebihi subtotal
                    $poin_digunakan = $subtotal / 100; // Recalculate poin yang benar-benar digunakan
                }
            }

            $total = $subtotal + $ongkir - $diskon_poin;
            $poin_didapat = floor($total / 10000); // Hitung poin yang didapat dari transaksi ini

            // Tentukan tanggal kirim
            $tanggal_kirim = null;
            if ($metode_pengantaran === 'diantar_kurir') {
                $tanggal_kirim = now()->addDays(2);
            }

            // Generate ID transaksi
            $lastTransaction = transaksipenjualan::where('id', 'like', 'TP%')
                ->orderBy('id', 'desc')
                ->first();
            if (!$lastTransaction) {
                $newId = 'TP01';
            } else {
                $lastNumber = (int) substr($lastTransaction->id, 2);
                $nextNumber = $lastNumber + 1;
                $formattedNumber = str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
                $newId = 'TP' . $formattedNumber;
            }

            $id_pegawai = ($metode_pengantaran === 'diantar_kurir') ? 5 : 4;

            // KURANGI POIN PEMBELI TERLEBIH DAHULU (SEBELUM MEMBUAT TRANSAKSI)
            if ($poin_digunakan > 0) {
                // Refresh data pembeli untuk memastikan data terbaru
                $pembeli->refresh();

                // Pastikan poin masih cukup
                if ($poin_digunakan > $pembeli->total_poin) {
                    throw new \Exception('Poin tidak mencukupi. Poin Anda saat ini: ' . $pembeli->total_poin);
                }

                // Kurangi poin pembeli
                $pembeli->total_poin -= $poin_digunakan;
                $pembeli->save();

                // Log untuk debugging
                \Log::info('Poin pembeli dikurangi', [
                    'id_pembeli' => $pembeli->id_pembeli,
                    'poin_digunakan' => $poin_digunakan,
                    'total_poin_sebelum' => $pembeli->total_poin + $poin_digunakan,
                    'total_poin_sesudah' => $pembeli->total_poin
                ]);
            }

            // Buat transaksi baru
            $transaksi = transaksipenjualan::create([
                'id' => $newId,
                'id_pembeli' => $pembeli->id_pembeli,
                'id_pegawai' => $id_pegawai,
                'tanggal_transaksi' => now(),
                'metode_pengantaran' => $metode_pengantaran,
                'tanggal_lunas' => null,
                'bukti_pembayaran' => null,
                'status_pembayaran' => 'Belum Lunas',
                'poin' => $poin_didapat,
                'poin_digunakan' => $poin_digunakan, // Simpan poin yang digunakan
                'diskon_poin' => $diskon_poin, // Simpan diskon dari poin
                'tanggal_kirim' => $tanggal_kirim,
                'ongkir' => $ongkir,
                'status_transaksi' => 'diproses'
            ]);

            // PROSES UPDATE STATUS BARANG dan detail transaksi
            foreach ($keranjang as $id_barang => $item) {
                $barang = Barang::where('id_barang', $id_barang)->first();
                if (!$barang) {
                    continue;
                }
                // Subtotal item selalu harga barang x 1
                $subtotalItem = $barang->harga_barang * 1;
                $detailTransaksi = $transaksi->detailTransaksi()->create([
                    'id_barang' => $barang->id_barang,
                    'jumlah' => 1,
                    'harga' => $barang->harga_barang,
                    'subtotal' => $subtotalItem,
                    'total_harga' => $subtotalItem,
                ]);
                $barang->status_barang = 'laku';
                $barang->save();
            }

            // Kosongkan keranjang
            session()->forget($keranjangKey);

            DB::commit();
            return redirect()->route('pembayaranPembeli', ['id' => $transaksi->id])
                ->with('success', 'Transaksi berhasil diproses dengan ID: ' . $newId .
                    ($poin_digunakan > 0 ? '. Poin sebesar ' . number_format($poin_digunakan, 0, ',', '.') . ' telah digunakan.' : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pembeli.cart')
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses checkout: ' . $e->getMessage());
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
            if ($transaksi->poin_digunakan > 0) {
                $pembeli = $transaksi->pembeli;
                $pembeli->total_poin += $transaksi->poin_digunakan;
                $pembeli->save();

                \Log::info('Poin dikembalikan', [
                    'pembeli_id' => $pembeli->id_pembeli,
                    'poin_dikembalikan' => $transaksi->poin_digunakan,
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

            // Gunakan firstOrFail() untuk mendapatkan error lebih jelas
            $transaksi = TransaksiPenjualan::where('id', $id)->firstOrFail();

            $transaksi->update([
                'status_pembayaran' => 'Lunas',
                'status_transaksi' => 'Di siapkan'
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil di-approve.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Transaksi dengan ID ' . $id . ' tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengapprove transaksi: ' . $e->getMessage());
        }
    }


     public function showTransaksiKirim()
    {
        try {
            $pegawaiLogin = Auth::guard('pegawai')->user();

            // Ambil transaksi dengan eager loading
            $transaksiAntar = TransaksiPenjualan::with(['detailTransaksi.barang', 'pembeli'])
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

    // public function jadwalPengiriman(Request $request)
    // {
    //     $request->validate([
    //         'transaksi_id' => 'required|exists:transaksipenjualan,id_transaksi_penjualan',
    //         'tanggal_kirim' => 'required|date|after_or_equal:today',
    //         'id_kurir' => 'required|exists:pegawai,id',
    //     ]);

    //     $kurir = Pegawai::findOrFail($request->id_kurir);

    //     if (strtolower($kurir->jabatan) !== 'kurir') {
    //         return back()->withErrors('Pegawai terpilih bukan kurir.');
    //     }

    //     $transaksi = TransaksiPenjualan::findOrFail($request->transaksi_id);

    //     $jamTransaksi = Carbon::parse($transaksi->tanggal_transaksi)->hour;

    //     if ($jamTransaksi >= 16) {
    //         $tanggalDipilih = Carbon::parse($request->tanggal_kirim);
    //         $tanggalTransaksi = Carbon::parse($transaksi->tanggal_transaksi);

    //         if ($tanggalDipilih->isSameDay($tanggalTransaksi)) {
    //             return back()->withErrors('Transaksi setelah jam 16.00 hanya bisa dikirim mulai hari berikutnya.');
    //         }
    //     }

    //     $transaksi->update([
    //         'tanggal_kirim' => $request->tanggal_kirim,
    //         'id_kurir' => $kurir->id,
    //     ]);

    //     return back()->with('success', 'Pengiriman berhasil dijadwalkan.');
    // }

    public function jadwalPengiriman(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksipenjualan,id_transaksi_penjualan',
            'id_kurir' => 'required|exists:pegawai,id_pegawai',
        ]);

        $kurir = Pegawai::findOrFail($request->id_kurir);

        if (strtolower($kurir->jabatan) !== 'Kurir') {
            return back()->withErrors('Pegawai terpilih bukan kurir.');
        }

        $transaksi = TransaksiPenjualan::findOrFail($request->transaksi_id);

        $tanggalTransaksi = Carbon::parse($transaksi->tanggal_transaksi);
        $jamTransaksi = $tanggalTransaksi->hour;

        // Tentukan tanggal kirim berdasarkan jam transaksi
        if ($jamTransaksi >= 16) {
            $tanggalKirim = $tanggalTransaksi->copy()->addDay()->startOfDay();
        } else {
            $tanggalKirim = $tanggalTransaksi->copy()->startOfDay();
        }
        \Log::info('Updating transaksi', [
            'id_transaksi_penjualan' => $transaksi->id_transaksi_penjualan,
            'tanggal_kirim' => $tanggalKirim,
            'id_kurir' => $kurir->id_kurir,
        ]);
        $transaksi->update([
            'tanggal_kirim' => $tanggalKirim,
            'id_kurir' => $kurir->id_kurir,
        ]);

        return back()->with('success', 'Pengiriman berhasil dijadwalkan.');
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
        try {
            $transaksi = TransaksiPenjualan::where('id_transaksi_penjualan', $id)->firstOrFail();

            $transaksi->update([
                'status_transaksi' => 'transaksi selesai',
            ]);

            return back()->with('success', 'Transaksi berhasil dikonfirmasi selesai.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Transaksi tidak ditemukan.');
        } catch (\Exception $e) {
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

    public function cekStatusHangus($id)
    {
        try {
            $transaksi = transaksipenjualan::with('detailTransaksi.barang')->findOrFail($id);

            // Pastikan tanggal ambil tidak null
            if (!$transaksi->tanggal_ambil) {
                return redirect()->back()->with('error', 'Tanggal ambil belum ditentukan.');
            }

            $tanggalAmbil = Carbon::parse($transaksi->tanggal_ambil);
            $sekarang = Carbon::now();

            // Hitung selisih hari
            $selisihHari = $sekarang->diffInDays($tanggalAmbil);

            if ($selisihHari > 2 && $transaksi->status_transaksi !== 'Hangus') {
                DB::transaction(function () use ($transaksi) {
                    // Update status transaksi jadi Hangus
                    $transaksi->status_transaksi = 'Hangus';
                    $transaksi->save();

                    // Update status barang di detail transaksi
                    foreach ($transaksi->detailTransaksi as $detail) {
                        if ($detail->barang) {
                            $detail->barang->status = 'di donasikan';
                            $detail->barang->save();
                        }
                    }
                });

                return redirect()->back()->with('success', 'Status transaksi dan barang berhasil diubah menjadi Hangus dan barang untuk donasi.');
            }

            return redirect()->back()->with('info', 'Transaksi belum melewati batas waktu 2 hari atau sudah Hangus.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

}