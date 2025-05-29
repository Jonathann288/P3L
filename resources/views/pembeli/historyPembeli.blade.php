<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pembeli</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">

    <!-- Sidebar Navigation -->
    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
            <nav>
                <div class="space-y-4">
                    <div class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <img src="{{ asset($pembeli->foto_pembeli) }}" alt="profile" class="w-8 h-8 rounded-full object-cover">
                        <span>{{ $pembeli->nama_pembeli }}</span>
                    </div>
                    <div class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-clock-rotate-left" ></i>
                        <span>History</span>
                    </div>
                    <a href="{{ route('pembeli.AlamatPembeli') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-cog"></i>
                        <span>Alamat</span>
                    </a>
                </div>
            </nav>
        </div>
        <!-- Bottom buttons -->
        <div class="space-y-4 mt-auto">
            <button class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">
                <a href="{{ route('pembeli.Shop-Pembeli') }}">Kembali</a>
            </button>
        </div>
    </div>

<!-- Main Content -->
<div class="p-8 bg-gray-100">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-semibold text-gray-800">History Pembeli</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @if ($transaksiPenjualan->isNotEmpty())
            @foreach ($transaksiPenjualan as $transaksi)
                @foreach ($transaksi->detailTransaksiPenjualan as $detail)
                    @php
                        $fotoBarang = is_array($detail->barang->foto_barang)
                            ? $detail->barang->foto_barang
                            : json_decode($detail->barang->foto_barang, true);
                    @endphp
                    <div
                        onclick="openModalDetail(this)"
                        data-nama-barang="{{ $detail->barang->nama_barang }}"
                        data-harga-barang="{{ number_format($detail->barang->harga_barang, 0, ',', '.') }}"
                        data-foto-barang="{{ asset($fotoBarang[0] ?? 'images/default.jpg') }}"
                        data-metode-pengantaran="{{ $transaksi->metode_pengantaran }}"
                        data-status-pembayaran="{{ $transaksi->status_pembayaran }}"
                        data-tanggal-transaksi="{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}"
                        data-tanggal-kirim="{{ $transaksi->tanggal_kirim ? \Carbon\Carbon::parse($transaksi->tanggal_kirim)->format('d M Y') : '-' }}"
                        data-pembayaran-class="{{ $transaksi->status_pembayaran == 'Lunas' ? 'text-green-500' : 'text-red-500' }}"
                        data-total-harga="{{ number_format($detail->total_harga, 0, ',', '.') }}"
                        class="cursor-pointer bg-white rounded-xl shadow-md p-6 transition hover:shadow-xl hover:scale-[1.02]"
                    >

                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset($pembeli->foto_pembeli) }}" alt="profile" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <div class="text-lg font-semibold">{{ $pembeli->nama_pembeli }}</div>
                                    <div class="text-sm text-gray-500">(+62) {{ $pembeli->nomor_telepon_pembeli }}</div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-400">
                                <i class="fa-regular fa-clock mr-1"></i>
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}
                            </div>
                        </div>

                        <div class="flex gap-4 mb-4">
                            <img src="{{ asset($fotoBarang[0] ?? 'images/default.jpg') }}" alt="barang" class="w-20 h-20 rounded-lg object-cover border">
                            <div class="flex flex-col justify-center">
                                <div class="text-md font-semibold">{{ $detail->barang->nama_barang }}</div>
                                <div class="text-sm text-gray-600">Rp {{ number_format($detail->barang->harga_barang, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="text-sm text-gray-700 space-y-2">
                            <div><i class="fa-solid fa-truck mr-2 text-blue-600"></i> Pengantaran: <strong>{{ $transaksi->metode_pengantaran }}</strong></div>
                            <div><i class="fa-solid fa-money-bill-wave mr-2 text-green-600"></i> Pembayaran: 
                                <strong class="{{ $transaksi->status_pembayaran == 'Lunas' ? 'text-green-500' : 'text-red-500' }}">{{ $transaksi->status_pembayaran }}</strong>
                            </div>
                            <div><i class="fa-solid fa-calendar-check mr-2 text-indigo-600"></i> Dikirim: 
                                {{ $transaksi->tanggal_kirim ? \Carbon\Carbon::parse($transaksi->tanggal_kirim)->format('d M Y') : '-' }}
                            </div>
                        </div>
                        <div class="text-sm text-gray-700 space-y-2">
                            <!-- Existing content... -->
                            <div><i class="fa-solid fa-calculator mr-2 text-purple-600"></i> Total Harga: 
                                <strong class="text-gray-800">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                    </div>
                @endforeach
            @endforeach
        @else
            <p class="text-center text-gray-500">Belum ada riwayat pembelian.</p>
        @endif
    </div>
</div>

<!-- Modal -->
<div id="modalDetail" class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl w-full max-w-md p-6 relative shadow-lg">
        <button onclick="closeModalDetail()" class="absolute top-3 right-3 text-gray-500 hover:text-red-500">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Detail Pembelian</h2>

        <div class="flex gap-4 mb-4">
            <img id="modal-foto-barang" src="" alt="barang" class="w-20 h-20 rounded-lg object-cover border">
            <div class="flex flex-col justify-center">
                <div id="modal-nama-barang" class="text-md font-semibold"></div>
                <div id="modal-harga-barang" class="text-sm text-gray-600"></div>
            </div>
        </div>

        <div class="text-sm text-gray-700 space-y-2">
            <div><i class="fa-solid fa-truck mr-2 text-blue-600"></i> Pengantaran: <strong id="modal-metode-pengantaran"></strong></div>
            <div><i class="fa-solid fa-money-bill-wave mr-2 text-green-600"></i> Pembayaran: 
                <strong id="modal-status-pembayaran" class=""></strong>
            </div>
            <div><i class="fa-solid fa-calendar-check mr-2 text-indigo-600"></i> Tanggal Transaksi: 
                <span id="modal-tanggal-transaksi"></span>
            </div>
            <div><i class="fa-solid fa-truck-fast mr-2 text-yellow-600"></i> Dikirim:
                <span id="modal-tanggal-kirim"></span>
            </div>
            <div><i class="fa-solid fa-calculator mr-2 text-purple-600"></i> Total Harga: 
                <strong id="modal-total-harga" class="text-gray-800"></strong>
            </div>

        </div>
    </div>
</div>

<script>
    function openModalDetail(element) {
        document.getElementById('modal-foto-barang').src = element.dataset.fotoBarang;
        document.getElementById('modal-nama-barang').textContent = element.dataset.namaBarang;
        document.getElementById('modal-harga-barang').textContent = 'Rp ' + element.dataset.hargaBarang;
        document.getElementById('modal-metode-pengantaran').textContent = element.dataset.metodePengantaran;
        
        const statusEl = document.getElementById('modal-status-pembayaran');
        statusEl.textContent = element.dataset.statusPembayaran;
        statusEl.className = element.dataset.pembayaranClass;

        document.getElementById('modal-tanggal-transaksi').textContent = element.dataset.tanggalTransaksi;
        document.getElementById('modal-tanggal-kirim').textContent = element.dataset.tanggalKirim;
        document.getElementById('modal-total-harga').textContent = 'Rp ' + element.dataset.totalHarga;


        document.getElementById('modalDetail').classList.remove('hidden');
    }

    function closeModalDetail() {
        document.getElementById('modalDetail').classList.add('hidden');
    }
</script>

<!-- FontAwesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

</body>

</html>