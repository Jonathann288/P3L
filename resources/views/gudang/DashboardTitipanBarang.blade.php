<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner</title>
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
            <h2 class="text-xl font-semibold mb-8">Owner</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('gudang.DashboardGudang') }}"
                        class="flex items-center space-x-4 p-3 bg-gray-700 rounded-lg">
                        <i class="fas fa-user-circle mr-2"></i>
                        <span>Profile Saya</span>
                    </a>
                    <a href="{{ route('gudang.DashboardTitipanBarang') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-dolly mr-2"></i>
                        <span>Tambah Titip Barang</span>
                    </a>

                </div>
            </nav>
        </div>
        <!-- Bottom buttons -->
        <div class="space-y-4 mt-auto">
            <button class="w-full py-2 bg-red-600 text-white rounded-lg hover:bg-red-500">
                Keluar
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-8 bg-gray-100">

        <h1 class="text-3xl font-bold mb-6">Daftar Titipan Barang</h1>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($titipans as $titipan)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h2 class="text-xl font-semibold">{{ $titipan->penitip->nama_penitip }}</h2>
                        <p class="text-sm text-gray-600">Tanggal Titip: {{ $titipan->tanggal_penitipan->format('d M Y') }}
                        </p>
                        <button @click="openModal({{ $titipan->id_transaksi_penitipan }})"
                            class="bg-yellow-500 text-white px-3 py-1 rounded">
                            Edit
                        </button>

                        <div x-data="{ open: false }" class="mt-4">
                            <button @click="open = !open" class="text-blue-600 hover:underline">Lihat Detail</button>
                            <div x-show="open" x-transition class="mt-4 text-sm space-y-2">
                                <div>
                                    <strong>Email Penitip:</strong> {{ $titipan->penitip->email_penitip }}
                                </div>
                                <div>
                                    <strong>Tanggal Batas Pengambilan:</strong>
                                    {{ $titipan->tanggal_batas_pengambilan->format('d M Y') }}
                                </div>
                                <div>
                                    <strong>Tanggal Pengambilan Barang:</strong>
                                    {{ $titipan->tanggal_pengambilan_barang ? $titipan->tanggal_pengambilan_barang->format('d M Y') : '-' }}
                                </div>
                                <div class="flex space-x-2 mt-2">
                                    @if(is_array($titipan->foto_barang) && count($titipan->foto_barang) > 0)
                                        @foreach($titipan->foto_barang as $foto)
                                            <img src="{{ asset('storage/' . $foto) }}" alt="Foto Barang"
                                                class="w-24 h-24 object-cover rounded">
                                        @endforeach
                                    @else
                                        <p class="text-gray-500 italic">Tidak ada foto tersedia.</p>
                                    @endif
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- Modal -->
    <div x-data="{ showModal: false, currentId: null }"
        x-init="$watch('showModal', value => { if (!value) document.getElementById('edit-form').reset(); })">
        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
            x-transition>
            <div class="bg-white rounded-lg p-6 w-full max-w-lg" @click.away="showModal = false">
                <h2 class="text-xl font-bold mb-4">Edit Titipan Barang</h2>
                <form id="edit-form" method="POST" action="{{ route('gudang.UpdateTitipanBarang', 0) }}"
                    enctype="multipart/form-data" x-ref="form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" :value="currentId">

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">ID Pegawai</label>
                        <input type="number" name="id_pegawai" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">ID Penitip</label>
                        <input type="number" name="id_penitip" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Tanggal Penitipan</label>
                        <input type="date" name="tanggal_penitipan" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Tanggal Akhir Penitipan</label>
                        <input type="date" name="tanggal_akhir_penitipan" class="w-full border rounded p-2">
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Tanggal Batas Pengambilan</label>
                        <input type="date" name="tanggal_batas_pengambilan" class="w-full border rounded p-2">
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Tanggal Pengambilan Barang</label>
                        <input type="date" name="tanggal_pengambilan_barang" class="w-full border rounded p-2">
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold mb-1">Upload Foto Barang (bisa lebih dari 1)</label>
                        <input type="file" name="foto_barang[]" multiple class="w-full border rounded p-2">
                    </div>

                    <div class="flex justify-end">
                        <button type="button" @click="showModal = false"
                            class="px-4 py-2 bg-gray-300 rounded mr-2">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function openModal(id) {
            const modal = document.querySelector('[x-data]');
            modal.__x.$data.showModal = true;
            modal.__x.$data.currentId = id;

            // Ganti action form dengan ID yang sesuai
            modal.querySelector('form').action = `/UpdateTitipanBarang/${id}`;
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>