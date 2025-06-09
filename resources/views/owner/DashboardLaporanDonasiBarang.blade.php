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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
</head>

<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">

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
                    <a href="{{route('owner.historyDonasi')}}"
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
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fa-solid fa-newspaper"></i>
                        <span>Laporan Donasi Barang</span>
                    </a>
                    <a href="{{ route('owner.DashboardLaporanRequestDonasi') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-book"></i>
                        <span>Laporan Request Donasi</span>
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
    </div>

    <div class="p-8 bg-gray-100">
        <div class="flex justify-between items-center mb-5">
            <h1 class="text-3xl font-bold">Laporan Donasi Barang</h1>
            <button onclick="generatePDF()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                <i class="fas fa-print mr-2"></i>
                Cetak Laporan Donasi
            </button>
        </div>

        <div class="overflow-auto rounded-lg shadow">
            <table class="min-w-full bg-white border border-gray-300 text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2 border">ID Barang</th>
                        <th class="px-4 py-2 border">Nama Barang</th>
                        <th class="px-4 py-2 border">ID Penitip</th>
                        <th class="px-4 py-2 border">Nama Penitip</th>
                        <th class="px-4 py-2 border">Tanggal Donasi</th>
                        <th class="px-4 py-2 border">Nama Organisasi</th>
                        <th class="px-4 py-2 border">Nama Penerima</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporanDonasi as $donasi)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border">{{ $donasi['id_barang'] }}</td>
                            <td class="px-4 py-2 border">{{ $donasi['nama_barang'] }}</td>
                            <td class="px-4 py-2 border">{{ $donasi['id_penitip'] }}</td>
                            <td class="px-4 py-2 border">{{ $donasi['nama_penitip'] }}</td>
                            <td class="px-4 py-2 border">
                                {{ \Carbon\Carbon::parse($donasi['tanggal_donasi'])->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-2 border">{{ $donasi['nama_organisasi'] }}</td>
                            <td class="px-4 py-2 border">{{ $donasi['nama_penerima'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">Tidak ada data donasi barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-10 bg-white p-6 rounded shadow" id="chart-container">
            <h2 class="text-xl font-semibold mb-4">Grafik Donasi per Organisasi</h2>
            <canvas id="donasiChart" height="100"></canvas>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <script>
        const { jsPDF } = window.jspdf;

        const chartLabels = {!! json_encode($laporanDonasi->pluck('nama_organisasi')->unique()->values()) !!};
        const chartData = {!! json_encode(
    $laporanDonasi->groupBy('nama_organisasi')->map(fn($group) => $group->count())->values()
) !!};

        const ctx = document.getElementById('donasiChart').getContext('2d');
        const donasiChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Jumlah Donasi',
                    data: chartData,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        async function generatePDF() {
            const doc = new jsPDF('l', 'mm', 'a4');

            const pageWidth = doc.internal.pageSize.getWidth();
            const pageHeight = doc.internal.pageSize.getHeight();

            const data = {!! json_encode($laporanDonasi) !!};
            const currentDate = new Date();
            const formattedDate = currentDate.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            const currentYear = currentDate.getFullYear();

            // Header with horizontal line
            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            doc.text('ReUse Mart', pageWidth / 2, 15, { align: 'center' });

            doc.setFontSize(10);
            doc.setFont('helvetica', 'normal');
            doc.text('Jl. Green Eco Park No. 456 Yogyakarta', pageWidth / 2, 20, { align: 'center' });
            doc.text('Telp: (0274) 123-4567 | Email: info@reusermart.com', pageWidth / 2, 25, { align: 'center' });

            // Horizontal line
            doc.setDrawColor(0, 0, 0);
            doc.setLineWidth(0.5);
            doc.line(20, 30, pageWidth - 20, 30);

            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            doc.text('LAPORAN DONASI BARANG', pageWidth / 2, 40, { align: 'center' });

            doc.setFontSize(10);
            doc.setFont('helvetica', 'normal');
            doc.text(`Tahun : ${currentYear}`, 20, 50);
            doc.text(`Tanggal cetak: ${formattedDate}`, 20, 55);

            const tableData = data.map(item => [
                item.id_barang,
                item.nama_barang,
                item.id_penitip,
                item.nama_penitip,
                formatDate(item.tanggal_donasi),
                item.nama_organisasi,
                item.nama_penerima
            ]);

            function formatDate(dateStr) {
                const date = new Date(dateStr);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            }

            const columnWidths = [30, 40, 25, 35, 30, 50, 35];
            const totalTableWidth = columnWidths.reduce((sum, width) => sum + width, 0);
            const startX = (pageWidth - totalTableWidth) / 2;

            doc.autoTable({
                startY: 60,
                margin: { left: startX, right: startX },
                head: [['Kode Produk', 'Nama Produk', 'Id Penitip', 'Nama Penitip', 'Tanggal Donasi', 'Organisasi', 'Nama Penerima']],
                body: tableData,
                styles: {
                    fontSize: 9,
                    cellPadding: { top: 3, bottom: 3, left: 2, right: 2 },
                    valign: 'middle',
                    halign: 'center',
                    lineWidth: 0.1,
                    lineColor: 0
                },
                headStyles: {
                    fillColor: [240, 240, 240],
                    textColor: [0, 0, 0],
                    fontStyle: 'bold',
                    halign: 'center',
                    lineWidth: 0.3
                },
                alternateRowStyles: {
                    fillColor: [250, 250, 250]
                },
                columnStyles: {
                    0: { cellWidth: columnWidths[0], halign: 'center' },
                    1: { cellWidth: columnWidths[1], halign: 'center' },
                    2: { cellWidth: columnWidths[2], halign: 'center' },
                    3: { cellWidth: columnWidths[3], halign: 'center' },
                    4: { cellWidth: columnWidths[4], halign: 'center' },
                    5: { cellWidth: columnWidths[5], halign: 'center' },
                    6: { cellWidth: columnWidths[6], halign: 'center' }
                },
                theme: 'grid',
                tableWidth: totalTableWidth
            });

            doc.save(`laporan_donasi_barang_${currentYear}.pdf`);
        }
    </script>
</body>

</html>