<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kategori</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .header p {
            margin: 0;
            font-size: 12px;
        }

        hr {
            margin: 10px 0;
            border: 1px solid black;
        }

        .judul-laporan {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin: 20px 0 10px;
        }

        .info {
            margin-bottom: 10px;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }

        th, td {
            border: 1px solid black;
            padding: 4px 6px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>{{ $perusahaan['nama'] }}</h2>
        <p>{{ $perusahaan['alamat'] }}</p>
        <p>Telp: {{ $perusahaan['telepon'] }} | Email: {{ $perusahaan['email'] }}</p>
    </div>

    <hr>

    <div class="judul-laporan">LAPORAN Penjualan PerKategori Barang Dalam Setahun</div>

    <p class="info">Tahun : {{ $tahun }}</p>
    <p class="info">Tanggal cetak: {{ $tanggal_cetak }}</p>

    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah item terjual</th>
                <th>Jumlah item gagal terjual</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_terjual = 0;
                $total_gagal = 0;
            @endphp
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->nama_kategori }}</td>
                    <td>{{ $item->jumlah_terjual }}</td>
                    <td>{{ $item->jumlah_gagal }}</td>
                </tr>
                @php
                    $total_terjual += $item->jumlah_terjual;
                    $total_gagal += $item->jumlah_gagal;
                @endphp
            @endforeach
            <tr>
                <th>Total</th>
                <th>{{ $total_terjual }}</th>
                <th>{{ $total_gagal }}</th>
            </tr>
        </tbody>
    </table>

</body>
</html>
