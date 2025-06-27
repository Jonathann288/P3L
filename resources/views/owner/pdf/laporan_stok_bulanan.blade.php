<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Gudang</title>
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
        <h2>LAPORAN STOK GUDANG</h2>
        <p>Tanggal cetak: {{ $tanggal_cetak }}</p>
    </div>

    <div class="info-box">
        <p>Stok yang bisa dilihat adalah stok per hari ini (sama dengan tanggal cetak). Tidak bisa dilihat stok yang kemarin-kemarin.</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>ID Penitip</th>
                <th>Nama Penitip</th>
                <th>Tanggal Masuk</th>
                <th>Perpanjangan</th>
                <th>ID Hunter</th>
                <th>Nama Hunter</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @php $totalHarga = 0; @endphp
            @foreach($barang as $b)
            <tr>
                <td>{{ $b->id }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ optional($b->detailTransaksiPenitipan)->transaksiPenitipan->penitip->id_penitip ?? '-' }}</td>
                <td>{{ optional($b->detailTransaksiPenitipan)->transaksiPenitipan->penitip->nama_penitip ?? '-' }}</td>
                <td>{{ optional($b->detailTransaksiPenitipan)->transaksiPenitipan->tanggal_penitipan ? \Carbon\Carbon::parse($b->detailTransaksiPenitipan->transaksiPenitipan->tanggal_penitipan)->format('d/m/Y') : '-' }}</td>
                <td>{{ optional($b->detailTransaksiPenitipan)->transaksiPenitipan->perpanjangan ? 'Ya' : 'Tidak' }}</td>
                <td>{{ optional($b->detailTransaksiPenitipan->transaksiPenitipan)->id_hunter ?? '-' }}</td>
                <td>{{ optional($b->detailTransaksiPenitipan->transaksiPenitipan->hunter)->nama_pegawai ?? '-' }}</td>
                <td>Rp {{ number_format($b->harga_barang, 0, ',', '.') }}</td>
            </tr>
            @php $totalHarga += $b->harga_barang; @endphp
            @endforeach
            <tr class="total-row">
                <td colspan="8">Total</td>
                <td>Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem ReUse Mart</p>
    </div>
</body>
</html> 