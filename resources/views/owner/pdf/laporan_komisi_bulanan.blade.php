<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Komisi Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 16px;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total-row {
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }

        .info-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .info-box h3 {
            margin-top: 0;
            font-size: 12px;
        }
        .info-box ul {
            padding-left: 20px;
            margin: 0;
        }
        .info-box li {
            margin-bottom: 4px;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>ReUse Mart</h1>
        <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
        <h2>LAPORAN KOMISI BULANAN</h2>
        <p>Bulan: {{ $bulan }} {{ $tahun }}</p>
        <p>Tanggal cetak: {{ $tanggal_cetak }}</p>
    </div>

    <div class="info-box">
        <h3>Aturan Perhitungan Komisi:</h3>
        <ul>
            <li><b>Total Komisi:</b> 20% dari harga jual. Jika penjualan > 30 hari sejak tanggal masuk, komisi menjadi 30%.</li>
            <li><b>Komisi Hunter:</b> 5% dari harga jual, jika produk merupakan hasil hunting (ada pegawai terkait).</li>
            <li><b>Bonus Penitip:</b> 10% dari <u>Total Komisi</u>, jika produk terjual dalam 7 hari sejak tanggal masuk.</li>
            <li><b>Komisi ReUse Mart:</b> Sisa dari Total Komisi setelah dikurangi Komisi Hunter dan Bonus Penitip.</li>
        </ul>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Harga Jual</th>
                <th>Tgl Masuk</th>
                <th>Tgl Laku</th>
                <th>Komisi Hunter</th>
                <th>Komisi ReUse Mart</th>
                <th>Bonus Penitip</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotalKomisiHunter = 0;
                $grandTotalKomisiReUseMart = 0;
                $grandTotalBonusPenitip = 0;
            @endphp

            @forelse($komisi as $k)
                @php
                    // --- Inisialisasi variabel ---
                    $komisiHunter = 0;
                    $komisiReUseMart = 0;
                    $bonusPenitip = 0;
                    $totalKomisi = 0;
                    
                    // --- Mengambil data penting ---
                    // CATATAN: Relasi komisi->barang seharusnya 'belongsTo'. Penggunaan ->first() mengindikasikan relasi 'hasMany'.
                    // Kode ini mengasumsikan bisa mendapatkan satu barang. Jika tidak, data tidak akan muncul.
                    $barang = $k->barang->first() ?? $k->barang; // Penyesuaian untuk menangani kedua jenis relasi
                    $transaksiPenjualan = $k->transaksipenjualan;
                    $transaksiPenitipan = optional(optional($barang)->detailTransaksiPenitipan)->transaksiPenitipan;
                    
                    // --- Lakukan perhitungan hanya jika semua data yang dibutuhkan ada ---
                    if ($barang && $transaksiPenjualan && $transaksiPenitipan) {
                        $hargaJual = $barang->harga_barang;
                        $tanggalMasuk = \Carbon\Carbon::parse($transaksiPenitipan->tanggal_penitipan);
                        $tanggalLaku = \Carbon\Carbon::parse($transaksiPenjualan->tanggal_transaksi);
                        
                        // Hitung lama penitipan dalam hari
                        $lamaPenitipanHari = $tanggalLaku->diffInDays($tanggalMasuk);
                        
                        // 1. Tentukan persentase komisi total (20% atau 30%)
                        $persentaseKomisiTotal = ($lamaPenitipanHari > 30) ? 0.30 : 0.20;
                        $totalKomisi = $hargaJual * $persentaseKomisiTotal;

                        // 2. Hitung Komisi Hunter (jika ada pegawai/hunter terkait)
                        if ($k->pegawai) {
                            $komisiHunter = $hargaJual * 0.05; // 5% dari harga jual
                        }
                        
                        // 3. Hitung Bonus Penitip (jika laku <= 7 hari)
                        if ($lamaPenitipanHari <= 7) {
                            $bonusPenitip = $totalKomisi * 0.10; // 10% dari total komisi
                        }
                        
                        // 4. Hitung Komisi ReUse Mart
                        $komisiReUseMart = $totalKomisi - $komisiHunter - $bonusPenitip;

                        // Akumulasi total untuk footer tabel
                        $grandTotalKomisiHunter += $komisiHunter;
                        $grandTotalKomisiReUseMart += $komisiReUseMart;
                        $grandTotalBonusPenitip += $bonusPenitip;
                    }
                @endphp
                
                @if ($barang && $transaksiPenjualan && $transaksiPenitipan)
                    <tr>
                         <td>{{ optional($barang)->id ?? '2025.06.10' }}</td>
                        <td>{{ $barang->nama_barang ?? 'N/A' }}</td>
                        <td class="text-right">Rp {{ number_format($barang->harga_barang ?? 0, 0, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaksiPenitipan->tanggal_penitipan)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaksiPenjualan->tanggal_transaksi)->format('d/m/Y') }}</td>
                        <td class="text-right">Rp {{ number_format($komisiHunter, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($komisiReUseMart, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($bonusPenitip, 0, ',', '.') }}</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data komisi untuk bulan dan tahun ini.</td>
                </tr>
            @endforelse

            <tr class="total-row">
                <td colspan="5">Total</td>
                <td class="text-right">Rp {{ number_format($grandTotalKomisiHunter, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($grandTotalKomisiReUseMart, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($grandTotalBonusPenitip, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem ReUse Mart</p>
    </div>
</body>
</html>