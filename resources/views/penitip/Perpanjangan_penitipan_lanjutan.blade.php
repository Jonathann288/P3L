<!-- COPY SEMUA -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profil Pembeli - Daftar Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
</head>

<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">

    <!-- Sidebar Navigation -->
    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('penitip.profilPenitip') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg cursor-pointer">
                        <span>{{ $penitip->nama_penitip }}</span>
                    </a>
                    <a href="{{ route('penitip.barang-titipan') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg cursor-pointer">
                        <i class="fa-solid fa-box"></i>
                        <span>Titipan</span>
                    </a>
                    <a href="{{ route('penitip.Perpanjangan_penitipan_lanjutan') }}"
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fa-solid fa-box"></i>
                        <span>Barang Titipan Lanjut</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-8 bg-gray-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <h1 class="text-2xl font-bold">Daftar Barang</h1>
            
            <form method="GET" action="{{ route('penitip.barang.search') }}" class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                <input
                    type="text"
                    name="search"
                    placeholder="Cari nama barang atau tanggal penitipan"
                    value="{{ request('search') }}"
                    class="w-full sm:w-[400px] px-4 py-2 border border-gray-300 rounded-md"
                />
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Cari
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="py-3 px-6 text-left">No</th>
                        <th class="py-3 px-6 text-left">ID Barang</th>
                        <th class="py-3 px-6 text-left">Nama Barang</th>
                        <th class="py-3 px-6 text-left">Tanggal Titipan</th>
                        <th class="py-3 px-6 text-left">Tanggal Akhir Titipan</th>
                        <th class="py-3 px-6 text-left">Status Barang</th>
                        <th class="py-3 px-6 text-left">Status Perpanjangan</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailBarang as $index => $item)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-4 px-6">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">{{ $item->barang->id }}</td>
                            <td class="py-4 px-6">{{ $item->barang->nama_barang }}</td>
                            <td class="py-4 px-6">
                                {{ \Carbon\Carbon::parse($item->transaksipenitipan->tanggal_penitipan)->format('d M Y') }}
                            </td>
                            <td class="py-4 px-6">
                                {{ \Carbon\Carbon::parse($item->transaksipenitipan->tanggal_akhir_penitipan)->format('d M Y') }}
                            </td>
                            <td class="py-4 px-6">{{ $item->barang->status_barang }}</td>
                            <td class="py-4 px-6">{{ $item->status_perpanjangan }}</td>
                            <td class="py-4 px-6 text-center">
                                    @php
                                        $tanggalAkhir = \Carbon\Carbon::parse($item->transaksipenitipan->tanggal_akhir_penitipan);
                                        $tanggalSekarang = \Carbon\Carbon::now();
                                        $masaAktif = $tanggalAkhir->greaterThanOrEqualTo($tanggalSekarang);
                                    @endphp
                                    <form method="POST" 
                                        action="{{ route('penitip.barang.perpanjang.lanjutan', $item->id_detail_transaksi_penitipan) }}" 
                                        class="form-perpanjangan"
                                        data-nama="{{ $item->barang->nama_barang }}"
                                        data-harga="{{ $item->barang->harga_barang }}"
                                        data-biaya="{{ $item->barang->harga_barang * 0.05 }}">
                                        @csrf
                                        <button type="submit" 
                                            class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700
                                            @if($masaAktif) opacity-50 cursor-not-allowed @endif"
                                            @if($masaAktif) disabled @endif>
                                            Perpanjang 30 Hari
                                        </button>
                                    </form>

                            </td>
                        </tr>
                    @endforeach

                    @if ($detailBarang->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">Tidak ada data barang.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Modal Detail Barang -->
        <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative">
                <button class="absolute top-3 right-3 text-gray-600 hover:text-gray-900 text-xl font-bold" onclick="closeDetail()">
                    &times;
                </button>

                <!-- Struktur kolom menjadi vertikal -->
                <div class="flex flex-col items-center gap-4">
                    <!-- Container foto -->
                    <div id="modalFotoContainer" class="flex gap-2 overflow-x-auto max-w-full justify-center"></div>

                    <!-- Info nama, harga, status di bawah foto -->
                    <div class="text-center">
                        <h2 id="modalNama" class="text-xl font-semibold mb-2"></h2>
                        <p><strong>Harga:</strong> Rp <span id="modalHarga"></span></p>
                        <p><strong>Status:</strong> <span id="modalStatus" class="inline-block px-2 py-1 rounded text-white text-sm"></span></p>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="font-semibold">Deskripsi:</p>
                    <p id="modalDeskripsi" class="whitespace-pre-line mt-1 text-gray-700"></p>
                </div>
            </div>
        </div>

    </div>

    <div
        id="toast"
        class="fixed bottom-5 right-5 bg-green-600 text-white px-5 py-3 rounded shadow-lg opacity-0 pointer-events-none transition-opacity duration-500"
    >
        <span id="toast-message"></span>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

    <script>
        const barangData = {};
        @foreach ($detailBarang as $item)
            barangData["{{ $item->barang->id_barang }}"] = {
                nama_barang: "{{ addslashes($item->barang->nama_barang) }}",
                // kategori: "{{ addslashes($item->barang->kategori) }}",
                harga: "{{ number_format($item->barang->harga_barang, 0, ',', '.') }}",
                deskripsi: "{{ addslashes($item->barang->deskripsi_barang) }}",
                status: "{{ $item->barang->status_barang }}",
                foto: {!! json_encode(array_map(fn($f) => asset($f), $item->barang->foto_barang)) !!}

            };
        @endforeach
        function showDetail(id) {
    const barang = barangData[id];
    if (barang) {
        const fotoContainer = document.getElementById('modalFotoContainer');
        fotoContainer.innerHTML = ''; // reset dulu

        // Misal barang.foto adalah array foto, loop dan buat img element
        barang.foto.forEach(src => {
            const img = document.createElement('img');
            img.src = src;
            img.alt = barang.nama_barang;
            img.className = 'w-40 h-40 object-cover rounded-md shadow';
            fotoContainer.appendChild(img);
        });

        document.getElementById('modalNama').textContent = barang.nama_barang;
        document.getElementById('modalHarga').textContent = barang.harga;
        document.getElementById('modalDeskripsi').textContent = barang.deskripsi;

        const statusElem = document.getElementById('modalStatus');
        statusElem.textContent = barang.status;
        statusElem.className = 'inline-block px-2 py-1 rounded text-white text-sm';

        if (barang.status === 'Laku' || barang.status === 'di donasikan') {
            statusElem.classList.add('bg-green-500');
        } else if (barang.status === 'Diproses') {
            statusElem.classList.add('bg-yellow-500');
        } else if (barang.status === 'tidak laku') {
            statusElem.classList.add('bg-blue-500');
        } else {
            statusElem.classList.add('bg-red-500');
        }

        document.getElementById('detailModal').classList.remove('hidden');
    }
}


        function closeDetail() {
                document.getElementById('detailModal').classList.add('hidden');
            }
            document.addEventListener('DOMContentLoaded', () => {
            const toast = document.getElementById('toast');
            const message = document.getElementById('toast-message');

            @if(session('success'))
                message.textContent = "{{ session('success') }}";
                toast.classList.remove('opacity-0', 'pointer-events-none');
                toast.classList.add('opacity-100');

                // Sembunyikan toast setelah 3 detik
                setTimeout(() => {
                    toast.classList.add('opacity-0');
                    toast.classList.add('pointer-events-none');
                    toast.classList.remove('opacity-100');
                }, 3000);
            @endif

            @if(session('error'))
                message.textContent = "{{ session('error') }}";
                toast.classList.remove('opacity-0', 'pointer-events-none');
                toast.classList.add('opacity-100');
                toast.style.backgroundColor = '#DC2626'; // merah untuk error

                // Sembunyikan toast setelah 3 detik
                setTimeout(() => {
                    toast.classList.add('opacity-0');
                    toast.classList.add('pointer-events-none');
                    toast.classList.remove('opacity-100');
                    toast.style.backgroundColor = '#16A34A'; // reset ke hijau
                }, 3000);
            @endif
        });

            document.querySelectorAll('.form-perpanjangan').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // cegah langsung submit

            const namaBarang = this.dataset.nama;
            const harga = parseInt(this.dataset.harga);
            const biaya = parseFloat(this.dataset.biaya);

            const hargaFormatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(harga);
            const biayaFormatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(biaya);

            const konfirmasi = confirm(`Apakah Anda yakin ingin memperpanjang barang "${namaBarang}" dengan harga ${hargaFormatted}?\nSaldo Anda akan dipotong sebesar ${biayaFormatted}.`);

            if (konfirmasi) {
                this.submit(); // jika user setuju, lanjut submit
            }
        });
    });
    </script>
</body>

</html>