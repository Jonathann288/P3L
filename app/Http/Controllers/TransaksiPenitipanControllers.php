<?php
// COPY SEMUANYA 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\transaksipenitipan;
use App\Models\DetailTransaksiPenitipan;
use App\Models\Barang;
use App\Models\Penitip;
use App\Models\Pegawai;
use App\Models\Kategoribarang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class TransaksiPenitipanControllers extends Controller
{
    public function showBarangTitipan()
    {
        $penitipId = Auth::guard('penitip')->user()->id_penitip;
        $penitip = Penitip::find($penitipId);

        $transaksiIds = TransaksiPenitipan::where('id_penitip', $penitipId)->pluck('id_transaksi_penitipan');

        $detailBarang = DetailTransaksiPenitipan::with(['barang', 'transaksipenitipan'])
            ->whereIn('id_transaksi_penitipan', $transaksiIds)
            ->get();

        return view('penitip.barang-titipan', compact('detailBarang', 'penitip'));
    }

    public function showBarangTitipanLanjutan()
    {
        $penitipId = Auth::guard('penitip')->user()->id_penitip;
        $penitip = Penitip::find($penitipId);

        // Ambil semua transaksi penitipan milik penitip ini
        $transaksiIds = TransaksiPenitipan::where('id_penitip', $penitipId)->pluck('id_transaksi_penitipan');

        // Ambil detail barang yang status_perpanjangan = 1
        $detailBarang = DetailTransaksiPenitipan::with(['barang', 'transaksipenitipan'])
            ->whereIn('id_transaksi_penitipan', $transaksiIds)
            ->where('status_perpanjangan', 1) // hanya yang sudah diperpanjang
            ->get();

        return view('penitip.Perpanjangan_penitipan_lanjutan', compact('detailBarang', 'penitip'));
    }


        public function search(Request $request)
    {   
        $penitip = Auth::guard('penitip')->user();
        $search = $request->input('search');

        if($search){
            $detailBarang = detailtransaksipenitipan::whereHas('barang', function ($query) use ($search) {
                $query->where('nama_barang', 'like', "%{$search}%");
            })
            ->orWhereHas('transaksipenitipan', function ($query) use ($search) {
                // Coba cek search ini cocok dengan tanggal_penitipan atau tanggal_akhir_penitipan
                // Karena input text, kita coba ubah input ke tanggal
                $date = date('Y-m-d', strtotime($search));
                
                // Pastikan tanggal valid supaya query aman
                if ($date) {
                    $query->where('tanggal_penitipan', $date)
                        ->orWhere('tanggal_akhir_penitipan', $date);
                }
            })
            ->get();
        }else{
            $detailBarang = detailtransaksipenitipan::whereHas('transaksipenitipan', function ($query) use ($penitip) {
                $query->where('id_penitip', $penitip->id_penitip);
            })->get();
        }
        return view('penitip.barang-titipan', compact('detailBarang', 'penitip'));
    }

    public function perpanjangMasaPenitipan($id)
    {
        $detail = detailtransaksipenitipan::with('transaksipenitipan')->findOrFail($id);
        $tanggalAkhir =  \Carbon\Carbon::parse($detail->transaksipenitipan->tanggal_akhir_penitipan);
        $sekarang = Carbon::now();

        // Cek jika masa penitipan sudah lewat (tanggal akhir lebih kecil dari sekarang)
        if ($tanggalAkhir->greaterThanOrEqualTo($sekarang)) {
            return redirect()->back()->with('error', 'Masa penitipan belum habis, belum bisa diperpanjang.');
        }

        // Kalau sudah lewat, perpanjang 30 hari lagi
        $tanggalBaru = $tanggalAkhir->addDays(30);

        $detail->transaksipenitipan->update([
            'tanggal_akhir_penitipan' => $tanggalBaru,
        ]);

        // Optional: reset status_perpanjangan jadi false atau hapus kalau ingin
        $detail->update([
            'status_perpanjangan' => false, // atau hapus kolom ini kalau tidak diperlukan
        ]);

        return redirect()->back()->with('success', 'Masa penitipan berhasil diperpanjang 30 hari.');
    } 

    public function perpanjangMasaPenitipanlanjutan($id)
    {
        $detail = DetailTransaksiPenitipan::with(['transaksipenitipan', 'barang'])->findOrFail($id);
        $penitipan = $detail->transaksipenitipan;
        $barang = $detail->barang;

        $tanggalAkhir = Carbon::parse($penitipan->tanggal_akhir_penitipan);
        $tanggalpengambilan = Carbon::parse($penitipan->tanggal_batas_pengambilan);
        $sekarang = Carbon::now();

        // Cek apakah masa penitipan sudah habis
        if ($tanggalAkhir->greaterThanOrEqualTo($sekarang)) {
            return redirect()->back()->with('error', 'Masa penitipan belum habis, belum bisa diperpanjang.');
        }
        if ($tanggalpengambilan->greaterThanOrEqualTo($sekarang)) {
            return redirect()->back()->with('error', 'Masa penitipan belum habis, belum bisa diperpanjang.');
        }

        // Hitung biaya perpanjangan (5% dari harga barang)
        $biayaPerpanjangan = $barang->harga_barang * 0.05;

        // Ambil penitip terkait
        $penitip = Penitip::findOrFail($penitipan->id_penitip);

        // Cek saldo penitip
        if ($penitip->saldo_penitip< $biayaPerpanjangan) {
            return redirect()->back()->with('error', 'Saldo tidak cukup untuk perpanjangan. Diperlukan: Rp' . number_format($biayaPerpanjangan, 0, ',', '.'));
        }

        // Eksekusi transaksi database
        DB::beginTransaction();
        try {
            // Potong saldo penitip
            $penitip->saldo_penitip -= $biayaPerpanjangan;
            $penitip->save();

            // Tambahkan 30 hari masa penitipan
            $penitipan->tanggal_akhir_penitipan = $tanggalAkhir->addDays(30);
            $penitipan->tanggal_batas_pengambilan = $tanggalpengambilan->addDays(7);
            $penitipan->save();

            // Update status_perpanjangan ke 2
            $detail->status_perpanjangan = 2;
            $detail->save();

            DB::commit();
            return redirect()->back()->with('success', 'Perpanjangan berhasil. Saldo telah dipotong sebesar Rp' . number_format($biayaPerpanjangan, 0, ',', '.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperpanjang: ' . $e->getMessage());
        }
    }

    public function aturTanggalPengambilan($id)
    {
        $detail = detailtransaksipenitipan::with('transaksipenitipan', 'barang')->findOrFail($id);

        $tanggalSekarang = Carbon::now();
        $tanggalBatasPengambilan = $tanggalSekarang->copy()->addDays(7);

        // Update tanggal pengambilan & batas pengambilan
        $detail->transaksipenitipan->update([
            'tanggal_pengambilan_barang' => $tanggalSekarang,
            'tanggal_batas_pengambilan' => $tanggalBatasPengambilan,
        ]);

        // Update status barang
        if ($detail->barang) {
            $detail->barang->update([
                'status_barang' => 'Barang akan diambil kembali',
            ]);
        }

        return redirect()->back()->with('success', 'Tanggal pengambilan dan batas pengambilan berhasil diperbarui, status barang juga diperbarui.');
    }

    public function showCatatanPengambilanBarang()
    {   
        $pegawaiLogin = Auth::guard('pegawai')->user();
        $detailBarang = DetailTransaksiPenitipan::with(['barang', 'transaksipenitipan.penitip'])
            ->whereHas('transaksipenitipan', function ($query) {
                $query->whereNotNull('tanggal_pengambilan_barang');
            })
            ->whereHas('barang', function ($query) {
                $query->where('status_barang', 'Barang akan diambil kembali')
                    ->orWhere('status_barang', 'Sudah diambil');
            })
            ->get();

        return view('gudang.DasboardCatatanPengembalianBarang', compact('detailBarang','pegawaiLogin'));
    }

    public function konfirmasiPengambilan($id)
    {
        $detail = DetailTransaksiPenitipan::with(['barang', 'transaksipenitipan'])->findOrFail($id);

        // Update status barang menjadi 'Sudah diambil'
        if ($detail->barang) {
            $detail->barang->update([
                'status_barang' => 'Sudah diambil',
            ]);
        }

        // Update tanggal akhir penitipan menjadi hari ini dan kosongkan batas pengambilan
        if ($detail->transaksipenitipan) {
            $detail->transaksipenitipan->update([
                'tanggal_akhir_penitipan' => now(),
                'tanggal_batas_pengambilan' => null,
            ]);
        }

        return redirect()->back()->with('success', 'Barang berhasil dikonfirmasi telah diambil.');
    }




    // PUNYA MU SOKO KENE DHA LEK PENGEN NGECEK SENG ATAS DEWE PUNYA KU BEDA FUNGSI KARO PUNYA MU
     public function showTitipanBarang(Request $request)
    {   
        $pegawaiLogin = Auth::guard('pegawai')->user();
        $query = TransaksiPenitipan::with('penitip', 'pegawai', 'hunter');  // Gunakan nama class yang benar

        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('tanggal_penitipan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tanggal_akhir_penitipan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tanggal_batas_pengambilan', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tanggal_pengambilan_barang', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('penitip', function ($penitipQuery) use ($searchTerm) {
                        $penitipQuery->where('nama_penitip', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('email_penitip', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('nomor_ktp', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('nomor_telepon_penitip', 'LIKE', "%{$searchTerm}%");
                    })
                    ->orWhereHas('pegawai', function ($pegawaiQuery) use ($searchTerm) {
                        $pegawaiQuery->where('nama_pegawai', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('email_pegawai', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        // Handle filter by status
        if ($request->has('status') && !empty($request->status)) {
            $status = $request->status;

            if ($status === 'diambil') {
                $query->whereNotNull('tanggal_pengambilan_barang');
            } elseif ($status === 'terlambat') {
                $query->whereNull('tanggal_pengambilan_barang')
                    ->where('tanggal_batas_pengambilan', '<', now());
            } elseif ($status === 'dalam_penitipan') {
                $query->whereNull('tanggal_pengambilan_barang')
                    ->where(function ($q) {
                        $q->whereNull('tanggal_batas_pengambilan')
                            ->orWhere('tanggal_batas_pengambilan', '>=', now());
                    });
            }
        }

        $titipans = $query->orderBy('tanggal_penitipan', 'desc')->get();
        $penitips = Penitip::select('id_penitip', 'nama_penitip', 'email_penitip', 'nomor_ktp')
            ->orderBy('nama_penitip')
            ->get();
        $kategoris = KategoriBarang::select('id_kategori', 'nama_kategori', 'nama_sub_kategori')  // Perbaiki nama class
            ->orderBy('nama_kategori')
            ->get();
        $hunters = Pegawai::where('id_jabatan', 6)->orderBy('nama_pegawai')->get();


        return view('gudang.DashboardTitipanBarang', compact('pegawaiLogin','titipans', 'penitips', 'kategoris', 'hunters'));
    }

    public function storeTitipanBarang(Request $request)
    {
        // Debug: Check if files are being received
        \Log::info('Files received:', ['files' => $request->hasFile('foto_barang'), 'all_files' => $request->allFiles()]);

        // Improved validation with better error messages
        $validated = $request->validate([
            'id_penitip' => 'required|exists:penitip,id_penitip',
            'id_hunter' => 'required|exists:pegawai,id_pegawai',
            'id_kategori' => 'required|exists:kategoribarang,id_kategori',
            'tanggal_penitipan' => 'required|date',
            'nama_barang' => 'required|string|max:255',
            'deskripsi_barang' => 'required|string|min:3',
            'harga_barang' => 'required|numeric|min:1',
            'berat_barang' => 'required|numeric|min:0.1',
            'status_barang' => 'required|string|in:tidak laku,di donasikan,laku,donasikan',
            'foto_barang' => 'required|array|min:1|max:5',
            'foto_barang.*' => 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'has_garansi' => 'required|in:ya,tidak',
            'garansi_type' => 'nullable|string|in:6_bulan,1_tahun,2_tahun,custom',
            'garansi_barang' => 'nullable|date|after:tanggal_penitipan',
        ], [
            'id_penitip.required' => 'Penitip harus dipilih',
            'id_hunter.required' => 'Hunter harus dipilih.',
            'id_penitip.exists' => 'Penitip tidak ditemukan',
            'id_hunter.exists' => 'Hunter tidak valid.',
            'id_kategori.required' => 'Kategori barang harus dipilih',
            'foto_barang.required' => 'Minimal 2 foto barang harus diupload',
            'foto_barang.array' => 'Foto barang harus berupa array file',
            'foto_barang.min' => 'Minimal upload 2 foto',
            'foto_barang.max' => 'Maksimal upload 5 foto',
            'foto_barang.*.required' => 'Setiap file foto harus valid',
            'foto_barang.*.file' => 'Setiap item harus berupa file',
            'foto_barang.*.image' => 'File harus berupa gambar',
            'foto_barang.*.mimes' => 'File harus berformat: jpeg, png, jpg, gif, atau webp',
            'foto_barang.*.max' => 'Ukuran file maksimal 2MB',
            'has_garansi.required' => 'Pilihan garansi harus dipilih',
            'garansi_barang.after' => 'Tanggal garansi harus setelah tanggal penitipan',
        ]);

        try {
            DB::beginTransaction();

            // Check authenticated staff
            $pegawai = Auth::guard('pegawai')->user();
            if (!$pegawai) {
                throw new \Exception('Pegawai tidak terautentikasi');
            }

            // Upload photos first with better error handling
            $fotoPaths = [];
            if ($request->hasFile('foto_barang')) {
                foreach ($request->file('foto_barang') as $index => $foto) {
                    if ($foto && $foto->isValid()) {
                        $filename = 'barang_' . time() . '_' . $index . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();

                        try {
                            $foto->move(public_path('images'), $filename);
                            $path = 'images/' . $filename;

                            if ($path) {
                                $fotoPaths[] = $path;
                                \Log::info("Photo uploaded successfully: {$path}");
                            } else {
                                throw new \Exception("Failed to store photo {$index}");
                            }
                        } catch (\Exception $e) {
                            \Log::error("Error uploading photo {$index}: " . $e->getMessage());
                            throw new \Exception("Gagal mengupload foto ke-" . ($index + 1));
                        }
                    } else {
                        \Log::error("Invalid file at index {$index}");
                        throw new \Exception("File foto ke-" . ($index + 1) . " tidak valid");
                    }
                }
            }

            if (empty($fotoPaths)) {
                throw new \Exception('Tidak ada foto yang berhasil diupload');
            }

            \Log::info('All photos uploaded successfully:', $fotoPaths);


            // Calculate dates
            $tanggalPenitipan = Carbon::parse($validated['tanggal_penitipan']);
            $tanggalAkhirPenitipan = $tanggalPenitipan->copy()->addDays(30);
            $tanggalBatasPengambilan = $tanggalAkhirPenitipan->copy()->addDays(7);


            // Calculate garansi_barang
            $garansiBarang = null;
            if ($validated['has_garansi'] === 'ya') {
                if ($request->filled('garansi_barang')) {
                    // Jika tanggal garansi sudah diisi manual
                    $garansiBarang = Carbon::parse($validated['garansi_barang']);
                } else {
                    // Hitung otomatis berdasarkan garansi_type atau default 1 tahun
                    $garansiType = $validated['garansi_type'] ?? '1_tahun';
                    $garansiBarang = $tanggalPenitipan->copy();

                    switch ($garansiType) {
                        case '6_bulan':
                            $garansiBarang->addMonths(6);
                            break;
                        case '2_tahun':
                            $garansiBarang->addYears(2);
                            break;
                        case '1_tahun':
                        default:
                            $garansiBarang->addYear();
                            break;
                    }
                }
            }

            // Save item
            $barang = new Barang();
            $barang->id = $this->generateBarangId();
            $barang->id_kategori = $validated['id_kategori'];
            $barang->nama_barang = $validated['nama_barang'];
            $barang->deskripsi_barang = $validated['deskripsi_barang'];
            $barang->harga_barang = $validated['harga_barang'];
            $barang->berat_barang = $validated['berat_barang'];
            $barang->status_barang = $validated['status_barang'];
            $barang->masa_penitipan = 30;
            $barang->foto_barang = $fotoPaths[0]; // main photo
            $barang->rating_barang = 0;
            $barang->garansi_barang = $garansiBarang;
            $barang->foto_barang = $fotoPaths;

            if (!$barang->save()) {
                throw new \Exception('Gagal menyimpan data barang');
            }

            \Log::info('Item saved successfully:', [
                'id_barang' => $barang->id_barang,
                'garansi_barang' => $garansiBarang ? $garansiBarang->format('Y-m-d H:i:s') : 'No warranty'
            ]);

            // Save consignment transaction
            $titipan = new TransaksiPenitipan();
            $titipan->id = $this->generateCustomId();
            $titipan->id_pegawai = $pegawai->id_pegawai;
            $titipan->id_penitip = $validated['id_penitip'];
            $titipan->id_hunter = $validated['id_hunter'];
            $titipan->tanggal_penitipan = $tanggalPenitipan;
            $titipan->tanggal_akhir_penitipan = $tanggalAkhirPenitipan;
            $titipan->tanggal_batas_pengambilan = $tanggalBatasPengambilan;
            $titipan->tanggal_pengambilan_barang = null;

            if (!$titipan->save()) {
                throw new \Exception('Gagal menyimpan data transaksi penitipan');
            }

            \Log::info('Consignment transaction saved successfully:', ['id' => $titipan->id_transaksi_penitipan]);

            // Save transaction details
            $detailTransaksi = new DetailTransaksiPenitipan();
            $detailTransaksi->id_transaksi_penitipan = $titipan->id_transaksi_penitipan;
            $detailTransaksi->id_barang = $barang->id_barang;

            if (!$detailTransaksi->save()) {
                throw new \Exception('Gagal menyimpan detail transaksi');
            }

            DB::commit();

            return redirect()->route('gudang.DashboardTitipanBarang')
                ->with('success', 'Transaksi penitipan berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollback();

            // Clean up uploaded photos on error
            if (!empty($fotoPaths)) {
                foreach ($fotoPaths as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                        \Log::info("Cleaned up photo: {$path}");
                    }
                }
            }

            \Log::error('Error in storeTitipanBarang:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'input' => $request->except(['foto_barang']), // Don't log file data
                'files' => $request->hasFile('foto_barang') ? 'Files present' : 'No files'
            ]);

            return redirect()->back()
                ->withInput($request->except(['foto_barang'])) // Don't return file input
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());

        }
    }

    private function validateFileUpload(Request $request)
    {
        // Check if files exist
        if (!$request->hasFile('foto_barang')) {
            throw new \Exception('Tidak ada file yang diupload');
        }

        $files = $request->file('foto_barang');

        // Ensure it's an array
        if (!is_array($files)) {
            $files = [$files];
        }

        // Check each file
        foreach ($files as $index => $file) {
            if (!$file) {
                throw new \Exception("File ke-" . ($index + 1) . " kosong");
            }

            if (!$file->isValid()) {
                throw new \Exception("File ke-" . ($index + 1) . " tidak valid: " . $file->getErrorMessage());
            }

            // Check file type
            $allowedMimes = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($extension, $allowedMimes)) {
                throw new \Exception("File ke-" . ($index + 1) . " harus berformat: " . implode(', ', $allowedMimes));
            }

            // Check file size (2MB = 2048KB)
            if ($file->getSize() > 2048 * 1024) {
                throw new \Exception("File ke-" . ($index + 1) . " terlalu besar. Maksimal 2MB");
            }

            // Check if it's actually an image
            $imageInfo = @getimagesize($file->getPathname());
            if (!$imageInfo) {
                throw new \Exception("File ke-" . ($index + 1) . " bukan gambar yang valid");
            }
        }

        return $files;
    }

    private function generateCustomId()
    {
        $last = DB::table('TransaksiPenitipan')
            ->select('id')
            ->where('id', 'like', 'T%')
            ->orderByDesc(DB::raw('CAST(SUBSTRING(id, 2) AS UNSIGNED)'))
            ->first();

        $newNumber = $last ? ((int) substr($last->id, 1)) + 1 : 1;
        $newId = 'T' . str_pad($newNumber, 3, '0', STR_PAD_LEFT); // hasil: T001, T012, T099, dst

        return $newId;
    }


    private function generateBarangId()
    {
        $lastBarang = DB::table('Barang')
            ->select('id')
            ->where('id', 'like', 'B%')
            ->orderByDesc(DB::raw('CAST(SUBSTRING(id, 2) AS UNSIGNED)'))
            ->first();

        $newNumber = $lastBarang ? ((int) substr($lastBarang->id, 1)) + 1 : 1;
        $newId = 'B' . $newNumber;

        return $newId;
    }


    public function createTitipanBarang()
    {
        $penitips = Penitip::select('id_penitip', 'nama_penitip', 'email_penitip', 'nomor_ktp')
            ->orderBy('nama_penitip')
            ->get();

        $titipans = TransaksiPenitipan::with('penitip', 'pegawai')  // Gunakan nama class yang benar
            ->orderBy('tanggal_penitipan', 'desc')
            ->get();

        $kategoris = KategoriBarang::select('id_kategori', 'nama_kategori', 'nama_sub_kategori')  // Gunakan nama class yang benar
            ->orderBy('nama_kategori')
            ->get();

        $hunters = Pegawai::where('id_jabatan', 6)->orderBy('nama_pegawai')->get();

        return view('gudang.DashboardTitipanBarang', compact('titipans', 'penitips', 'kategoris', 'hunters'));
    }


    /**
     * Hitung durasi penitipan otomatis
     */
    public function hitungDurasiPenitipan($tanggalMasukGudang)
    {
        $tanggalMasuk = Carbon::parse($tanggalMasukGudang);
        $tanggalAkhir = $tanggalMasuk->copy()->addDays(30);
        $tanggalBatasPengambilan = $tanggalAkhir->copy()->addDays(7);

        return [
            'tanggal_penitipan' => $tanggalMasuk,
            'tanggal_akhir_penitipan' => $tanggalAkhir,
            'tanggal_batas_pengambilan' => $tanggalBatasPengambilan,
            'durasi_hari' => 30,
            'grace_period_hari' => 7
        ];
    }

    /**
     * API endpoint untuk mendapatkan durasi penitipan
     */
    public function getDurasiPenitipan(Request $request)
    {
        $tanggalMasuk = $request->input('tanggal_masuk');

        if (!$tanggalMasuk) {
            return response()->json(['error' => 'Tanggal masuk gudang diperlukan'], 400);
        }

        try {
            $durasi = $this->hitungDurasiPenitipan($tanggalMasuk);

            return response()->json([
                'success' => true,
                'data' => [
                    'tanggal_penitipan' => $durasi['tanggal_penitipan']->format('Y-m-d'),
                    'tanggal_akhir_penitipan' => $durasi['tanggal_akhir_penitipan']->format('Y-m-d'),
                    'tanggal_batas_pengambilan' => $durasi['tanggal_batas_pengambilan']->format('Y-m-d'),
                    'durasi_hari' => $durasi['durasi_hari'],
                    'grace_period_hari' => $durasi['grace_period_hari'],
                    'info' => "Masa penitipan: {$durasi['durasi_hari']} hari + {$durasi['grace_period_hari']} hari grace period"
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Format tanggal tidak valid'], 400);
        }
    }

    /**
     * Pencarian transaksi dengan berbagai kriteria
     */
    // Dalam class TransaksiPenitipanControllers

    // Dalam class TransaksiPenitipanControllers

    public function searchTitipan(Request $request)
    {
        $query = transaksipenitipan::with([
            'penitip',
            'pegawai',
            'hunter',
            'detailTransaksiPenitipan.barang.detailTransaksiPenjualan.transaksipenjualan',
            'detailTransaksiPenitipan.barang.donasi'
        ]);

        // ... (kode search term, tanggal, penitip, pegawai tetap sama) ...
        if ($request->filled('search_term')) {
            $searchTerm = $request->search_term;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id_transaksi_penitipan', 'LIKE', "%{$searchTerm}%") // [cite: 153]
                    ->orWhere('tanggal_penitipan', 'LIKE', "%{$searchTerm}%") // [cite: 153]
                    ->orWhere('tanggal_akhir_penitipan', 'LIKE', "%{$searchTerm}%") // [cite: 153]
                    ->orWhere('tanggal_batas_pengambilan', 'LIKE', "%{$searchTerm}%") // [cite: 154]
                    ->orWhere('tanggal_pengambilan_barang', 'LIKE', "%{$searchTerm}%") // [cite: 154]
                    ->orWhereHas('penitip', function ($penitipQuery) use ($searchTerm) { // [cite: 154]
                        $penitipQuery->where('nama_penitip', 'LIKE', "%{$searchTerm}%") // [cite: 155]
                            ->orWhere('email_penitip', 'LIKE', "%{$searchTerm}%") // [cite: 155]
                            ->orWhere('nomor_ktp', 'LIKE', "%{$searchTerm}%") // [cite: 155]
                            ->orWhere('nomor_telepon_penitip', 'LIKE', "%{$searchTerm}%"); // [cite: 155]
                    })
                    ->orWhereHas('pegawai', function ($pegawaiQuery) use ($searchTerm) { // [cite: 156]
                        $pegawaiQuery->where('nama_pegawai', 'LIKE', "%{$searchTerm}%") // [cite: 156]
                            ->orWhere('email_pegawai', 'LIKE', "%{$searchTerm}%"); // [cite: 157]
                    })
                    ->orWhereHas('detailTransaksiPenitipan.barang', function ($barangQuery) use ($searchTerm) {
                        $barangQuery->where('nama_barang', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal_penitipan', [
                $request->tanggal_dari, // [cite: 158]
                $request->tanggal_sampai // [cite: 158]
            ]);
        }

        if ($request->filled('id_penitip')) {
            $query->where('id_penitip', $request->id_penitip); // [cite: 159]
        }

        if ($request->filled('id_pegawai')) {
            $query->where('id_pegawai', $request->id_pegawai); // [cite: 160]
        }


        // Filter berdasarkan status BARU
        if ($request->filled('status')) {
            $status = $request->status;
            $today = \Carbon\Carbon::now(); // [cite: 162, 311]

            switch ($status) {
                case 'tidak_laku':
                    // Barang dengan status_barang = 'tidak laku' ATAU
                    // masih dalam masa penitipan & belum laku & belum donasi & belum diambil penitip
                    $query->where(function ($q) use ($today) {
                        $q->whereHas('detailTransaksiPenitipan.barang', function ($barangQuery) {
                            $barangQuery->where('status_barang', 'tidak laku');
                        })
                            ->orWhere(function ($q2) use ($today) { // Masih dalam periode, belum ada aksi lain
                                $q2->where('tanggal_akhir_penitipan', '>=', $today) // Masih dalam masa penitipan
                                    ->whereNull('tanggal_pengambilan_barang'); // Belum diambil penitip
                            });
                    })
                        ->whereDoesntHave('detailTransaksiPenitipan.barang.detailTransaksiPenjualan') // Belum laku
                        ->whereDoesntHave('detailTransaksiPenitipan.barang.donasi', function ($donasiQuery) { // Belum ada request donasi aktif
                            $donasiQuery->whereNotNull('id_request');
                        });
                    break;

                case 'laku':
                    $query->whereHas('detailTransaksiPenitipan.barang.detailTransaksiPenjualan.transaksipenjualan', function ($tpQuery) {
                        // Logika ini bisa disesuaikan jika 'laku' punya definisi lebih spesifik
                        // selain 'akan diambil' atau 'sudah diambil'
                        $tpQuery->whereNotNull('id_transaksi_penjualan');
                    })
                        // Pastikan tidak tumpang tindih dengan 'akan diambil' atau 'sudah diambil' jika diperlukan pemisahan ketat
                        ->whereDoesntHave('detailTransaksiPenitipan.barang.detailTransaksiPenjualan.transaksipenjualan', function ($tpQuery) {
                            $tpQuery->where('metode_pengantaran', 'Ambil di Gudang');
                        });
                    break;

                case 'di_donasikan': // Sudah melewati tanggal_batas_pengambilan dan belum diambil/laku/donasi aktif
                    $query->where('tanggal_batas_pengambilan', '<', $today) // [cite: 72, 165]
                        ->whereNull('tanggal_pengambilan_barang') // Belum diambil penitip [cite: 72]
                        ->whereDoesntHave('detailTransaksiPenitipan.barang.detailTransaksiPenjualan') // Belum laku [cite: 163]
                        ->whereDoesntHave('detailTransaksiPenitipan.barang.donasi', function ($donasiQuery) { // Belum ada request donasi aktif [cite: 163, 165]
                            $donasiQuery->whereNotNull('id_request');
                        });
                    break;

                case 'donasikan': // Barang yang memiliki id_request pada table donasi
                    $query->whereHas('detailTransaksiPenitipan.barang.donasi', function ($donasiQuery) {
                        $donasiQuery->whereNotNull('id_request');
                    });
                    break;

                case 'akan_diambil': // metode_pengantaran = Ambil di Gudang, status_pembayaran != lunas
                    $query->whereHas('detailTransaksiPenitipan.barang.detailTransaksiPenjualan.transaksipenjualan', function ($tpQuery) {
                        $tpQuery->where('metode_pengantaran', 'Ambil di Gudang')
                            ->where('status_pembayaran', '!=', 'lunas');
                    });
                    break;

                case 'sudah_diambil': // metode_pengantaran = Ambil di Gudang, status_pembayaran = lunas
                    $query->whereHas('detailTransaksiPenitipan.barang.detailTransaksiPenjualan.transaksipenjualan', function ($tpQuery) {
                        $tpQuery->where('metode_pengantaran', 'Ambil di Gudang')
                            ->where('status_pembayaran', 'lunas');
                    });
                    break;

                case 'diambil_kembali_penitip':
                    $query->whereNotNull('tanggal_pengambilan_barang');
                    break;

                // Tidak ada lagi case 'dalam_penitipan'
            }
        }

        $results = $query->orderBy('tanggal_penitipan', 'desc')->get();

        // ... (sisa kode untuk return view tetap sama) ...
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $results,
                'count' => $results->count(),
                'message' => "Ditemukan {$results->count()} transaksi"
            ]);
        }
        $penitips = Penitip::select('id_penitip', 'nama_penitip', 'email_penitip', 'nomor_ktp')
            ->orderBy('nama_penitip')
            ->get();

        $kategoris = KategoriBarang::select('id_kategori', 'nama_kategori', 'nama_sub_kategori')
            ->orderBy('nama_kategori')
            ->get();
        $hunters = Pegawai::where('id_jabatan', 6)->orderBy('nama_pegawai')->get();
        return view('gudang.DashboardTitipanBarang', [
            'titipans' => $results,
            'searchTerm' => $request->search_term,
            'penitips' => $penitips,
            'kategoris' => $kategoris,
            'hunters' => $hunters,
            'request' => $request
        ]);
    }

    public function updateTitipanBarang(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_penitip' => 'required|string|max:255',
            'email_penitip' => 'required|email|max:255',
            'id_hunter' => 'required|exists:pegawai,id_pegawai',
            'tanggal_penitipan' => 'required|date',
            'tanggal_akhir_penitipan' => 'nullable|date',
            'tanggal_batas_pengambilan' => 'nullable|date',
            'tanggal_pengambilan_barang' => 'nullable|date',
            'nama_barang' => 'required|string|max:255',
            'deskripsi_barang' => 'required|string|min:3',
            'harga_barang' => 'required|numeric|min:1',
            'berat_barang' => 'required|numeric|min:0.1',
            'status_barang' => 'required|string|in:tidak laku,di donasikan,laku,donasikan',
            'has_garansi' => 'required|in:ya,tidak',
            'garansi_type' => 'nullable|string|in:6_bulan,1_tahun,2_tahun,custom',
            'garansi_barang' => 'nullable|date|after:tanggal_penitipan',
            'foto_barang.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'auto_calculate' => 'nullable|boolean'
        ]);

        try {
            $titipan = TransaksiPenitipan::with('detailTransaksiPenitipan.barang')->findOrFail($id);

            // Update data penitip
            $penitip = $titipan->penitip;
            if ($penitip) {
                $penitip->nama_penitip = $validated['nama_penitip'];
                $penitip->email_penitip = $validated['email_penitip'];
                $penitip->save();
            }

            // Hitung durasi otomatis jika diminta
            if ($request->auto_calculate) {
                $durasi = $this->hitungDurasiPenitipan($validated['tanggal_penitipan']);
                $titipan->tanggal_penitipan = $durasi['tanggal_penitipan'];
                $titipan->tanggal_akhir_penitipan = $durasi['tanggal_akhir_penitipan'];
                $titipan->tanggal_batas_pengambilan = $durasi['tanggal_batas_pengambilan'];
            } else {
                $titipan->tanggal_penitipan = $validated['tanggal_penitipan'];
                $titipan->tanggal_akhir_penitipan = $validated['tanggal_akhir_penitipan'];
                $titipan->tanggal_batas_pengambilan = $validated['tanggal_batas_pengambilan'];
            }
            $titipan->id_hunter = $validated['id_hunter'] ;
            $titipan->tanggal_pengambilan_barang = $validated['tanggal_pengambilan_barang'] ?? null;

            $titipan->save();

            // Update semua barang terkait
            foreach ($titipan->detailTransaksiPenitipan as $index => $detail) {
                $barang = $detail->barang;
                if ($barang) {
                    $barang->nama_barang = $validated['nama_barang'];
                    $barang->deskripsi_barang = $validated['deskripsi_barang'];
                    $barang->harga_barang = $validated['harga_barang'];
                    $barang->berat_barang = $validated['berat_barang'];
                    $barang->status_barang = $validated['status_barang'];

                    // Hitung garansi
                    $tanggalPenitipan = Carbon::parse($validated['tanggal_penitipan']);
                    $garansiBarang = null;

                    if ($validated['has_garansi'] === 'ya') {
                        if ($request->filled('garansi_barang')) {
                            $garansiBarang = Carbon::parse($validated['garansi_barang']);
                        } else {
                            $garansiType = $validated['garansi_type'] ?? '1_tahun';
                            $garansiBarang = $tanggalPenitipan->copy();

                            switch ($garansiType) {
                                case '6_bulan':
                                    $garansiBarang->addMonths(6);
                                    break;
                                case '1_tahun':
                                default:
                                    $garansiBarang->addYear();
                                    break;
                            }
                        }
                    }

                    $barang->garansi_barang = $garansiBarang;

                    // Handle update foto jika ada
                    if ($request->hasFile("foto_barang.{$index}")) {
                        $this->deleteOldPhotos($barang);
                        $foto = $request->file("foto_barang.{$index}");

                        if ($foto && $foto->isValid()) {
                            $filename = 'barang_' . time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                            $foto->move(public_path('images'), $filename);
                            $barang->foto_barang = ['images/' . $filename];
                        }
                    }

                    $barang->save();
                }
            }

            return redirect()->route('gudang.DashboardTitipanBarang')->with('success', 'Data titipan berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    private function deleteOldPhotos($titipan)
    {
        $oldFotos = $titipan->foto_barang;

        if (empty($oldFotos)) {
            return;
        }

        // Pastikan $oldFotos adalah array
        if (is_string($oldFotos)) {
            $oldFotos = json_decode($oldFotos, true);
        }

        if (!is_array($oldFotos)) {
            return;
        }

        // Hapus setiap file foto lama
        foreach ($oldFotos as $oldFoto) {
            if (!empty($oldFoto)) {
                $cleanPath = trim($oldFoto);

                // Cek dan hapus file dari storage
                if (Storage::disk('public')->exists($cleanPath)) {
                    Storage::disk('public')->delete($cleanPath);
                }
            }
        }
    }

    public function deleteTitipanBarang($id)
    {
        try {
            $titipan = transaksipenitipan::findOrFail($id);

            // Hapus foto-foto terkait
            $this->deleteOldPhotos($titipan);

            // Hapus record dari database
            $titipan->delete();

            return redirect()->route('gudang.DashboardTitipanBarang')->with('success', 'Data titipan berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cetakNota($id)
    {
        try {
            // Ambil data transaksi dengan relasi
            $titipan = transaksipenitipan::with(['penitip', 'pegawai'])->findOrFail($id);

            // Data untuk PDF
            $data = [
                'titipan' => $titipan,
                'tanggal_cetak' => Carbon::now()->format('d F Y H:i:s'),
                'perusahaan' => [
                    'nama' => 'Gudang Titipan Barang',
                    'alamat' => 'Jl. Contoh No. 123, Kota ABC',
                    'telepon' => '(021) 1234-5678',
                    'email' => 'info@gudangtitipan.com'
                ]
            ];

            // Generate PDF dengan orientasi portrait dan ukuran A4
            $pdf = PDF::loadView('gudang.CetakNota', $data)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true
                ]);


            // Nama file
            $namaFile = 'Nota_Penitipan_' . $titipan->id . '_' . Carbon::now()->format('YmdHis') . '.pdf';

            // Return sebagai download
            return $pdf->download($namaFile);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mencetak nota: ' . $e->getMessage());
        }
    }

    public function showDaftarBarang()
    {
        // Ambil data pegawai yang sedang login (sesuaikan dengan sistem autentikasi Anda)
        $pegawai = auth()->user(); // atau sesuai dengan cara Anda mengambil data pegawai

        // Atau jika menggunakan session/cara lain:
        // $pegawai = Pegawai::find(session('id_pegawai'));

        // Ambil semua barang dengan relasi yang diperlukan
        $barang = Barang::with([
            'kategoribarang',
            'detailTransaksiPenitipan.transaksiPenitipan.pegawai',
            'detailTransaksiPenitipan.transaksiPenitipan.penitip'
        ])->get();

        foreach ($barang as $item) {
            $item->foto_barang = $item->getFotoBarangAttribute($item->foto_barang);
        }

        return view('gudang.DaftarBarang', compact('pegawai', 'barang'));
    }

    // Alternatif jika Anda ingin menampilkan barang yang ditangani oleh pegawai tertentu
    public function showDaftarBarangByPegawai()
    {
        // Ambil data pegawai yang sedang login
        $pegawai = auth()->user(); // sesuaikan dengan sistem autentikasi Anda

        // Ambil barang yang ditangani oleh pegawai ini
        $barang = Barang::whereHas('detailTransaksiPenitipan.transaksiPenitipan', function ($query) use ($pegawai) {
            $query->where('id_pegawai', $pegawai->id_pegawai);
        })->with([
                    'kategoribarang',
                    'detailTransaksiPenitipan.transaksiPenitipan.pegawai',
                    'detailTransaksiPenitipan.transaksiPenitipan.penitip'
                ])->get();

        return view('gudang.DaftarBarang', compact('pegawai', 'barang'));
    }

}