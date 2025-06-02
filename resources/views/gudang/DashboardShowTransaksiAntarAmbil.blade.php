<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pembayaran</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white p-6 flex flex-col justify-between w-64">
            <div>
                <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
                <nav>
                    <div class="space-y-4">
                        <a href="{{ route('gudang.DashboardGudang') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                            <span  class="text-2xl font-bold">{{ $pegawaiLogin->nama_pegawai }}</span>
                        </a>
                        <a href="{{ route('gudang.DashboardTitipanBarang') }}"
                            class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                            <i class="fas fa-dolly mr-2"></i>
                            <span>Tambah Titip Barang</span>
                        </a>
                        <a href="{{ route('gudang.DaftarBarang') }}"
                            class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                            <i class="fas fa-boxes mr-2"></i>
                            <span>Daftar Barang</span>
                        </a>
                        <a href="{{ route('gudang.DasboardCatatanPengembalianBarang') }}"
                            class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                            <i class="fas fa-boxes mr-2"></i>
                            <span>Catatan Pengembalian Barang</span>
                        </a>
                        <a href="{{ route('gudang.DashboardShowTransaksiAntarAmbil') }}"
                            class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                            <i class="fa-solid fa-truck"></i> 
                            <span>Daftar Transakasi Kirim dan Ambil sendiri</span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <div class="container mx-auto px-4 py-8">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Daftar Transaksi Antar dan Ambil Sendiri</h1>
                        <p class="text-gray-600">Mengelola transaksi pengiriman dan ambil sendiri pelanggan</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            {{ count($transaksiAntar) }} Antar Kurir
                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            {{ count($transaksiAmbilsendiri) }} Ambil Sendiri
                        </span>
                    </div>
                </div>

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <strong class="font-medium">Error: </strong> {{ session('error') }}
                        </div>
                    </div>
                @endif

                <!-- Pending Transactions Section -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-10">
                    <div class="p-6 bg-blue-600 border-b border-blue-700">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="fa-solid fa-truck"></i> 
                            <span> Antar Kurir</span>
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Pembeli</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID Transaksi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Barang</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Transaksi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Pembayaran</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Transaksi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Kurir</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($transaksiAntar as $index => $transaksi)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span
                                                        class="text-blue-600 font-medium">{{ substr($transaksi->pembeli->nama_pembeli ?? 'N', 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $transaksi->pembeli->nama_pembeli ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-gray-800 text-xs font-medium rounded">{{ $transaksi->id }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1 max-w-xs">
                                                @foreach($transaksi->detailTransaksi as $detail)
                                                    <span
                                                        class="px-2 py-1 bg-blue-100 text-gray-800 text-xs rounded">{{ $detail->barang->nama_barang }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaksi->tanggal_transaksi->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full capitalize">{{ $transaksi->status_pembayaran }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full capitalize">{{ $transaksi->status_transaksi }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-gray-800 text-xs rounded">{{  $transaksi->kurir->nama_pegawai ?? 'Belum ada kurir' }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <button 
                                                class="text-blue-600 hover:text-blue-900 inline-flex items-center btn-detail"
                                                data-id="{{ $transaksi->id }}"
                                                data-nama="{{ $transaksi->pembeli->nama_pembeli }}"
                                                data-tanggal="{{ $transaksi->tanggal_transaksi->format('d M Y') }}"
                                                data-bukti="{{ asset('storage/bukti_pembayaran/' . $transaksi->bukti_pembayaran) }}"
                                                data-barang='@json($transaksi->detailTransaksi)'
                                                data-ongkir="{{ $transaksi->ongkir ?? '-' }}"
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                View
                                            </button>

                                           <!-- Tombol Jadwalkan -->
                                            <button 
                                                class="text-yellow-600 hover:text-yellow-900 inline-flex items-center btn-jadwal"
                                                data-id="{{ $transaksi->id_transaksi_penjualan }}"
                                                data-jam-transaksi="{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('H') }}"
                                                data-tanggal-kirim="{{ $transaksi->tanggal_kirim }}"
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M5 8a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"/>
                                                    <path d="M4 4a1 1 0 011-1h10a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                                </svg>
                                                Jadwalkan
                                            </button>
                                            <form action="{{ route('gudang.cetak-notaKurir', $transaksi->id_transaksi_penjualan) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-sm">
                                                    Cetak Nota
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center py-8">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                    </path>
                                                </svg>
                                                <p class="mt-2 text-sm font-medium text-gray-600">No pending transactions
                                                </p>
                                                <p class="text-xs text-gray-500">All transactions are verified</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Verified Transactions Section -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6 bg-blue-600 border-b border-blue-700">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                             <i class="fa-solid fa-hand-holding"></i> 
                            Di Ambil Sendiri
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Pembeli</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID Transaksi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Barang</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Transaksi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Pembayaran</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($transaksiAmbilsendiri as $index => $transaksi)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span
                                                        class="text-blue-600 font-medium">{{ substr($transaksi->pembeli->nama_pembeli ?? 'N', 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $transaksi->pembeli->nama_pembeli ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-gray-800 text-xs font-medium rounded">{{ $transaksi->id }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1 max-w-xs">
                                                @foreach($transaksi->detailTransaksi as $detail)
                                                    <span
                                                        class="px-2 py-1 bg-blue-100 text-gray-800 text-xs rounded">{{ $detail->barang->nama_barang }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaksi->tanggal_transaksi->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full capitalize">{{ $transaksi->status_pembayaran }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <button 
                                                class="text-blue-600 hover:text-blue-900 inline-flex items-center btn-detail"
                                                data-id="{{ $transaksi->id }}"
                                                data-nama="{{ $transaksi->pembeli->nama_pembeli }}"
                                                data-tanggal="{{ $transaksi->tanggal_transaksi->format('d M Y') }}"
                                                data-bukti="{{ asset('storage/bukti_pembayaran/' . $transaksi->bukti_pembayaran) }}"
                                                data-barang='@json($transaksi->detailTransaksi)'
                                                data-ongkir="{{ $transaksi->ongkir ?? '-' }}"
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                View
                                            </button>
                                            @if ($transaksi->tanggal_ambil)
                                                <button class="text-gray-400 cursor-not-allowed inline-flex items-center" disabled>
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Diambil {{ \Carbon\Carbon::parse($transaksi->tanggal_ambil)->format('d M Y') }}
                                                </button>
                                            @else
                                                <button class="btn-ambil px-2 py-1 bg-yellow-500 text-white rounded text-sm"
                                                    data-id="{{ $transaksi->id_transaksi_penjualan }}">
                                                    Jadwalkan Ambil
                                                </button>
                                            @endif

                                            @if ($transaksi->status_transaksi == 'Di siapkan')
                                            <form action="{{ route('gudang.konfirmasi-terima', $transaksi->id_transaksi_penjualan) }}" method="POST" onsubmit="return confirm('Yakin ingin konfirmasi barang sudah diterima?');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-sm">
                                                        Konfirmasi Terima
                                                    </button>
                                                </form>
                                            @elseif ($transaksi->status_transaksi == 'transaksi selesai')
                                                <button class="text-gray-400 cursor-not-allowed inline-flex items-center" disabled>
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Transaksi Selesai
                                                </button>
                                            @endif

                                            <form action="{{ route('gudang.cetak-nota', $transaksi->id_transaksi_penjualan) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-sm">
                                                    Cetak Nota
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center py-8">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                    </path>
                                                </svg>
                                                <p class="mt-2 text-sm font-medium text-gray-600">No verified transactions
                                                </p>
                                                <p class="text-xs text-gray-500">Approve pending transactions to see them
                                                    here</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div id="detailModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-xl w-full p-6 relative">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
            &times;
        </button>
        <h2 class="text-xl font-semibold mb-4">Detail Transaksi</h2>
        <p><strong>Nama Pembeli:</strong> <span id="modalNamaPembeli"></span></p>
        <p><strong>Tanggal Transaksi:</strong> <span id="modalTanggalTransaksi"></span></p>
        <p><strong>Ongkir:</strong> Rp <span id="modalOngkir"></span></p>
        <div class="mt-4">
            <h3 class="font-semibold mb-2">Barang:</h3>
            <div id="modalBarangList" class="space-y-2"></div>
        </div>
        <div class="mt-4">
            <h3 class="font-semibold mb-2">Bukti Pembayaran:</h3>
            <img id="modalBuktiPembayaran" src="" alt="Bukti Pembayaran" class="w-full max-h-60 object-contain rounded border">
        </div>
    </div>
</div>

<div id="modal-jadwal" class="fixed inset-0 bg-black bg-opacity-30 hidden justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-md shadow">
        <h2 class="text-lg font-bold mb-4">Pilih Kurir Pengiriman</h2>
        <form action="{{ route('gudang.jadwal-baru') }}" method="POST">
            @csrf
            <input type="hidden" name="id_transaksi_penjualan" id="jadwal-transaksi-id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Pilih Kurir</label>
                <select name="id_pegawai" class="mt-1 p-2 w-full border rounded" required>
                    @foreach($kurirList as $kurir)
                        <option value="{{ $kurir->id_pegawai }}">{{ $kurir->nama_pegawai }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modal-jadwal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-ambil" class="fixed inset-0 bg-black bg-opacity-30 hidden justify-center items-center z-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-md shadow">
        <h2 class="text-lg font-bold mb-4">Jadwalkan Pengambilan</h2>
        <form action="{{ route('gudang.jadwal-ambil') }}" method="POST">
            @csrf
            <input type="hidden" name="transaksi_id" id="ambil-transaksi-id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tanggal Ambil</label>
                <input type="date" name="tanggal_ambil" class="mt-1 p-2 w-full border rounded" required>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modal-ambil').classList.add('hidden')" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>



<script>
document.querySelectorAll('.btn-detail').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const nama = this.dataset.nama;
        const tanggal = this.dataset.tanggal;
        const bukti = this.dataset.bukti;
        const ongkir = this.dataset.ongkir;

        let barang = [];
        try {
            barang = JSON.parse(this.dataset.barang);
            // Ensure each item has the correct structure
            barang = barang.map(item => {
                return {
                    barang: {
                        nama_barang: item.barang?.nama_barang || 'N/A',
                        harga_barang: item.barang?.harga_barang || 0,
                        deskripsi_barang: item.barang?.deskripsi_barang || 'N/A',
                        foto_barang: item.barang?.foto_barang || ''
                    }
                };
            });
        } catch (e) {
            console.error("Failed to parse detailTransaksi:", e);
        }

        showModal(id, nama, tanggal, bukti, barang, ongkir);
    });
});

function showModal(id, namaPembeli, tanggalTransaksi, buktiPembayaran, barangList, ongkir) {
    console.log("Received barangList:", barangList); // Debug log
    
    document.getElementById('modalNamaPembeli').textContent = namaPembeli;
    document.getElementById('modalTanggalTransaksi').textContent = tanggalTransaksi;
    document.getElementById('modalOngkir').textContent = ongkir ?? '-';
    document.getElementById('modalBuktiPembayaran').src = buktiPembayaran;

    const barangContainer = document.getElementById('modalBarangList');
    barangContainer.innerHTML = ''; // clear previous

    if (!barangList || !Array.isArray(barangList)) {
        barangContainer.innerHTML = '<p>No items found</p>';
        return;
    }

    barangList.forEach(item => {
        if (!item.barang) {
            console.warn("Item missing barang property:", item);
            return;
        }

        // Handle foto_barang parsing safely
        let gambarArray = [];
        try {
            if (item.barang.foto_barang) {
                gambarArray = typeof item.barang.foto_barang === 'string' 
                    ? JSON.parse(item.barang.foto_barang) 
                    : item.barang.foto_barang;
            }
            // Ensure it's an array
            if (!Array.isArray(gambarArray)) {
                gambarArray = [gambarArray].filter(Boolean);
            }
        } catch (e) {
            console.error("Error parsing foto_barang:", e);
            gambarArray = [item.barang.foto_barang].filter(Boolean);
        }

        // Generate HTML for images
        let gambarHTML = gambarArray.map(path => {
            // Ensure path doesn't already include /images/
            const cleanPath = path.startsWith('images/') ? path.substring(7) : path;
            return `<img src="/images/${cleanPath}" alt="${item.barang.nama_barang}" class="w-20 h-20 object-cover rounded mr-2">`;
        }).join('');

        const div = document.createElement('div');
        div.className = "flex items-start gap-4 border p-2 rounded";
        
        div.innerHTML = `
            <div class="flex flex-col">
                <!-- Gambar -->
                <div class="flex justify-center mb-4">
                    ${gambarHTML}
                </div>
                
                <!-- Informasi Barang -->
                <div class="space-y-2">
                    <p class="text-lg font-semibold"><span>Nama: </span>${item.barang.nama_barang}</p>
                    <p class="text-blue-600 font-bold"><span>Harga: </span>Rp ${item.barang.harga_barang.toLocaleString()}</p>
                    <p class="text-gray-600"><span>Deskripsi: </span>${item.barang.deskripsi_barang}</p>
                </div>
            </div>
        `;

        barangContainer.appendChild(div);
    });

    document.getElementById('detailModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

document.querySelectorAll('.btn-jadwal').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.dataset.id;

        document.getElementById('jadwal-transaksi-id').value = id;
        document.getElementById('modal-jadwal').classList.remove('hidden');
    });
});

document.querySelectorAll('.btn-ambil').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.dataset.id;
        document.getElementById('ambil-transaksi-id').value = id;
        document.getElementById('modal-ambil').classList.remove('hidden');
    });
});



</script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>