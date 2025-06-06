<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Nota Penitipan Barang</title>
    <style>
        /* Reset dan Pengaturan Dasar Body */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            /* Font khas untuk nota */
            font-size: 13px;
            line-height: 1.5;
            color: #000;
            background-color: #fff;
            padding: 20px;
        }

        /* Kontainer Utama Nota */
        .invoice-container {
            max-width: 580px;
            /* << DILEBARKAN DARI 450px MENJADI 580px */
            margin: 0 auto;
            padding: 25px;
            border: 1px solid #000;
        }

        /* Bagian Header */
        .header {
            text-align: left;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .company-address {
            font-size: 13px;
            margin-bottom: 15px;
        }

        /* Bagian Informasi Utama (No Nota, Tanggal, Penitip) */
        .info-section {
            margin-bottom: 15px;
        }

        .info-line {
            display: flex;
            margin-bottom: 2px;
            /* Jarak antar baris */
        }

        .info-line .label {
            width: 170px;
            /* Lebar label disesuaikan sedikit */
            flex-shrink: 0;
            /* Mencegah label menyusut */
        }

        .info-line .value {
            /* Value akan mengisi sisa ruang */
        }

        /* Penataan Khusus untuk Alamat Penitip */
        .penitip-address {
            display: flex;
        }

        .penitip-address .label {
            width: 170px;
            /* Lebar label disesuaikan sedikit */
            flex-shrink: 0;
        }

        .penitip-address .value {
            display: block;
        }


        /* Bagian Daftar Barang */
        .items-section {
            padding-top: 15px;
            margin-top: 15px;
            border-top: 1px dashed #000;
            /* Garis pemisah */
        }

        .item {
            display: flex;
            justify-content: space-between;
            /* Mendorong nama dan harga berjauhan */
            margin-bottom: 5px;
            /* Jarak antar item utama */
        }

        .item-details {
            padding-left: 20px;
            /* Indentasi untuk detail di bawah item */
            margin-bottom: 15px;
            /* Jarak setelah detail item */
        }

        /* Bagian Footer (Penerima QC) */
        .footer-section {
            margin-top: 30px;
            /* Memberi jarak dari item terakhir */
        }

        .qc-person {
            margin-top: 60px;
            /* Memberi jarak vertikal yang signifikan untuk nama */
        }

        /* Modifikasi untuk memberi jarak pada header tabel */
        .details-table th {

            /* Padding: 12px (atas/bawah), 18px (kiri/kanan) */
            padding: 12px 18px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #ddd;
        }

        /* Modifikasi untuk memberi jarak pada sel isi tabel */
        .details-table td {
            /* Padding: 15px (atas/bawah), 18px (kiri/kanan) */
            padding: 15px 18px;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <div class="invoice-title">ReUse Mart</div>
            </div>
            <div class="invoice-info">
                <div>Jl. Green Eco Park No. 456 Yogyakarta</div>
                <div><strong>No. Transaksi:</strong> {{ $titipan->id }}</div>
                <div>{{ $titipan->tanggal_penitipan->format('d M Y')  }}</div>
            </div>
        </div>

        <!-- Bill To Section -->
        <div class="bill-to">
            <div class="bill-to-label">PENITIP:</div>
            <div class="customer-info">
                <div><strong>{{ $titipan->penitip->nama_penitip}}</strong></div>
                <div>{{ $titipan->penitip->email_penitip }}</div>
                <div>{{ $titipan->penitip->nomor_telepon_penitip  }}</div>
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
                @php
                    $barang = optional($titipan->detailtransaksipenitipan->first())->barang;
                    $penanggungJawab = optional(optional($barang)->detailTransaksiPenitipan->first())->transaksiPenitipan->pegawai;
                @endphp

                <tr>
                    <td>Info Barang</td>
                    <td>{{ $titipan->tanggal_penitipan->format('d/m/Y') }}</td>
                    <td>
                        @if($barang)
                            Nama: {{ $barang->nama_barang }}<br>
                            Kategori: {{ $barang->kategoribarang->nama_kategori }}<br>
                            Berat: {{ $barang->berat_barang }} kg<br>
                            Harga: Rp {{ number_format($barang->harga_barang, 0, ',', '.') }}
                        @else
                            Tidak ada data barang
                        @endif
                    </td>
                    <td><span class="status-badge">Verified</span></td>
                </tr>

                <tr>
                    <td> QC Penanggung Jawab</td>
                    <td>{{ $titipan->tanggal_penitipan->format('d/m/Y') }}</td>
                    <td>
                        @if($penanggungJawab)
                            {{ $penanggungJawab->nama_pegawai }}<br>
                            ( {{ $penanggungJawab->id }} )
                            Telepon: {{ $penanggungJawab->nomor_telepon_pegawai }}
                        @else
                            Tidak diketahui
                        @endif
                    </td>
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

        </div>
    </div>
</body>

</html>