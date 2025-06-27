<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembatalan Transaksi</title>
    <link rel="icon" type="image/png" sizes="128x128" href="{{ asset('images/logo2.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">

    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
            <nav>
                <div class="space-y-4">
                    <div class="flex items-center space-x-4 p-3 bg-gray-700 rounded-lg">
                        <img src="{{ asset($pembeli->foto_pembeli) }}" alt="profile"
                            class="w-8 h-8 rounded-full object-cover">
                        <span>{{ $pembeli->nama_pembeli }}</span>
                    </div>
                    <a href="{{ route('pembeli.historyPembeli') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>History</span>
                    </a>
                    <a href="{{ route('pembeli.AlamatPembeli') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-cog"></i>
                        <span>Alamat</span>
                    </a>
                    <a href="{{ route('pembeli.pembatalanTransaksiValid') }}"
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fas fa-ban"></i>
                        <span>Pembatalan Transaksi</span>
                    </a>
                </div>
            </nav>
        </div>
        <div class="space-y-4 mt-auto">
            <button class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">
                <a href="{{ route('pembeli.Shop-Pembeli') }}">Kembali</a>
            </button>
        </div>
    </div>

    <div class="p-8 bg-gray-100">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-gray-800">Pembatalan Transaksi</h1>
        </div>

        @if($transaksiBisaDibatalkan->isEmpty())
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
                Tidak ada transaksi yang dapat dibatalkan.
            </div>
        @else
            <div class="overflow-x-auto bg-white p-4 rounded shadow">
                <table class="min-w-full text-sm">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">No Transaksi</th>
                            <th class="py-3 px-4 text-left">Tanggal</th>
                            <th class="py-3 px-4 text-left">Total</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Aksi</th>
                        </tr>
                    </thead>


                    <tbody class="text-gray-800">
                        @foreach($transaksiBisaDibatalkan as $transaksi)
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $transaksi->id_transaksi_penjualan }}</td>
                                <td class="py-2 px-4">
                                    {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}
                                </td>
                                @php
                                    $total_harga = $transaksi->detailTransaksiPenjualan->sum('total_harga');
                                @endphp
                                <td class="py-2 px-4">Rp{{ number_format($total_harga, 0, ',', '.') }}</td>
                                <td class="py-2 px-4 capitalize">
                                    @if($transaksi->status_transaksi === 'dibatalkan pembeli')
                                        <span class="text-red-600 font-semibold">Dibatalkan Pembeli</span>
                                    @else
                                        {{ $transaksi->status_transaksi }}
                                    @endif
                                </td>

                                <td class="py-2 px-4">
                                    @if($transaksi->status_transaksi === 'sedang disiapkan')
                                        <button type="button"
                                            onclick="bukaModal('{{ $transaksi->id_transaksi_penjualan }}', '{{ $transaksi->detailTransaksiPenjualan->sum("total_harga") }}', {{ $pembeli->total_poin }})"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                            Batalkan
                                        </button>
                                    @else
                                        <span class="text-gray-500 text-sm italic"></span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <div id="modalKonfirmasi" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Konfirmasi Pembatalan</h2>
            <p class="mb-4" id="modalText"></p>
            <form id="formPembatalan" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="tutupModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Ya,
                        Batalkan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatRupiah(angka) {
            return 'Rp. ' + parseInt(angka).toLocaleString('id-ID');
        }

        function bukaModal(transaksiId, totalHarga, poinSekarang) {
            const totalHargaInt = parseInt(totalHarga);
            const poinTambahan = Math.floor(totalHargaInt / 10000);
            const poinSetelah = poinSekarang + poinTambahan;

            document.getElementById('modalText').innerText =
                `Apakah Anda yakin akan membatalkan transaksi ini, dengan total transaksi ${formatRupiah(totalHarga)} dan dikonversi menjadi poin reward sebanyak ${poinTambahan} poin? Total poin Anda setelah ini adalah ${poinSetelah} poin.`;

            const form = document.getElementById('formPembatalan');
            form.action = `/pembeli/pembatalan-transaksi/${transaksiId}`; // Pastikan route ini sesuai

            document.getElementById('modalKonfirmasi').classList.remove('hidden');
            document.getElementById('modalKonfirmasi').classList.add('flex');
        }

        function tutupModal() {
            document.getElementById('modalKonfirmasi').classList.add('hidden');
            document.getElementById('modalKonfirmasi').classList.remove('flex');
        }
    </script>

</body>

</html>