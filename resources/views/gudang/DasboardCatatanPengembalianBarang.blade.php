<!-- SEMUA COPY -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gudang</title>
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
                    <a href="{{ route('gudang.DashboardGudang') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <span  class="text-2xl font-bold">{{ $pegawaiLogin->nama_pegawai }}</span>
                    </a>
                    <a href="{{ route('gudang.DashboardTitipanBarang') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-dolly mr-2"></i>
                        <span>Tambah Titip Barang</span>
                    </a>
                     <a href="{{ route('gudang.DaftarBarang') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-boxes mr-2"></i>
                        <span>Daftar Barang</span>
                    </a>
                    <a href="{{ route('gudang.DasboardCatatanPengembalianBarang') }}"
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fas fa-boxes mr-2"></i>
                        <span>Catatan Pengembalian Barang</span>
                    </a>
                </div>
            </nav>
        </div>
        <!-- Bottom buttons -->
        <div class="space-y-4 mt-auto">
            <form action="{{ route('logout.pegawai') }}"  method="POST">
                @csrf
                <button class="w-full py-2 bg-red-600 text-white rounded-lg hover:bg-red-500">
                    Keluar
                </button>
            </form>
        </div>
    </div>

<!-- Main Content -->
<div class="p-8 bg-gray-100">
    <h2 class="text-2xl font-bold mb-6">Catatan Pengambilan Barang</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($detailBarang as $detail)
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition duration-300">
                <h3 class="text-lg font-semibold mb-2">{{ $detail->barang->nama_barang ?? '-' }}</h3>
                <p class="text-sm text-gray-600 mb-1">
                    <strong>Penitip:</strong> {{ $detail->transaksipenitipan->penitip->nama_penitip ?? '-' }}
                </p>
                <p class="text-sm text-gray-600 mb-1">
                    <strong>Tanggal Titip:</strong> {{ \Carbon\Carbon::parse($detail->transaksipenitipan->tanggal_titip)->format('d M Y') ?? '-' }}
                </p>
                <p class="text-sm text-gray-600 mb-1">
                    <strong>Tanggal Pengambilan:</strong> {{ \Carbon\Carbon::parse($detail->transaksipenitipan->tanggal_pengambilan)->format('d M Y') ?? '-' }}
                </p>
                <p class="text-sm text-gray-600 mb-1">
                    <strong>Batas Pengambilan:</strong> {{ \Carbon\Carbon::parse($detail->transaksipenitipan->tanggal_batas_pengambilan)->format('d M Y') ?? '-' }}
                </p>
                <p class="text-sm text-gray-600">
                    <strong>Status:</strong> {{ $detail->barang->status_barang ?? '-' }}
                </p>
                @if($detail->barang->status_barang !== 'Sudah diambil')
                    <form action="{{ route('gudang.konfirmasiPengambilan', $detail->id_detail_transaksi_penitipan) }}" method="POST" onsubmit="return confirm('Yakin ingin mengkonfirmasi pengambilan barang ini?');">
                        @csrf
                        <button type="submit" class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition">
                            Konfirmasi Pengambilan
                        </button>
                    </form>
                @else
                    <button type="button" class="mt-4 w-full bg-gray-400 text-white py-2 px-4 rounded-lg cursor-not-allowed" disabled>
                        Sudah Diambil
                    </button>
                @endif
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500">
                Tidak ada barang yang sedang dijadwalkan untuk pengambilan.
            </div>
        @endforelse
    </div>
</div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>