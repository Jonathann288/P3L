<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">

    <!-- Sidebar Navigation -->
    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('owner.DashboardOwner') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-user-circle mr-2"></i>
                        <span>{{ $pegawaiLogin->nama_pegawai }}</span>
                    </a>
                    <a href="{{ route('owner.DashboardDonasi') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                        <span>Daftar Request Donasi</span>
                    </a>
                    <a href="{{ route('owner.historyDonasi') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Histroy Request Donasi</span>
                    </a>
                    <a href="{{ route('owner.DashboardLaporan') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
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
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fa-solid fa-calendar-times"></i>
                        <span>Laporan Penitipan Habis</span>
                    </a>
                    <a href="{{ route('owner.LaporanPenjualanHunter') }}"
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fa-solid fa-calendar-times"></i>
                        <span>Laporan penjualan hunter</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-8 bg-gray-100">
        <h2 class="text-2xl font-semibold mb-6">Laporan Penitipan yang Masa Titipannya Telah Habis</h2>
        <div class="flex justify-end mb-4">
            <a href="{{ route('laporan.penitipanHabis.cetak') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow transition duration-200">
                <i class="fa-solid fa-download mr-2"></i>Download PDF
            </a>
        </div>
        <div class="overflow-x-auto bg-white rounded-lg shadow p-4">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">ID Barang</th>
                        <th class="px-4 py-2 text-left">Nama Produk</th>
                        <th class="px-4 py-2 text-left">ID Penitip</th>
                        <th class="px-4 py-2 text-left">Nama Penitip</th>
                        <th class="px-4 py-2 text-left">Tanggal Titip</th>
                        <th class="px-4 py-2 text-left">Tanggal Akhir Penitipan</th>
                        <th class="px-4 py-2 text-left">Tanggal Batas Pengambilan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($data as $row)
                        <tr>
                            <td class="px-4 py-2">{{ $row->id }}</td>
                            <td class="px-4 py-2">{{ $row->nama_barang }}</td>
                            <td class="px-4 py-2">{{ $row->id }}</td>
                            <td class="px-4 py-2">{{ $row->nama_penitip }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($row->tanggal_penitipan)->format('d-m-Y') }}</td>
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($row->tanggal_akhir_penitipan)->format('d-m-Y') }}</td>
                            <td class="px-4 py-2">
                                {{ \Carbon\Carbon::parse($row->tanggal_batas_pengambilan)->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>