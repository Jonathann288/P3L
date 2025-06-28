<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Laporan</title>
    <link rel="icon" type="image/png" sizes="128x128" href="{{ asset('images/logo2.png') }}">
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
                    <a href="{{ route('owner.DashboardOwner') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-user-circle"></i>
                        <span>Profil</span>
                    </a>
                    <a href="{{ route('owner.DashboardDonasi') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                        <span>Daftar Request Donasi</span>
                    </a>
                    <a href="{{ route('owner.historyDonasi') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>History Request Donasi</span>
                    </a>
                    <a href="{{ route('owner.DashboardLaporan') }}" class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span>Laporan</span>
                    </a>
                    <a href="{{ route('owner.DashboardLaporanDonasiBarang') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-newspaper"></i>
                        <span>Laporan Donasi Barang</span>
                    </a>
                    <a href="{{ route('owner.DashboardLaporanRequestDonasi') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-book"></i>
                        <span>Laporan RequestDonasi</span>
                    </a>
                    <a href="{{ route('owner.DashboardLaporanTransaksiPenitip') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-book"></i>
                        <span>Laporan Transaksi Penitip</span>
                    </a>
                    <a href="{{route('owner.LaporanPenjualanKategoriBarang')}}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fas fa-boxes mr-2"></i>
                        <span>Laporan Penjualan Per Kategori Barang</span>
                    </a>
                    <a href="{{ route('owner.LaporanPenitipanMasaHabis') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-calendar-times"></i>
                        <span>Laporan Penitipan Habis</span>
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
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-gray-800">Laporan</h1>
        </div>

        <div class="grid grid-cols-1 gap-8">
            <!-- Laporan Penjualan Bulanan -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Laporan Penjualan Bulanan Keseluruhan</h2>
                </div>
                <p class="text-gray-600 mb-4">Menampilkan data penjualan bulanan dalam bentuk tabel dan grafik.</p>
                <form action="{{ route('owner.laporanPenjualanBulanan') }}" method="GET" class="mb-4">
                    <div class="flex items-center space-x-4">
                        <div>
                            <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                            <select name="tahun" id="tahun" class="border border-gray-300 rounded-md px-3 py-2">
                                @for ($i = date('Y'); $i >= date('Y')-5; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                <i class="fas fa-download mr-2"></i> Download PDF
                            </button>
                        </div>
                    </div>
                </form>
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500">Format laporan akan mencakup data penjualan per bulan dan total penjualan dalam tahun yang dipilih.</p>
                </div>
            </div>

            <!-- Laporan Komisi Bulanan -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Laporan Komisi Bulanan per Produk</h2>
                </div>
                <p class="text-gray-600 mb-4">Menampilkan data komisi bulanan per produk.</p>
                <form action="{{ route('owner.laporanKomisiBulanan') }}" method="GET" class="mb-4">
                    <div class="flex items-center space-x-4">
                        <div>
                            <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                            <select name="bulan" id="bulan" class="border border-gray-300 rounded-md px-3 py-2">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                            <select name="tahun" id="tahun" class="border border-gray-300 rounded-md px-3 py-2">
                                @for ($i = date('Y'); $i >= date('Y')-5; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                <i class="fas fa-download mr-2"></i> Download PDF
                            </button>
                        </div>
                    </div>
                </form>
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500">Format laporan akan mencakup data komisi per produk, komisi hunter, dan bonus penitip.</p>
                </div>
            </div>

            <!-- Laporan Stok Gudang -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Laporan Stok Gudang</h2>
                </div>
                <p class="text-gray-600 mb-4">Menampilkan data stok barang di gudang saat ini.</p>
                <form action="{{ route('owner.laporanStokGudang') }}" method="GET" class="mb-4">
                    <div class="flex items-center">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-download mr-2"></i> Download PDF
                        </button>
                    </div>
                </form>
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500">Format laporan akan mencakup data stok barang yang tersedia di gudang saat ini.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html> 