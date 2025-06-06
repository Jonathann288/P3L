



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpanjangan Masa Penitipan - Gudang (Tanpa Style)</title>
</head>
<body>

    {{-- Navigasi Sederhana Jika Diperlukan (opsional, bisa dihapus) --}}
    <div>
        <a href="{{ route('gudang.DashboardGudang') }}">Profile Saya</a> |
        <a href="{{ route('gudang.DashboardTitipanBarang') }}">Tambah Titip Barang</a> |
        <a href="{{ route('gudang.DaftarBarang') }}">Daftar Barang</a> |
        <a href="{{ route('gudang.showPerpanjanganPage') }}"><b>Perpanjang Penitipan</b></a>
        <hr>
    </div>


    @if(session('success'))
        <div>
            <p><strong>Berhasil!</strong> {{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div>
            <p><strong>Gagal!</strong> {{ session('error') }}</p>
        </div>
    @endif

    <div>
        <table border="1"> 
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Transaksi</th>
                    <th>Nama Penitip</th>
                    <th>Tanggal Penitipan</th>
                    <th>Tanggal Akhir Penitipan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $index => $transaksi)
                    <tr>
                        <td>
                            @if($transaksis instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $transaksis->firstItem() + $index }}
                            @else
                                {{ $index + 1 }}
                            @endif
                        </td>
                        <td>{{ $transaksi->id_transaksi_penitipan }}</td>
                        <td>{{ $transaksi->penitip ? $transaksi->penitip->nama_penitip : 'N/A' }}</td>
                        <td>{{ $transaksi->tanggal_penitipan ? \Carbon\Carbon::parse($transaksi->tanggal_penitipan)->format('d M Y') : 'N/A' }}</td>
                        <td>{{ $transaksi->tanggal_akhir_penitipan ? \Carbon\Carbon::parse($transaksi->tanggal_akhir_penitipan)->format('d M Y') : 'N/A' }}</td>
                        <td>
                            @if(!$transaksi->tanggal_pengambilan_barang)
                                <form action="{{ route('gudang.prosesPerpanjangPenitipan', $transaksi->id_transaksi_penitipan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memperpanjang masa penitipan untuk transaksi ini selama 30 hari?');">
                                    @csrf
                                    <button type="submit">Perpanjang 30 Hari</button>
                                </form>
                            @else
                                <span>Sudah Diambil</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Belum ada data transaksi penitipan yang bisa diperpanjang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transaksis instanceof \Illuminate\Pagination\LengthAwarePaginator && $transaksis->hasPages())
        <div>
            {{ $transaksis->links() }}
        </div>
    @endif

</body>
</html>