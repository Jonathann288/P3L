<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pembeli</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
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
                    <a href="{{ route('pembeli.profilPembeli') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <img src="{{ asset($pembeli->foto_pembeli) }}" alt="profile"
                            class="w-8 h-8 rounded-full object-cover">
                        <span>{{ $pembeli->nama_pembeli }}</span>
                    </a>
                    <div class="flex items-center space-x-4 p-3 hover:bg-gray-700 bg-blue-600  rounded-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>History</span>
                    </div>
                    <a href="{{ route('pembeli.AlamatPembeli') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-cog"></i>
                        <span>Alamat</span>
                    </a>
                </div>
            </nav>
        </div>
        <!-- Bottom buttons -->
        <div class="space-y-4 mt-auto">
            <button class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">
                <a href="{{ route('pembeli.Shop-Pembeli') }}">Kembali</a>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-8 bg-gray-100">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-gray-800">History Pembeli</h1>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if ($transaksiPenjualan->isNotEmpty())
                @foreach ($transaksiPenjualan as $item)
                    <button onclick="openModalTambah()" class="bg-white p-4 rounded-lg shadow-md">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-bold text-lg">{{ $pembeli->nama_pembeli }}</div>
                                <div class="text-sm text-gray-500">(+62) {{ $pembeli->nomor_telepon_pembeli }}</div>
                            </div>
                        </div>
                        <div class="mt-2 text-gray-700">
                            {{ $item->id }}, {{ $item->tanggal_transaksi }}, {{ $item->metode_pengantaran }},
                            {{ $item->status_pembayaran }}, {{ $item->tanggal_kirim }}
                        </div>
                    </button>
                @endforeach
            @else
                <p>Belum ada history pembelian yang ditambahkan.</p>
            @endif
        </div>
    </div>
    </div>

    <!-- Modal Tambah Penitip -->
    <div id="modalTambah" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h2 class="text-xl font-bold mb-4">detail Pembelian</h2>
            <div class="space-y-4">
                @foreach ($transaksiPenjualan as $item)
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-600">tanggal_transaksi:</span>
                        <span>{{ $item->tanggal_transaksi }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-600">metode_pengantaran:</span>
                        <span>{{ $item->metode_pengantaran }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-600">status_pembayaran:</span>
                        <span>{{ $item->status_pembayaran }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        function openModalTambah() {
            document.getElementById('modalTambah').classList.remove('hidden');
        }

        function closeModalTambah() {
            document.getElementById('modalTambah').classList.add('hidden');
        }
    </script>
</body>

</html>