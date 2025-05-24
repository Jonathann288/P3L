<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Nota Penitipan Barang</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f0;
            padding: 20px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 20px;
        }


        .invoice-title {
            font-size: 36px;
            font-weight: 300;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #333;
        }

        .invoice-info {
            text-align: right;
            font-size: 13px;
            color: #666;
        }

        .bill-to {
            margin-bottom: 30px;
        }

        .bill-to-label {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
            color: #666;
        }

        .customer-info {
            font-size: 14px;
            line-height: 1.8;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            font-size: 13px;
        }

        .details-table th {
            background-color: #f8f8f8;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            color: #666;
            border-bottom: 1px solid #ddd;
        }

        .details-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #eee;
        }

        .details-table tr:hover {
            background-color: #fafafa;
        }

        .total-section {
            margin-top: 30px;
            text-align: right;
        }

        .total-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .total-label {
            width: 120px;
            text-align: right;
            padding-right: 20px;
            color: #666;
        }

        .total-value {
            width: 100px;
            text-align: right;
            font-weight: 600;
        }

        .final-total {
            border-top: 2px solid #333;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
        }

        .final-total .total-label {
            font-size: 16px;
            color: #333;
        }

        .thank-you {
            margin: 40px 0 30px 0;
            font-size: 16px;
            font-weight: 500;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }

        .payment-info {
            font-size: 12px;
            color: #666;
        }

        .payment-info-title {
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 8px;
            color: #333;
        }

        .company-info {
            text-align: right;
            font-size: 12px;
            color: #666;
        }

        .company-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .photo-list {
            margin-top: 20px;
        }

        .photo-list ul {
            list-style-type: none;
            padding: 0;
        }

        .photo-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            background-color: #e8f5e8;
            color: #2d5a2d;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <div class="invoice-title">NOTA PENITIPAN</div>
            </div>
            <div class="invoice-info">
                <div><strong>No. Transaksi:</strong> {{ $titipan->id ?? '12345' }}</div>
                <div>{{ $titipan->tanggal_penitipan->format('d M Y') ?? '26 July 2025' }}</div>
            </div>
        </div>

        <!-- Bill To Section -->
        <div class="bill-to">
            <div class="bill-to-label">PENITIP:</div>
            <div class="customer-info">
                <div><strong>{{ $titipan->penitip->nama_penitip ?? 'Nama Penitip' }}</strong></div>
                <div>{{ $titipan->penitip->email_penitip ?? 'email@example.com' }}</div>
                <div>{{ $titipan->penitip->alamat_penitip ?? 'Alamat Penitip' }}</div>
            </div>
        </div>

        <!-- Details Table -->
        <table class="details-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Keterangan</th>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 30%;">Detail</th>
                    <th style="width: 15%;">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Tanggal Penitipan</td>
                    <td>{{ $titipan->tanggal_penitipan->format('d/m/Y') ?? '26/07/2025' }}</td>
                    <td>Barang diterima dan dicatat</td>
                    <td><span class="status-badge">Aktif</span></td>
                </tr>
                <tr>
                    <td>Tanggal Akhir Penitipan</td>
                    <td>{{ $titipan->tanggal_akhir_penitipan->format('d/m/Y') ?? '26/08/2025' }}</td>
                    <td>Periode penitipan berakhir</td>
                    <td><span class="status-badge">Terjadwal</span></td>
                </tr>
                <tr>
                    <td>Batas Pengambilan</td>
                    <td>{{ $titipan->tanggal_batas_pengambilan->format('d/m/Y') ?? '02/09/2025' }}</td>
                    <td>Batas waktu pengambilan barang</td>
                    <td><span class="status-badge">Terjadwal</span></td>
                </tr>
                <tr>
                    <td>Petugas Penerima</td>
                    <td>{{ $titipan->tanggal_penitipan->format('d/m/Y') ?? '26/07/2025' }}</td>
                    <td>{{ $titipan->pegawai->nama_pegawai ?? 'Nama Pegawai' }}</td>
                    <td><span class="status-badge">Verified</span></td>
                </tr>
            </tbody>
        </table>

        <!-- Photo List Section -->
        @if(!empty($titipan->foto_barang))
            <div class="photo-list">
                <div class="bill-to-label">DAFTAR FOTO BARANG:</div>
                <ul>
                    @foreach($titipan->foto_barang as $index => $foto)
                        <li>{{ $index + 1 }}. {{ $foto }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <!-- Thank You Message -->
        <div class="thank-you">
            Terima kasih atas kepercayaan Anda!
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="payment-info">
                <div class="payment-info-title">Informasi Penting</div>
                <div>• Harap simpan nota ini sebagai bukti</div>
                <div>• Untuk pengambilan barang, harap bawa nota ini</div>
            </div>
            <div class="company-info">
                <div class="company-name">REUSEMART</div>
                <div>Jalan Magelang No. 123,Sleman Yogyakarta</div>
                <div>Telp: (021) 1234-5678</div>
                <div>Email: reusemart@penitipan.com</div>
            </div>
        </div>
    </div>
</body>

</html>