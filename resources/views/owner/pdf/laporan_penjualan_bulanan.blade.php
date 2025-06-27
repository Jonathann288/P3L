<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Bulanan</title>
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
        .chart-container {
            width: 100%;
            height: 300px;
            margin: 20px 0;
            text-align: center;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="header">
        <h1>ReUse Mart</h1>
        <p>Jl. Green Eco Park No. 456 Yogyakarta</p>
        <h2>LAPORAN PENJUALAN BULANAN</h2>
        <p>Tahun: {{ $tahun }}</p>
        <p>Tanggal cetak: {{ $tanggal_cetak }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Jumlah Barang Terjual</th>
                <th>Jumlah Penjualan Kotor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $bulan => $item)
            <tr>
                <td>{{ $item['bulan'] }}</td>
                <td>{{ $item['jumlah_terjual'] }}</td>
                <td>Rp {{ number_format($item['total_penjualan'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td>Total</td>
                <td>{{ $totalTerjual }}</td>
                <td>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="chart-container">
        <h3>Grafik Penjualan Bulanan Tahun {{ $tahun }}</h3>
        @if(isset($chartImage))
        <img src="data:image/png;base64,{{ $chartImage }}" style="width:100%; max-height:400px;">
        @endif
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem ReUse Mart</p>
    </div>
</body>
</html> 