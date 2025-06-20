<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gudang</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen"
    x-data="titipanHandler()">

    <!-- Sidebar Navigation -->
    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-semibold mb-8">Gudang</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('gudang.DashboardGudang') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <span class="text-2xl font-bold">{{ $pegawaiLogin->nama_pegawai }}</span>
                    </a>
                    <a href="{{ route('gudang.DashboardTitipanBarang') }}"
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fas fa-dolly mr-2"></i>
                        <span>Tambah Titip Barang</span>
                    </a>
                    <a href="{{ route('gudang.DaftarBarang') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-boxes mr-2"></i>
                        <span>Daftar Barang</span>
                    </a>
                    <a href="{{ route('gudang.DasboardCatatanPengembalianBarang') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-boxes mr-2"></i>
                        <span>Catatan Pengembalian Barang</span>
                    </a>
                    <a href="{{ route('gudang.DashboardShowTransaksiAntarAmbil') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-truck"></i>
                        <span>Daftar Transakasi Kirim dan Ambil sendiri</span>
                    </a>
                </div>
            </nav>
        </div>
        <div class="space-y-4 mt-auto">
            <button class="w-full py-2 bg-red-600 text-white rounded-lg hover:bg-red-500">Keluar</button>
        </div>
    </div>



    <!-- Main Content -->
    <div class="p-8 bg-gray-100">

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1 class="text-3xl font-bold mb-6">Daftar Titipan Barang</h1>
        <div class="flex justify-end mb-4">
            <button @click="showCreateModal = true"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                + Tambah Titipan Barang
            </button>
        </div>

        <!-- Modal Tambah Titipan Barang -->
        <div x-show="showCreateModal" x-transition
            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
            style="display: none;">
            <div class="bg-white rounded-lg w-full max-w-xl overflow-y-auto max-h-[90vh] p-6"
                @click.away="showCreateModal = false">

                <h2 class="text-xl font-bold text-gray-800 mb-4">Tambah Titipan Barang</h2>

                {{-- Error Validation --}}
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('gudang.StoreTitipanBarang') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Penitip --}}
                    <div class="mb-4">
                        <label for="id_penitip" class="block font-semibold mb-2">Pilih Penitip <span
                                class="text-red-500">*</span></label>
                        <select name="id_penitip" id="id_penitip" required
                            class="w-full border p-3 rounded-lg @error('id_penitip') border-red-500 @enderror">
                            <option value="">-- Pilih Penitip --</option>
                            @foreach($penitips ?? [] as $penitip)
                                @if(is_object($penitip))
                                    <option value="{{ $penitip->id_penitip }}" {{ old('id_penitip') == $penitip->id_penitip ? 'selected' : '' }}>
                                        {{ $penitip->nama_penitip }}
                                    </option>
                                @endif
                            @endforeach


                        </select>
                        @error('id_penitip') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    {{-- Hunter --}}
                    <div class="mb-4">
                        <label for="id_hunter" class="block font-semibold mb-2">Pilih Hunter </label>
                        <select name="id_hunter" id="id_hunter" required
                            class="w-full border p-3 rounded-lg @error('id_hunter') border-red-500 @enderror">
                            <option value="">-- Pilih Hunter --</option>
                            @foreach($hunters ?? [] as $hunter)
                                @if(is_object($hunter))
                                    <option value="{{ $hunter->id_pegawai }}" {{ old('id_hunter') == $hunter->id_pegawai ? 'selected' : '' }}>
                                        {{ $hunter->nama_pegawai }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('id_hunter') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kategori Barang --}}
                    <div class="mb-4">
                        <label for="id_kategori" class="block font-semibold mb-2">Kategori Barang <span
                                class="text-red-500">*</span></label>
                        <select name="id_kategori" id="id_kategori" required
                            class="w-full border p-3 rounded-lg @error('id_kategori') border-red-500 @enderror">
                            <option value="">-- Pilih Kategori --</option>

                            {{-- Debug setiap item --}}
                            @if(isset($kategoris) && count($kategoris) > 0)
                                @foreach($kategoris as $index => $kategori)
                                    {{-- Debug item --}}
                                    <!-- DEBUG Item {{ $index }}: {{ json_encode($kategori) }} -->

                                    @if(isset($kategori->id_kategori) && isset($kategori->nama_kategori))
                                        <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @else
                                        <!-- DEBUG: Missing fields in item {{ $index }} -->
                                    @endif
                                @endforeach
                            @else
                                <option value="" disabled>Tidak ada kategori tersedia</option>
                            @endif
                        </select>
                        @error('id_kategori') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tanggal Penitipan --}}
                    <div class="mb-4">
                        <label for="tanggal_penitipan" class="block font-semibold mb-2">Tanggal Titip <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_penitipan" id="tanggal_penitipan" required
                            value="{{ old('tanggal_penitipan', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}"
                            class="w-full border p-3 rounded-lg @error('tanggal_penitipan') border-red-500 @enderror" />
                        @error('tanggal_penitipan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama Barang --}}
                    <div class="mb-4">
                        <label for="nama_barang" class="block font-semibold mb-2">Nama Barang <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_barang" id="nama_barang" required value="{{ old('nama_barang') }}"
                            placeholder="Masukkan nama barang"
                            class="w-full border p-3 rounded-lg @error('nama_barang') border-red-500 @enderror" />
                        @error('nama_barang') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Deskripsi Barang --}}
                    <div class="mb-4">
                        <label for="deskripsi_barang" class="block font-semibold mb-2">Deskripsi Barang <span
                                class="text-red-500">*</span></label>
                        <textarea name="deskripsi_barang" id="deskripsi_barang" rows="3" required
                            class="w-full border p-3 rounded-lg @error('deskripsi_barang') border-red-500 @enderror">{{ old('deskripsi_barang') }}</textarea>
                        @error('deskripsi_barang') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Harga Barang --}}
                    <div class="mb-4">
                        <label for="harga_barang" class="block font-semibold mb-2">Harga Barang (Rp) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="harga_barang" id="harga_barang" required step="1" min="1"
                            value="{{ old('harga_barang') }}"
                            class="w-full border p-3 rounded-lg @error('harga_barang') border-red-500 @enderror" />
                        @error('harga_barang') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Berat Barang --}}
                    <div class="mb-4">
                        <label for="berat_barang" class="block font-semibold mb-2">Berat Barang (Gram(g)) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="berat_barang" id="berat_barang" required step="0.01" min="0.1"
                            value="{{ old('berat_barang') }}"
                            class="w-full border p-3 rounded-lg @error('berat_barang') border-red-500 @enderror" />
                        @error('berat_barang') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status Barang --}}
                    <div class="mb-4">
                        <label for="status_barang" class="block font-semibold mb-2">Status Barang <span
                                class="text-red-500">*</span></label>
                        <select name="status_barang" id="status_barang" required
                            class="w-full border p-3 rounded-lg @error('status_barang') border-red-500 @enderror">
                            <option value="">-- Pilih Status --</option>
                            <option value="tidak laku" {{ old('status_barang') == 'tidak laku' ? 'selected' : '' }}>Tidak
                                Laku</option>
                        </select>
                        @error('status_barang') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- SECTION GARANSI BARANG - BARU -->
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-3">Pengaturan Garansi Barang</h3>

                        <div class="mb-3">
                            <label class="block font-semibold mb-2">Apakah barang memiliki garansi? <span
                                    class="text-red-500">*</span></label>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="has_garansi" value="tidak" checked class="mr-2"
                                        onchange="toggleGaransi()">
                                    <span>Tidak ada garansi</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="has_garansi" value="ya" class="mr-2"
                                        onchange="toggleGaransi()">
                                    <span>Ya, ada garansi</span>
                                </label>
                            </div>
                        </div>

                        <!-- Input garansi yang tersembunyi, akan muncul jika dipilih "Ya" -->
                        <div id="garansi-section" class="hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="garansi_type" class="block font-semibold mb-2">Jenis Garansi</label>
                                    <select name="garansi_type" id="garansi_type" class="w-full border p-3 rounded-lg">
                                        <option value="1_tahun">1 Tahun (Default)</option>
                                        <option value="6_bulan">6 Bulan</option>
                                        <option value="custom">Custom</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="garansi_barang" class="block font-semibold mb-2">Tanggal Berakhir
                                        Garansi</label>
                                    <input type="date" name="garansi_barang" id="garansi_barang"
                                        class="w-full border p-3 rounded-lg" readonly />
                                    <small class="text-gray-600">Otomatis dihitung berdasarkan tanggal penitipan</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fixed Foto Barang Section --}}
                    <div class="mb-4">
                        <label for="foto_barang" class="block font-semibold mb-2">
                            Foto Barang <span class="text-red-500">*</span>
                            <small class="text-gray-500 font-normal">(1-5 foto, maks 2MB per foto)</small>
                        </label>

                        <input type="file" name="foto_barang[]" id="foto_barang" required multiple
                            accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                            class="w-full border p-3 rounded-lg @error('foto_barang') border-red-500 @enderror @error('foto_barang.*') border-red-500 @enderror"
                            onchange="previewImages(this)" />

                        <!-- File validation info -->
                        <div class="text-sm text-gray-600 mt-1">
                            Format yang didukung: JPEG, PNG, JPG, GIF, WEBP. Maksimal 5 foto.
                        </div>

                        @error('foto_barang')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('foto_barang.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Image Preview -->
                        <div id="image-preview" class="grid grid-cols-3 gap-2 mt-3" style="display: none;"></div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex justify-end mt-6">
                        <button type="button" @click="showCreateModal = false"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded mr-2">
                            Batal
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded disabled:opacity-50">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>



        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <form method="GET" action="{{ route('gudang.SearchTitipan') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search_term" value="{{ request('search_term') }}"
                    placeholder="Cari Penitip, Barang, atau Status..."
                    class="col-span-1 md:col-span-2 border p-3 rounded-lg w-full" />

                <select name="status" class="border p-3 rounded-lg w-full">
                    <option value="">Semua Status</option>
                    <option value="tidak_laku" {{ request('status') == 'tidak_laku' ? 'selected' : '' }}>Tidak Laku
                    </option>
                    <option value="laku" {{ request('status') == 'laku' ? 'selected' : '' }}>Laku</option>
                    <option value="di_donasikan" {{ request('status') == 'di_donasikan' ? 'selected' : '' }}>Di Donasikan
                        (Lewat Batas Pengambilan)</option>
                    <option value="donasikan" {{ request('status') == 'donasikan' ? 'selected' : '' }}>Donasikan (Ada
                        Request)</option>
                    <option value="akan_diambil" {{ request('status') == 'akan_diambil' ? 'selected' : '' }}>Akan Diambil
                        (Pembeli)</option>
                    <option value="sudah_diambil" {{ request('status') == 'sudah_diambil' ? 'selected' : '' }}>Sudah
                        Diambil (Pembeli)</option>
                    <option value="diambil_kembali_penitip" {{ request('status') == 'diambil_kembali_penitip' ? 'selected' : '' }}>Diambil Kembali (Penitip)</option>
                </select>


                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Cari</button>
            </form>
        </div>

        <!-- Tampilkan hasil pencarian jika ada -->
        @if(request('search_term'))
            <div class="mb-4 text-gray-600">
                Menampilkan hasil untuk: <strong>{{ request('search_term') }}</strong>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($titipans as $titipan)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h2 class="text-xl font-semibold">
                            {{ $titipan->penitip ? $titipan->penitip->nama_penitip : 'Nama tidak tersedia' }}
                        </h2>
                        <p class="text-sm text-gray-600">
                            Tanggal Titip:
                            {{ $titipan->tanggal_penitipan ? $titipan->tanggal_penitipan->format('d M Y') : '-' }}
                        </p>
                        @if($titipan->pegawai)
                            <p class="text-sm text-gray-500">
                                Ditambahkan oleh: <strong>{{ $titipan->pegawai->nama_pegawai }}</strong>
                            </p>
                        @endif


                        @php
                            $status = 'Belum Diketahui';
                            $badgeClass = 'bg-gray-200 text-gray-800';
                            $today = \Carbon\Carbon::now(); // [cite: 311]

                            $barangsInThisTitipan = $titipan->detailTransaksiPenitipan->map(function ($detail) {
                                return $detail->barang;
                            })->filter();

                            $isSudahDiambil = $barangsInThisTitipan->contains(function ($barang) {
                                if ($barang && $barang->detailTransaksiPenjualan && $barang->detailTransaksiPenjualan->transaksipenjualan) {
                                    $penjualan = $barang->detailTransaksiPenjualan->transaksipenjualan;
                                    return $penjualan->metode_pengantaran === 'Ambil di Gudang' && $penjualan->status_pembayaran === 'lunas';
                                }
                                return false;
                            });

                            $isAkanDiambil = false;
                            if (!$isSudahDiambil) {
                                $isAkanDiambil = $barangsInThisTitipan->contains(function ($barang) {
                                    if ($barang && $barang->detailTransaksiPenjualan && $barang->detailTransaksiPenjualan->transaksipenjualan) {
                                        $penjualan = $barang->detailTransaksiPenjualan->transaksipenjualan;
                                        return $penjualan->metode_pengantaran === 'Ambil di Gudang' && $penjualan->status_pembayaran !== 'lunas';
                                    }
                                    return false;
                                });
                            }

                            $isLaku = false;
                            if (!$isSudahDiambil && !$isAkanDiambil) {
                                $isLaku = $barangsInThisTitipan->contains(function ($barang) {
                                    // Cek jika ada detail penjualan, dan metode bukan ambil di gudang, atau sudah lunas tapi bukan ambil di gudang
                                    if ($barang && $barang->detailTransaksiPenjualan && $barang->detailTransaksiPenjualan->transaksipenjualan) {
                                        $penjualan = $barang->detailTransaksiPenjualan->transaksipenjualan;
                                        // Dianggap laku jika ada penjualan dan bukan 'Ambil di Gudang' atau sudah lunas tapi bukan 'Ambil di Gudang'
                                        return $penjualan->id_transaksi_penjualan && $penjualan->metode_pengantaran !== 'Ambil di Gudang';
                                    }
                                    return $barang && $barang->detailTransaksiPenjualan && $barang->detailTransaksiPenjualan->id_detail_transaksi_penjualan;
                                });
                            }

                            $isDonasikan = false; // Sudah ada id_request di tabel donasi
                            if (!$isSudahDiambil && !$isAkanDiambil && !$isLaku) {
                                $isDonasikan = $barangsInThisTitipan->contains(function ($barang) { // [cite: 312]
                                    return $barang && $barang->donasi && $barang->donasi->id_request !== null; // [cite: 312]
                                });
                            }

                            $isDiDonasikan = false; // Sudah lewat tanggal batas pengambilan & belum ada aksi lain
                            if (!$isSudahDiambil && !$isAkanDiambil && !$isLaku && !$isDonasikan) {
                                if ($titipan->tanggal_batas_pengambilan && \Carbon\Carbon::parse($titipan->tanggal_batas_pengambilan)->lt($today) && !$titipan->tanggal_pengambilan_barang) { // [cite: 313]
                                    $isDiDonasikan = true;
                                }
                            }

                            $isDiambilKembaliPenitip = $titipan->tanggal_pengambilan_barang != null; // [cite: 311]

                            $isTidakLaku = false;
                            if (!$isSudahDiambil && !$isAkanDiambil && !$isLaku && !$isDonasikan && !$isDiDonasikan && !$isDiambilKembaliPenitip) {
                                // Kondisi Tidak Laku:
                                // 1. status_barang di tabel barang adalah 'tidak laku'
                                // 2. ATAU barang masih dalam masa penitipan (tanggal_akhir_penitipan >= today)
                                $isTidakLaku = $barangsInThisTitipan->contains(function ($barang) {
                                    return $barang && $barang->status_barang === 'tidak laku';
                                });
                                if (!$isTidakLaku && $titipan->tanggal_akhir_penitipan && \Carbon\Carbon::parse($titipan->tanggal_akhir_penitipan)->gte($today)) {
                                    $isTidakLaku = true; // Jika masih dalam masa penitipan dan belum ada status lain, anggap tidak laku (sementara)
                                }
                            }

                            if ($isSudahDiambil) {
                                $status = 'Sudah Diambil (Pembeli)';
                                $badgeClass = 'bg-red-600 text-white'; // [cite: 322, 324]
                            } elseif ($isAkanDiambil) {
                                $status = 'Akan Diambil (Pembeli)';
                                $badgeClass = 'bg-blue-500 text-white'; // [cite: 322]
                            } elseif ($isLaku) {
                                $status = 'Laku, sudah diambil'; // Lebih spesifik untuk membedakan dari Ambil di Gudang
                                $badgeClass = 'bg-teal-500 text-white'; // [cite: 318, 322]
                            } elseif ($isDonasikan) {
                                $status = 'Donasikan (Ada Request)';
                                $badgeClass = 'bg-purple-600 text-white'; // [cite: 316, 322]
                            } elseif ($isDiDonasikan) {
                                $status = 'Di Donasikan (Lewat Batas)';
                                $badgeClass = 'bg-yellow-500 text-black'; // [cite: 317, 322]
                            } elseif ($isDiambilKembaliPenitip) {
                                $status = 'Diambil Kembali (Penitip)'; // [cite: 315]
                                $badgeClass = 'bg-gray-500 text-white'; // [cite: 323]
                            } elseif ($isTidakLaku) {
                                $status = 'Tidak Laku / Tersedia'; // [cite: 319]
                                $badgeClass = 'bg-green-500 text-white'; // [cite: 321]
                            } else {
                                // Fallback jika ada kondisi yang belum tercover (seharusnya tidak banyak)
                                if ($titipan->tanggal_akhir_penitipan && \Carbon\Carbon::parse($titipan->tanggal_akhir_penitipan)->lt($today) && !$titipan->tanggal_pengambilan_barang) {
                                    $status = 'Melewati Batas (Belum Ada Keputusan)';
                                    $badgeClass = 'bg-orange-400 text-white';
                                } else {
                                    $status = 'Dalam Proses Gudang';
                                    $badgeClass = 'bg-indigo-200 text-indigo-800';
                                }
                            }
                        @endphp

                        <div class="mt-2">
                            <span class="inline-block {{ $badgeClass }} text-xs px-2 py-1 rounded-full">
                                {{ $status }}
                            </span>
                        </div>


                        <!-- Edit Button -->
                        <button @click="openModal({{ $titipan->id_transaksi_penitipan }}, {
                                nama_penitip: '{{ addslashes(optional($titipan->penitip)->nama_penitip) }}',
                                email_penitip: '{{ addslashes(optional($titipan->penitip)->email_penitip) }}',
                                id_hunter: '{{ $titipan->id_hunter ?? '' }}',
                                tanggal_penitipan: '{{ optional($titipan->tanggal_penitipan)->format('Y-m-d') }}',
                                tanggal_akhir_penitipan: '{{ optional($titipan->tanggal_akhir_penitipan)->format('Y-m-d') }}',
                                tanggal_batas_pengambilan: '{{ optional($titipan->tanggal_batas_pengambilan)->format('Y-m-d') }}',
                                tanggal_pengambilan_barang: '{{ optional($titipan->tanggal_pengambilan_barang)->format('Y-m-d') }}',

                                // Bagian data barang yang lebih aman
                                nama_barang: '{{ addslashes(optional(optional($titipan->detailTransaksiPenitipan->first())->barang)->nama_barang) }}',
                                deskripsi_barang: '{{ addslashes(optional(optional($titipan->detailTransaksiPenitipan->first())->barang)->deskripsi_barang) }}',
                                harga_barang: '{{ optional(optional($titipan->detailTransaksiPenitipan->first())->barang)->harga_barang }}',
                                berat_barang: '{{ optional(optional($titipan->detailTransaksiPenitipan->first())->barang)->berat_barang }}',
                                status_barang: '{{ optional(optional($titipan->detailTransaksiPenitipan->first())->barang)->status_barang }}',

                                // Bagian garansi yang lebih aman
                                has_garansi: '{{ optional(optional($titipan->detailTransaksiPenitipan->first())->barang)->garansi_barang ? 'ya' : 'tidak' }}',
                                garansi_barang: '{{ optional(optional(optional($titipan->detailTransaksiPenitipan->first())->barang)->garansi_barang)->format('Y-m-d') }}'
                            })"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded mt-3 transition-colors">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        <!--cetakNota -->
                        <a href="{{ route('gudang.CetakNota', $titipan->id_transaksi_penitipan) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded mt-3 ml-2 inline-block transition-colors"
                            target="_blank">
                            <i class="fas fa-print mr-1"></i> Cetak Nota
                        </a>


                        <!-- Detail Toggle -->
                        <div x-data="{ open: false }" class="mt-4">
                            <button @click="open = !open"
                                class="text-blue-600 hover:text-blue-800 hover:underline flex items-center">
                                <i class="fas fa-eye mr-1"></i>
                                <span x-text="open ? 'Sembunyikan Detail' : 'Lihat Detail'"></span>
                            </button>

                            <div x-show="open" x-transition.duration.300ms class="mt-4 text-sm space-y-2 border-t pt-4">
                                <div class="grid grid-cols-1 gap-2">
                                    <div>
                                        <strong class="text-gray-700">Email Penitip:</strong>
                                        <span
                                            class="text-gray-600">{{ $titipan->penitip ? $titipan->penitip->email_penitip : '-' }}</span>
                                    </div>

                                    @if($titipan->hunter)
                                        <div>
                                            <strong class="text-gray-700">Hunter:</strong>
                                            <span class="text-gray-600 font-medium">{{ $titipan->hunter->nama_pegawai }}</span>
                                        </div>
                                    @endif

                                    <div>
                                        <strong class="text-gray-700">Tanggal Akhir Penitipan:</strong>
                                        <span
                                            class="text-gray-600">{{ $titipan->tanggal_akhir_penitipan ? $titipan->tanggal_akhir_penitipan->format('d M Y') : '-' }}</span>
                                    </div>

                                    <div>
                                        <strong class="text-gray-700">Tanggal Batas Pengambilan:</strong>
                                        <span
                                            class="text-gray-600">{{ $titipan->tanggal_batas_pengambilan ? $titipan->tanggal_batas_pengambilan->format('d M Y') : '-' }}</span>
                                    </div>

                                    <div>
                                        <strong class="text-gray-700">Tanggal Pengambilan Barang:</strong>
                                        <span
                                            class="text-gray-600">{{ $titipan->tanggal_pengambilan_barang ? $titipan->tanggal_pengambilan_barang->format('d M Y') : 'Belum diambil' }}</span>
                                    </div>
                                </div>


                                <!-- Foto Barang Section - FIXED VERSION -->
                                <div class="mt-4">
                                    <strong class="text-gray-700 block mb-2">Foto Barang:</strong>

                                    @php
                                        // Ambil foto dari tabel barang melalui relasi
                                        $fotoBarang = [];

                                        // Ambil semua detail transaksi untuk mendapatkan barang-barang
                                        foreach ($titipan->detailTransaksiPenitipan ?? [] as $detail) {
                                            if ($detail->barang && $detail->barang->foto_barang) {
                                                $barangFoto = $detail->barang->foto_barang;

                                                // Handle foto_barang yang bisa berupa string JSON atau array
                                                if (is_string($barangFoto)) {
                                                    $decoded = json_decode($barangFoto, true);
                                                    if (is_array($decoded)) {
                                                        $fotoBarang = array_merge($fotoBarang, $decoded);
                                                    } else {
                                                        $fotoBarang[] = $barangFoto; // single string path
                                                    }
                                                } elseif (is_array($barangFoto)) {
                                                    $fotoBarang = array_merge($fotoBarang, $barangFoto);
                                                }
                                            }
                                        }

                                        // Filter foto yang kosong
                                        $fotoBarang = array_filter($fotoBarang, fn($foto) => !empty($foto));
                                    @endphp

                                    @if(!empty($fotoBarang))
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($fotoBarang as $index => $foto)
                                                @php
                                                    $cleanFoto = trim($foto);
                                                    // Periksa apakah path sudah include 'storage' atau tidak
                                                    if (strpos($cleanFoto, 'storage/') === false && strpos($cleanFoto, 'images/') === 0) {
                                                        $fullPath = asset($cleanFoto); // langsung dari public/images/
                                                    } else {
                                                        $fullPath = asset('storage/' . $cleanFoto);
                                                    }
                                                    $altText = $titipan->penitip?->nama_penitip ?? 'Foto Barang';
                                                @endphp

                                                <div class="text-center">
                                                    <div class="relative group">
                                                        <img src="{{ $fullPath }}" alt="Foto Barang {{ $index + 1 }}"
                                                            class="w-full h-24 object-cover rounded border hover:shadow-lg transition-shadow cursor-pointer"
                                                            loading="lazy"
                                                            onerror="this.onerror=null; this.classList.add('opacity-50'); this.nextElementSibling.style.display='block'; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0zNSA2NUw1MCA0NUw2NSA2NUgzNVoiIGZpbGw9IiM5Q0EzQUYiLz4KPGNpcmNsZSBjeD0iNDAiIGN5PSIzNSIgcj0iNSIgZmlsbD0iIzlDQTNBRiIvPgo8L3N2Zz4=';"
                                                            onclick="openImageModal('{{ $fullPath }}', '{{ $altText }} - {{ $index + 1 }}')">

                                                        <!-- Overlay for hover effect -->
                                                        <div
                                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded flex items-center justify-center">
                                                            <i
                                                                class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Error message -->
                                                    <div class="text-xs text-red-500 mt-1" style="display: none;">
                                                        Gagal memuat foto
                                                    </div>

                                                    <!-- File name -->
                                                    <div class="text-xs text-gray-400 mt-1 truncate" title="{{ $cleanFoto }}">
                                                        {{ basename($cleanFoto) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <div
                                                class="w-24 h-24 mx-auto bg-gray-100 rounded border flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                            </div>
                                            <p class="text-gray-500 italic mt-2">Tidak ada foto tersedia</p>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <div class="text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-4"></i>
                        <p class="text-lg">Belum ada data titipan barang</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" style="display: none;">

        <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            @click.away="closeModal()">

            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Edit Titipan Barang</h2>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="edit-form" method="POST" :action="getFormAction()" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Penitip -->
                        <div class="col-span-1 md:col-span-2">
                            <label class="block font-semibold mb-2 text-gray-700">Nama Penitip *</label>
                            <input type="text" name="nama_penitip" x-model="formData.nama_penitip"
                                class="w-full border p-3 rounded-lg" required>
                        </div>

                        <!-- Email Penitip -->
                        <div class="col-span-1 md:col-span-2">
                            <label class="block font-semibold mb-2 text-gray-700">Email Penitip *</label>
                            <input type="email" name="email_penitip" x-model="formData.email_penitip"
                                class="w-full border p-3 rounded-lg" required>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block font-semibold mb-2 text-gray-700">Hunter </label>
                            <select name="id_hunter" x-model="formData.id_hunter" class="w-full border p-3 rounded-lg"
                                required>
                                <option value="">-- Pilih Hunter --</option>
                                <option value="">-- Pilih Hunter --</option>
                                @foreach($hunters ?? [] as $hunter)
                                    @if(is_object($hunter))
                                        <option value="{{ $hunter->id_pegawai }}" {{ old('id_hunter') == $hunter->id_pegawai ? 'selected' : '' }}>
                                            {{ $hunter->nama_pegawai }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>


                        <!-- Tanggal Titip -->
                        <div class="col-span-1">
                            <label class="block font-semibold mb-2 text-gray-700">Tanggal Titip *</label>
                            <input type="date" name="tanggal_penitipan" x-model="formData.tanggal_penitipan"
                                @change="updateDurasi(); updateEditGaransiDate();" class="w-full border p-3 rounded-lg"
                                required>
                        </div>

                        <!-- Hidden fields untuk memastikan data tetap terkirim -->
                        <input type="hidden" name="tanggal_akhir_penitipan" x-model="formData.tanggal_akhir_penitipan">
                        <input type="hidden" name="tanggal_batas_pengambilan"
                            x-model="formData.tanggal_batas_pengambilan">
                        <input type="hidden" name="tanggal_pengambilan_barang"
                            x-model="formData.tanggal_pengambilan_barang">


                        <!-- Tanggal Akhir Penitipan (readonly, untuk display) -->
                        <div class="col-span-1">
                            <label class="block font-semibold mb-2 text-gray-700">Tanggal Akhir Penitipan</label>
                            <input type="date" :value="formData . tanggal_akhir_penitipan"
                                class="w-full border p-3 rounded-lg bg-gray-100" readonly>
                        </div>

                        <!-- Tanggal Batas Pengambilan (readonly, untuk display) -->
                        <div class="col-span-1">
                            <label class="block font-semibold mb-2 text-gray-700">Tanggal Batas Pengambilan</label>
                            <input type="date" :value="formData . tanggal_batas_pengambilan"
                                class="w-full border p-3 rounded-lg bg-gray-100" readonly>
                        </div>


                        <!-- Nama Barang -->
                        <div class="col-span-1 md:col-span-2">
                            <label class="block font-semibold mb-2 text-gray-700">Nama Barang *</label>
                            <input type="text" name="nama_barang" class="w-full border p-3 rounded-lg" required
                                x-model="formData.nama_barang">
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-span-1 md:col-span-2">
                            <label class="block font-semibold mb-2 text-gray-700">Deskripsi Barang *</label>
                            <textarea name="deskripsi_barang" rows="3" class="w-full border p-3 rounded-lg" required
                                x-model="formData.deskripsi_barang"></textarea>
                        </div>

                        <!-- Harga -->
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">Harga Barang (Rp) *</label>
                            <input type="number" name="harga_barang" class="w-full border p-3 rounded-lg" required
                                x-model="formData.harga_barang">
                        </div>

                        <!-- Berat -->
                        <div>
                            <label class="block font-semibold mb-2 text-gray-700">Berat Barang (gram) *</label>
                            <input type="number" name="berat_barang" class="w-full border p-3 rounded-lg" required
                                step="0.1" min="0.1" x-model="formData.berat_barang">
                        </div>

                        <!-- Status -->
                        <div class="col-span-1 md:col-span-2">
                            <label class="block font-semibold mb-2 text-gray-700">Status Barang *</label>
                            <select name="status_barang" class="w-full border p-3 rounded-lg" required
                                x-model="formData.status_barang">
                                <option value="">-- Pilih Status --</option>
                                <option value="tidak laku">Tidak Laku</option>
                            </select>
                        </div>

                        <!-- Garansi -->
                        <div class="col-span-1 md:col-span-2 bg-blue-50 p-4 rounded border border-blue-200">
                            <label class="block font-semibold mb-2 text-blue-800">Garansi Barang</label>

                            <div class="mb-3">
                                <label class="text-sm font-medium block mb-1">Memiliki Garansi?</label>
                                <div class="flex gap-4">
                                    <label><input type="radio" name="has_garansi" value="ya" class="mr-1">Ya</label>
                                    <label><input type="radio" name="has_garansi" value="tidak" class="mr-1"
                                            checked>Tidak</label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm mb-1">Jenis Garansi</label>
                                    <select name="garansi_type" class="w-full border p-3 rounded-lg">
                                        <option value="1_tahun">1 Tahun (default)</option>
                                        <option value="6_bulan">6 Bulan</option>
                                        <option value="custom">Custom</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">Tanggal Berakhir Garansi</label>
                                    <input type="date" name="garansi_barang" class="w-full border p-3 rounded-lg">
                                </div>
                            </div>
                        </div>

                        <!-- Foto Barang -->
                        <div class="col-span-1 md:col-span-2">
                            <label class="block font-semibold mb-2 text-gray-700">Foto Barang </label>
                            <input type="file" name="foto_barang[]" multiple class="w-full border p-3 rounded-lg"
                                accept="image/*">
                            <small class="text-gray-500">Boleh upload ulang 1-5 foto. Kosongkan jika tidak ingin
                                diubah.</small>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t">
                        <button type="button" @click="closeModal()"
                            class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Image Viewer -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-60 flex items-center justify-center p-4"
        style="display: none;">
        <div class="relative max-w-4xl max-h-full">
            <button onclick="closeImageModal()"
                class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 z-10">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded">
            <div id="modalCaption" class="text-white text-center mt-4"></div>
        </div>
    </div>

    <!-- Alpine.js Logic -->
    <script>
        // Tambahkan dalam bagian Alpine.js function titipanHandler()
        function titipanHandler() {
            return {
                showModal: false,
                showCreateModal: false,
                currentId: null,
                formData: {
                    nama_penitip: '',
                    email_penitip: '',
                    id_hunter: '',
                    tanggal_penitipan: '',
                    tanggal_akhir_penitipan: '',
                    tanggal_batas_pengambilan: '',
                    tanggal_pengambilan_barang: '',
                    nama_barang: '',
                    deskripsi_barang: '',
                    harga_barang: '',
                    berat_barang: '',
                    status_barang: '',
                    has_garansi: 'tidak',
                    garansi_barang: ''
                },

                openModal(id, data) {
                    this.currentId = id;
                    this.formData = { ...this.formData, ...data };
                    this.showModal = true;
                    this.updateDurasi();
                    document.body.style.overflow = 'hidden';

                    // Setup garansi handlers untuk edit form setelah modal terbuka
                    this.$nextTick(() => {
                        this.setupEditGaransiHandlers();
                    });
                },

                setupEditGaransiHandlers() {
                    // Setup event listeners untuk garansi di form edit
                    const editGaransiRadios = document.querySelectorAll('#edit-form input[name="has_garansi"]');
                    const editGaransiType = document.querySelector('#edit-form select[name="garansi_type"]');
                    const editGaransiDate = document.querySelector('#edit-form input[name="garansi_barang"]');
                    const editGaransiSection = document.querySelector('#edit-form .garansi-section');

                    if (editGaransiRadios.length > 0) {
                        editGaransiRadios.forEach(radio => {
                            radio.addEventListener('change', () => {
                                this.toggleEditGaransi();
                            });
                        });
                    }

                    if (editGaransiType) {
                        editGaransiType.addEventListener('change', () => {
                            this.updateEditGaransiDate();
                        });
                    }
                },

                toggleEditGaransi() {
                    const hasGaransi = document.querySelector('#edit-form input[name="has_garansi"]:checked')?.value;
                    const garansiInputs = document.querySelectorAll('#edit-form select[name="garansi_type"], #edit-form input[name="garansi_barang"]');

                    if (hasGaransi === 'ya') {
                        garansiInputs.forEach(input => {
                            input.style.display = 'block';
                            input.disabled = false;
                        });
                        this.updateEditGaransiDate();
                    } else {
                        garansiInputs.forEach(input => {
                            input.style.display = 'none';
                            input.disabled = true;
                        });
                        document.querySelector('#edit-form input[name="garansi_barang"]').value = '';
                    }
                },

                updateEditGaransiDate() {
                    const tanggalPenitipan = this.formData.tanggal_penitipan;
                    const garansiType = document.querySelector('#edit-form select[name="garansi_type"]')?.value;
                    const garansiInput = document.querySelector('#edit-form input[name="garansi_barang"]');
                    const hasGaransi = document.querySelector('#edit-form input[name="has_garansi"]:checked')?.value;

                    if (!tanggalPenitipan || hasGaransi === 'tidak' || !garansiInput) {
                        return;
                    }

                    const tanggalMulai = new Date(tanggalPenitipan);
                    let tanggalAkhir = new Date(tanggalMulai);

                    switch (garansiType) {
                        case '6_bulan':
                            tanggalAkhir.setMonth(tanggalAkhir.getMonth() + 6);
                            garansiInput.readOnly = true;
                            break;
                        case '1_tahun':
                            tanggalAkhir.setFullYear(tanggalAkhir.getFullYear() + 1);
                            garansiInput.readOnly = true;
                            break;
                        case 'custom':
                            garansiInput.readOnly = false;
                            return;
                        default:
                            tanggalAkhir.setFullYear(tanggalAkhir.getFullYear() + 1);
                            garansiInput.readOnly = true;
                    }

                    const formattedDate = tanggalAkhir.toISOString().split('T')[0];
                    garansiInput.value = formattedDate;
                },

                closeModal() {
                    this.showModal = false;
                    this.currentId = null;
                    this.formData = {
                        nama_penitip: '',
                        email_penitip: '',
                        tanggal_penitipan: '',
                        tanggal_akhir_penitipan: '',
                        tanggal_batas_pengambilan: '',
                        tanggal_pengambilan_barang: ''
                    };
                    document.body.style.overflow = 'auto';

                    // Clear image preview
                    const preview = document.getElementById('image-preview');
                    if (preview) {
                        preview.innerHTML = '';
                        preview.style.display = 'none';
                    }
                },

                getFormAction() {
                    return `/UpdateTitipanBarang/${this.currentId}`;
                },

                updateDurasi() {
                    const input = this.formData.tanggal_penitipan;
                    if (input) {
                        const tgl = new Date(input);
                        const akhir = new Date(tgl);
                        const batas = new Date(tgl);

                        akhir.setDate(akhir.getDate() + 30);
                        batas.setDate(akhir.getDate() + 30 + 7);

                        const formatDate = (dateObj) => dateObj.toISOString().split('T')[0];

                        this.formData.tanggal_akhir_penitipan = formatDate(akhir);
                        this.formData.tanggal_batas_pengambilan = formatDate(batas);
                    }
                }
            }
        }


        // Toggle garansi section visibility
        function toggleGaransi() {
            const hasGaransi = document.querySelector('input[name="has_garansi"]:checked').value;
            const garansiSection = document.getElementById('garansi-section');

            if (hasGaransi === 'ya') {
                garansiSection.classList.remove('hidden');
                updateGaransiDate(); // Update tanggal garansi
            } else {
                garansiSection.classList.add('hidden');
                document.getElementById('garansi_barang').value = '';
            }
        }

        // Update garansi date based on tanggal_penitipan and garansi_type
        function updateGaransiDate() {
            const tanggalPenitipan = document.getElementById('tanggal_penitipan').value;
            const garansiType = document.getElementById('garansi_type').value;
            const hasGaransi = document.querySelector('input[name="has_garansi"]:checked').value;

            if (!tanggalPenitipan || hasGaransi === 'tidak') {
                return;
            }

            const tanggalMulai = new Date(tanggalPenitipan);
            let tanggalAkhir = new Date(tanggalMulai);

            switch (garansiType) {
                case '6_bulan':
                    tanggalAkhir.setMonth(tanggalAkhir.getMonth() + 6);
                    break;
                case '1_tahun':
                    tanggalAkhir.setFullYear(tanggalAkhir.getFullYear() + 1);
                    break;
                case 'custom':
                    // Untuk custom, user bisa edit manual
                    document.getElementById('garansi_barang').readOnly = false;
                    return;
                default:
                    tanggalAkhir.setFullYear(tanggalAkhir.getFullYear() + 1);
            }

            // Format tanggal untuk input date (YYYY-MM-DD)
            const formattedDate = tanggalAkhir.toISOString().split('T')[0];
            document.getElementById('garansi_barang').value = formattedDate;
            document.getElementById('garansi_barang').readOnly = true;
        }

        // Event listener untuk garansi type change
        document.addEventListener('DOMContentLoaded', function () {
            const garansiTypeSelect = document.getElementById('garansi_type');
            garansiTypeSelect.addEventListener('change', updateGaransiDate);

            // Set default tanggal penitipan ke hari ini
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal_penitipan').value = today;
        });


        // Image preview function
        function previewImages(input) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';

            if (input.files && input.files.length > 0) {
                preview.style.display = 'grid';

                Array.from(input.files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const div = document.createElement('div');
                            div.className = 'relative';
                            div.innerHTML = `
                                    <img src="${e.target.result}" alt="Preview ${index + 1}" 
                                        class="w-full h-20 object-cover rounded border">
                                    <div class="text-xs text-gray-500 mt-1 truncate">${file.name}</div>
                                    <div class="text-xs text-gray-400">${(file.size / 1024).toFixed(1)} KB</div>
                                `;
                            preview.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                preview.style.display = 'none';
            }
        }

        // Image modal functions
        function openImageModal(src, caption) {
            document.getElementById('modalImage').src = src;
            document.getElementById('modalCaption').textContent = caption;
            document.getElementById('imageModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            document.getElementById('imageModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function previewImages(input) {
            const preview = document.getElementById('image-preview');
            const submitBtn = document.getElementById('submitBtn');
            preview.innerHTML = '';

            if (input.files && input.files.length > 0) {
                // Check file count
                if (input.files.length > 5) {
                    alert('Maksimal 5 foto yang dapat diupload');
                    input.value = '';
                    preview.style.display = 'none';
                    return;
                }

                preview.style.display = 'grid';
                let validFiles = 0;

                Array.from(input.files).forEach((file, index) => {
                    // File type validation
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                    if (!allowedTypes.includes(file.type)) {
                        alert(`File ${file.name} bukan format gambar yang didukung`);
                        return;
                    }

                    // File size validation (2MB)
                    if (file.size > 2048 * 1024) {
                        alert(`File ${file.name} terlalu besar. Maksimal 2MB`);
                        return;
                    }

                    validFiles++;

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const div = document.createElement('div');
                        div.className = 'relative border rounded p-2';
                        div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${index + 1}" 
                        class="w-full h-20 object-cover rounded border">
                    <div class="text-xs text-gray-600 mt-1 truncate">${file.name}</div>
                    <div class="text-xs text-gray-400">${(file.size / 1024).toFixed(1)} KB</div>
                    <div class="text-xs text-green-600">✓ Valid</div>
                `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });

                // If no valid files, clear input
                if (validFiles === 0) {
                    input.value = '';
                    preview.style.display = 'none';
                }
            } else {
                preview.style.display = 'none';
            }
        }

        // Form submission validation
        document.getElementById('titipanForm').addEventListener('submit', function (e) {
            const fileInput = document.getElementById('foto_barang');
            const submitBtn = document.getElementById('submitBtn');

            if (!fileInput.files || fileInput.files.length === 0) {
                e.preventDefault();
                alert('Silakan pilih minimal 1 foto barang');
                return false;
            }

            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';
        });

        // Close modal on escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>