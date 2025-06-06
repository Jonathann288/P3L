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
                    <a href="{{ route('owner.DashboardLaporanDonasiBarang') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-newspaper"></i>
                        <span>Laporan Donasi Barang</span>
                    </a>
                    <a href="{{ route('owner.DashboardLaporanRequestDonasi') }}"
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
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
            <h1 class="text-3xl font-bold">Laporan Request Donasi</h1>
            <button onclick="generatePDF()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                <i class="fas fa-print mr-2"></i>
                Cetak Laporan Request Donasi
            </button>
        </div>

        <div class="overflow-auto rounded-lg shadow">
            <table class="min-w-full bg-white border border-gray-300 text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="px-4 py-2 border">ID Organisasi</th>
                        <th class="px-4 py-2 border">Nama Organisasi</th>
                        <th class="px-4 py-2 border">Alamat Organisasi</th>
                        <th class="px-4 py-2 border">Deskripsi Request</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requestdonasi as $index => $d)
                        <tr class="border-t">
                            <td class="py-3 px-6">{{ $d->organisasi->id }}</td>
                            <td class="py-3 px-6">{{ $d->organisasi->nama_organisasi }}</td>
                            <td class="py-3 px-6">{{ $d->organisasi->alamat_organisasi }}</td>
                            <td class="py-3 px-6">{{ $d->deskripsi_request }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <script>
        // Data request donasi di-passing dari Blade ke JS
        const dataRequestDonasi = {!! json_encode($requestdonasi) !!};

        function generatePDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');

            const pageWidth = doc.internal.pageSize.getWidth();

            // Header
            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            doc.text('ReUse Mart', pageWidth / 2, 15, { align: 'center' });

            doc.setFontSize(10);
            doc.setFont('helvetica', 'normal');
            doc.text('Jl. Green Eco Park No. 456 Yogyakarta', pageWidth / 2, 20, { align: 'center' });
            doc.text('Telp: (0274) 123-4567 | Email: info@reusermart.com', pageWidth / 2, 25, { align: 'center' });

            // Garis horizontal
            doc.setDrawColor(0, 0, 0);
            doc.setLineWidth(0.5);
            doc.line(15, 30, pageWidth - 15, 30);

            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            doc.text('LAPORAN REQUEST DONASI', pageWidth / 2, 40, { align: 'center' });

            // Tanggal cetak
            const currentDate = new Date();
            const formattedDate = currentDate.toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            });
            doc.setFontSize(10);
            doc.setFont('helvetica', 'normal');
            doc.text(`Tanggal cetak: ${formattedDate}`, 15, 50);

            // Data tabel diubah ke array untuk autoTable
            const tableData = dataRequestDonasi.map(item => [
                item.organisasi.id,
                item.organisasi.nama_organisasi,
                item.organisasi.alamat_organisasi,
                item.deskripsi_request
            ]);

            // Kolom dan lebar masing-masing kolom
            const columnWidths = [25, 50, 60, 50];
            const totalTableWidth = columnWidths.reduce((a, b) => a + b, 0);
            const startX = (pageWidth - totalTableWidth) / 2;

            doc.autoTable({
                startY: 60,
                margin: { left: startX, right: startX },
                head: [['ID Organisasi', 'Nama Organisasi', 'Alamat Organisasi', 'Deskripsi Request']],
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
                    3: { cellWidth: columnWidths[3], halign: 'left' }
                },
                theme: 'grid',
                tableWidth: totalTableWidth
            });

            doc.save(`laporan_request_donasi_${currentDate.getFullYear()}.pdf`);
        }
    </script>

</body>

</html>