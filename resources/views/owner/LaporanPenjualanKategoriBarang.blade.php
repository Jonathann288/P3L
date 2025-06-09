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
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
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
    </div>

    <!-- Main Content -->
    <div class="p-8 bg-gray-100">
        <h2 class="text-2xl font-semibold mb-6">Laporan Penjualan per Kategori Barang</h2>
        <div class="flex justify-end mb-4">
            <a href="{{ route('laporan.kategori.cetak') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow transition duration-200">
                <i class="fa-solid fa-download mr-2"></i>Download PDF
            </a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <canvas id="grafikPenjualan" height="100"></canvas>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('grafikPenjualan');

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($kategori),
                datasets: [
                    {
                        label: 'Terjual',
                        data: @json($terjual),
                        backgroundColor: 'rgba(75, 192, 192, 0.7)'
                    },
                    {
                        label: 'Gagal Terjual',
                        data: @json($gagal),
                        backgroundColor: 'rgba(255, 99, 132, 0.7)'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Grafik Penjualan Barang per Kategori'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>