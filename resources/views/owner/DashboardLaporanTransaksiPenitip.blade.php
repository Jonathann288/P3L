<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Admin</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
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
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700  rounded-lg">
                        <i class="fa-solid fa-book"></i>
                        <span>Laporan Request Donasi</span>
                    </a>
                    <a href="{{ route('owner.DashboardLaporanTransaksiPenitip') }}"
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fa-solid fa-book"></i>
                        <span>Laporan Transaksi Penitip</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <div class="p-8 bg-gray-100">
        <h1 class="text-3xl font-bold mb-5">Laporan Transaksi Penitip</h1>

        <div class="mb-6">
            <input type="text" id="searchInput" placeholder="Cari Laporan Transaksi Penitip..." class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2
                focus:ring-blue-500">
        </div>


        @foreach($transaksiPenitipan as $idPenitip => $transaksiGroup)
                @php
                    $firstTransaksi = $transaksiGroup->first();
                    $penitip = $firstTransaksi->penitip ?? null;
                    $bulan = $firstTransaksi->tanggal_penitipan ? \Carbon\Carbon::parse($firstTransaksi->tanggal_penitipan)->format('m') : '-';
                    $tahun = $firstTransaksi->tanggal_penitipan ? \Carbon\Carbon::parse($firstTransaksi->tanggal_penitipan)->format('Y') : '-';
                @endphp

                <div class="mb-8 bg-white p-4 rounded-lg shadow" data-index="{{ $loop->index }}"
                    data-id-penitip="{{ $penitip->id }}" data-nama-penitip="{{ $penitip->nama_penitip ?? 'Unknown' }}"
                    data-bulan="{{ $bulan }}" data-tahun="{{ $tahun }}">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class=" text-xl font-semibold">
                            Penitip: {{ $penitip->nama_penitip ?? 'Unknown' }}
                        </h2>
                        <button onclick="generatePDFForIndex({{ $loop->index }})"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1 px-3 rounded-lg flex items-center text-sm">
                            <i class="fas fa-print mr-1"></i> Cetak Laporan
                        </button>
                    </div>

                    <div class="overflow-auto rounded-lg shadow">
                        <table class="min-w-full bg-white border border-gray-300 text-sm">
                            <thead class="bg-gray-100 text-left">
                                <tr>
                                    <th class="px-4 py-2 border">Kode Produk</th>
                                    <th class="px-4 py-2 border">Nama Produk</th>
                                    <th class="px-4 py-2 border">Tanggal Masuk</th>
                                    <th class="px-4 py-2 border">Tanggal Laku</th>
                                    <th class="px-4 py-2 border">Harga Jual Bersih</th>
                                    <th class="px-4 py-2 border">Bonus Jual Tercepat</th>
                                    <th class="px-4 py-2 border">Pendapatan</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($transaksiGroup as $transaksi)
                                    @foreach($transaksi->detailtransaksipenitipan as $detail)
                                        @php
                                            // Hitung harga jual bersih (80% dari harga barang)
                                            $hargaBarang = $detail->barang->harga_barang ?? 0;
                                            $hargaJualBersih = $hargaBarang - ($hargaBarang * 0.2);

                                            // Hitung bonus jual cepat (10% dari harga jual bersih jika terjual dalam 7 hari)
                                            $bonus = 0;
                                            $tanggalPenitipan = \Carbon\Carbon::parse($transaksi->tanggal_penitipan);
                                            $tanggalLaku = $detail->transaksipenjualan ? \Carbon\Carbon::parse($detail->transaksipenjualan->tanggal_lunas) : null;

                                            if ($tanggalLaku && $tanggalLaku->diffInDays($tanggalPenitipan) <= 7) {
                                                $bonus = $hargaJualBersih * 0.10;
                                            }

                                            // Hitung total pendapatan (harga jual bersih + bonus)
                                            $totalPendapatan = $hargaJualBersih + $bonus;
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-2 border">{{ $detail->barang->id ?? '-' }}</td>
                                            <td class="px-4 py-2 border">{{ $detail->barang->nama_barang ?? '-' }}</td>
                                            <td class="px-4 py-2 border">
                                                {{ $transaksi->tanggal_penitipan ? \Carbon\Carbon::parse($transaksi->tanggal_penitipan)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-2 border">
                                                @if($detail->transaksipenjualan)
                                                    {{ \Carbon\Carbon::parse($detail->transaksipenjualan->tanggal_lunas)->format('d/m/Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-2 border">Rp {{ number_format($hargaJualBersih, 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 border">Rp {{ number_format($bonus, 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 border">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-100 font-semibold">
                                    <td colspan="7" class="px-4 py-3 border text-right">
                                        Total Pendapatan: Rp {{ number_format($transaksiGroup->sum(function ($transaksi) {
                $total = 0;
                foreach ($transaksi->detailtransaksipenitipan as $detail) {
                    $hargaBarang = $detail->barang->harga_barang ?? 0;
                    $hargaJualBersih = $hargaBarang - ($hargaBarang * 0.2);

                    $bonus = 0;
                    $tanggalPenitipan = \Carbon\Carbon::parse($transaksi->tanggal_penitipan);
                    $tanggalLaku = $detail->transaksipenjualan ? \Carbon\Carbon::parse($detail->transaksipenjualan->tanggal_lunas) : null;

                    if ($tanggalLaku && $tanggalLaku->diffInDays($tanggalPenitipan) <= 7) {
                        $bonus = $hargaJualBersih * 0.10;
                    }

                    $total += $hargaJualBersih + $bonus;
                }
                return $total;
            }), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
        @endforeach
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        function generatePDFForIndex(index) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');
            const pageWidth = doc.internal.pageSize.getWidth();

            const container = document.querySelector(`[data-index="${index}"]`);
            if (!container) {
                alert('Tabel tidak ditemukan.');
                return;
            }

            // Ambil data dari atribut data di container
            const idPenitip = container.getAttribute('data-id-penitip') || '-';
            const namaPenitip = container.getAttribute('data-nama-penitip') || '-';
            const bulan = container.getAttribute('data-bulan') || '-';
            const tahun = container.getAttribute('data-tahun') || '-';

            // Konversi angka bulan ke nama bulan
            const namaBulan = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            const bulanIndex = parseInt(bulan, 10);
            const bulanHuruf = (!isNaN(bulanIndex) && bulanIndex >= 1 && bulanIndex <= 12)
                ? namaBulan[bulanIndex - 1]
                : '-';

            // Header toko
            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            doc.text('ReUse Mart', pageWidth / 2, 15, { align: 'center' });

            doc.setFontSize(10);
            doc.setFont('helvetica', 'normal');
            doc.text('Jl. Green Eco Park No. 456 Yogyakarta', pageWidth / 2, 20, { align: 'center' });
            doc.text('Telp: (0274) 123-4567 | Email: info@reusermart.com', pageWidth / 2, 25, { align: 'center' });

            doc.setDrawColor(0, 0, 0);
            doc.setLineWidth(0.5);
            doc.line(15, 30, pageWidth - 15, 30);

            // Judul laporan
            const titleElem = container.querySelector('h2');
            const titleText = titleElem ? titleElem.innerText.trim() : 'Laporan Transaksi Penitip';

            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            doc.text(titleText.toUpperCase(), pageWidth / 2, 40, { align: 'center' });

            // Info Penitip
            const infoY = 47;
            doc.setFontSize(10);
            doc.setFont('helvetica', 'normal');
            doc.text(`ID Penitip : ${idPenitip}`, 15, infoY);
            doc.text(`Nama Penitip : ${namaPenitip}`, 15, infoY + 6);
            doc.text(`Bulan : ${bulanHuruf}`, 15, infoY + 12);
            doc.text(`Tahun : ${tahun}`, 15, infoY + 18);

            // Tanggal cetak
            const currentDate = new Date();
            const formattedDate = currentDate.toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            });
            doc.text(`Tanggal cetak: ${formattedDate}`, 15, infoY + 28);

            // Ambil header tabel
            const headers = [];
            container.querySelectorAll('thead th').forEach(th => headers.push(th.innerText.trim()));

            // Ambil data dari tabel
            const rows = container.querySelectorAll('tbody tr');
            const data = [];
            rows.forEach(row => {
                const cols = row.querySelectorAll('td');
                const rowData = [];
                cols.forEach(col => rowData.push(col.innerText.trim()));
                data.push(rowData);
            });

            // Ambil total pendapatan dari footer tabel
            const totalElem = container.querySelector('tfoot tr td');
            const totalPendapatan = totalElem ? totalElem.innerText.trim() : 'Total Pendapatan: Rp 0';

            // Ukuran kolom
            const columnWidths = [25, 40, 25, 25, 30, 30, 30];
            const totalTableWidth = columnWidths.reduce((a, b) => a + b, 0);
            const startX = (pageWidth - totalTableWidth) / 2;

            // Cetak tabel ke PDF
            doc.autoTable({
                startY: infoY + 38,
                margin: { left: startX, right: startX },
                head: [headers],
                body: data,
                foot: [[{ content: totalPendapatan, colSpan: 7, styles: { halign: 'right', fontStyle: 'bold', fillColor: [230, 230, 230] } }]],
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
                footStyles: {
                    fillColor: [230, 230, 230],
                    textColor: [0, 0, 0],
                    fontStyle: 'bold',
                    halign: 'right',
                    lineWidth: 0.3
                },
                alternateRowStyles: {
                    fillColor: [250, 250, 250]
                },
                columnStyles: {
                    0: { cellWidth: columnWidths[0], halign: 'center' },
                    1: { cellWidth: columnWidths[1], halign: 'left' },
                    2: { cellWidth: columnWidths[2], halign: 'center' },
                    3: { cellWidth: columnWidths[3], halign: 'center' },
                    4: { cellWidth: columnWidths[4], halign: 'right' },
                    5: { cellWidth: columnWidths[5], halign: 'right' },
                    6: { cellWidth: columnWidths[6], halign: 'right' },
                },
                theme: 'grid',
                tableWidth: totalTableWidth
            });

            doc.save(`Laporan_Transaksi_Penitip_${namaPenitip}_${bulanHuruf}_${tahun}.pdf`);
        }

        document.getElementById("searchInput").addEventListener("input", function () {
            const query = this.value.toLowerCase();
            const cards = document.querySelectorAll("[data-nama-penitip]");

            cards.forEach(card => {
                const namaPenitip = card.getAttribute("data-nama-penitip").toLowerCase();
                if (namaPenitip.includes(query)) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        });
    </script>

</body>

</html>