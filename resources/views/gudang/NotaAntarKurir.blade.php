<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Transaksi Penjualan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header, .footer {
            text-align: center;
        }

        .company-info {
            margin-bottom: 20px;
        }

        .info-table, .items-table, .additional-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .info-table td, .additional-info-table td {
            padding: 4px;
        }

        .additional-info-table td:first-child {
            width: 25%;
            font-weight: bold;
        }

        .items-table th, .items-table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        .items-table th {
            background-color: #f0f0f0;
        }

        .right {
            text-align: right;
        }

        .total {
            font-weight: bold;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
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
    <table class="additional-info-table" style="margin-bottom: 30px;">
        <tr>
            <td>No Nota</td>
            <td>: {{ $transaksi->no_nota ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal Pesan</td>
            <td>: {{ \Carbon\Carbon::parse($transaksi->created_at)->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Lunas pada</td>
            <td>: {{ \Carbon\Carbon::parse($transaksi->updated_at)->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Tanggal Ambil</td>
            <td>: {{ \Carbon\Carbon::parse($transaksi->tanggal_ambil)->format('d/m/Y') }}</td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td><strong>No. Transaksi:</strong> {{ $transaksi->id_transaksi_penjualan }}</td>
            <td><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal_penjualan)->format('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Nama Pembeli:</strong> {{ $transaksi->pembeli->email_pembeli }}/{{ $transaksi->pembeli->nama_pembeli }}</td>
            <td><strong>Deliviry:</strong> Kurir ReUseMart ({{ $transaksi->kurir->nama_pegawai ?? 'Belum ada kurir' }})</td>
        </tr>
        <tr>
            <td><strong>Petugas:</strong> {{ $transaksi->pegawai->nama_pegawai ?? '-' }}</td>
            <td><strong>Tanggal Cetak:</strong> {{ $tanggal_cetak }}</td>
        </tr>
    </table>

    <!-- Tabel tambahan info transaksi -->

    <h4>Detail Barang</h4>
    <table class="items-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Barang Harga</th>
                <th>potongan poin</th>
                <th>Poin Yang Didapat</th>
                <th>Ongkir</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($transaksi->detailTransaksi as $i => $detail)
                @php $total += $detail->total_harga; @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                    <td>Rp{{ number_format($detail->barang->harga_barang , 0, ',', '.' )}}</td>
                    <td>{{ $transaksi->poin }}</td>
                    <td class="right">{{ $transaksi->poin_dapat }}</td>
                    <td class="right">{{ $transaksi->ongkir }}</td>
                    <td class="right">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6" class="right total">Total</td>
                <td class="right total">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signature">
        <p>Hormat kami,</p>
        <br><br>
        <p><strong>{{ $transaksi->pegawai->nama_pegawai ?? 'Petugas' }}</strong></p>
    </div>

    <div class="footer">
        <hr>
        <p>Terima kasih telah berbelanja di {{ $perusahaan['nama'] }}</p>
    </div>

</body>
</html>
