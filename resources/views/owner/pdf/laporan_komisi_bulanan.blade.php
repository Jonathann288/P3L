<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Komisi Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
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
        }
        .info-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
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
        <p>Kompor laku < 7 hari, sehingga penitip mendapat bonus.</p>
        <p>Komisi ReUseMart: 20% = 400.000</p>
        <p>Tapi diberikan ke hunter 5% (100.000), sehingga sisa untuk ReUseMart: 300.000.</p>
        <p>Bonus untuk penitip: 10% dari 400.000 = 40.000, sehingga sisa untuk ReUseMart: 260.000.</p>
        <p>Rak buku laku > 1 bulan, sehingga barang ini sudah ada perpanjangan penitipan. Sehingga, komisi 30%, komisi hunter 0 berarti barang ini bukan barang hasil hunting.</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Harga Jual</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Laku</th>
                <th>Komisi Hunter</th>
                <th>Komisi ReUse Mart</th>
                <th>Bonus Penitip</th>
            </tr>
        </thead>
        <tbody>
            @php $totalKomisiHunter = 0; $totalKomisiReUseMart = 0; $totalBonusPenitip = 0; @endphp
            @foreach($komisi as $k)
            <tr>
                <td>{{ optional($k->barang->first())->kode_barang ?? 'N/A' }}</td>
                <td>{{ optional($k->barang->first())->nama_barang ?? 'N/A' }}</td>
                <td>Rp {{ number_format(optional($k->barang->first())->harga_barang ?? 0, 0, ',', '.') }}</td>
                <td>{{ optional($k->barang->first())->tanggal_masuk ? \Carbon\Carbon::parse($k->barang->first()->tanggal_masuk)->format('d/m/Y') : 'N/A' }}</td>
                <td>{{ $k->transaksipenjualan ? \Carbon\Carbon::parse($k->transaksipenjualan->tanggal_transaksi)->format('d/m/Y') : 'N/A' }}</td>
                <td>Rp {{ number_format($k->komisi_hunter ?? 0, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($k->komisi_reusemart ?? 0, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($k->komisi_penitip ?? 0, 0, ',', '.') }}</td>
            </tr>
            @php 
                $totalKomisiHunter += $k->komisi_hunter ?? 0;
                $totalKomisiReUseMart += $k->komisi_reusemart ?? 0;
                $totalBonusPenitip += $k->komisi_penitip ?? 0;
            @endphp
            @endforeach
            <tr class="total-row">
                <td colspan="5">Total</td>
                <td>Rp {{ number_format($totalKomisiHunter, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalKomisiReUseMart, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalBonusPenitip, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem ReUse Mart</p>
    </div>
</body>
</html> 