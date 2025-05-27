<!-- SEMUA COPY -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gudang</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">

    <!-- Sidebar Navigation -->
    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('gudang.DashboardGudang') }}" class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <span  class="text-2xl font-bold">{{ $pegawaiLogin->nama_pegawai }}</span>
                    </a>
                   <a href="{{ route('gudang.DashboardTitipanBarang') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-box-archive"></i>
                        <span>Titipan Barang</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>
    <!-- Main Content -->
<div class="p-6 bg-white rounded shadow-md overflow-auto">
    <h1 class="text-2xl font-semibold mb-6">Barang Titipan</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border border-gray-300">
            <thead class="bg-gray-200">
                <tr class="text-left">
                    <th class="p-3 border">Nama Barang</th>
                    <th class="p-3 border">Kategori</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detailBarang as $detail)
                    <tr class="hover:bg-gray-100">
                        <td class="p-3 border">{{ $detail->barang->nama_barang }}</td>
                        <td class="p-3 border">{{ $detail->barang->kategori->nama_kategori ?? '-' }}</td>
                        <td class="p-3 border capitalize">{{ $detail->barang->status_barang }}</td>
                        <td class="p-3 border">
                            <button 
                                class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700" 
                                onclick="openModal('{{ $detail->id_detail_transaksi_penitipan }}')"
                            >
                                Detail
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    @foreach ($detailBarang as $detail)
        <div 
            id="modal-{{ $detail->id_detail_transaksi_penitipan }}" 
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden"
        >
            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
                <button class="absolute top-3 right-3 text-gray-600 hover:text-black text-xl" onclick="closeModal('{{ $detail->id_detail_transaksi_penitipan }}')">
                    &times;
                </button>

                <h2 class="text-xl font-semibold mb-4">Detail Barang Titipan</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p><strong>Nama Barang:</strong> {{ $detail->barang->nama_barang }}</p>
                        <p><strong>Kategori:</strong> {{ $detail->barang->kategori->nama_kategori ?? '-' }}</p>
                        <p><strong>Harga:</strong> Rp{{ number_format($detail->barang->harga_barang, 0, ',', '.') }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($detail->barang->status_barang) }}</p>
                    </div>
                    <div>
                        <p><strong>Penitip:</strong> {{ $detail->transaksipenitipan->penitip->nama_penitip ?? '-' }}</p>
                        <p><strong>Tanggal Penitipan:</strong> {{ \Carbon\Carbon::parse($detail->transaksipenitipan->tanggal_penitipan)->format('d M Y') }}</p>
                        <p><strong>No. Telp:</strong> {{ $detail->transaksipenitipan->penitip->nomor_telepon_penitip ?? '-' }}</p>
                    </div>
                    @php
                        $fotoArray = [];
                        if($detail->barang->foto_barang) $fotoArray[] = $detail->barang->foto_barang;
                        if($detail->barang->foto_barang2) $fotoArray[] = $detail->barang->foto_barang2;
                        if($detail->barang->foto_barang3) $fotoArray[] = $detail->barang->foto_barang3;
                        if($detail->barang->foto_barang4) $fotoArray[] = $detail->barang->foto_barang4;
                    @endphp

                    <div class="col-span-2">
                        <p class="mb-2"><strong>Foto Barang:</strong></p>

                        @if(count($fotoArray) > 0)
                            <div class="flex space-x-4">
                                @foreach($fotoArray as $foto)
                                    <img src="{{ asset($foto) }}" alt="Foto Barang" class="w-40 h-40 object-cover rounded">
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-400 italic">Tidak ada foto</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
    function openModal(id) {
        document.getElementById('modal-' + id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById('modal-' + id).classList.add('hidden');
    }
</script>
</body>
</html>