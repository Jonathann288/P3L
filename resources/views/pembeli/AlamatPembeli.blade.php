<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pembeli</title>
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
                    <a href="{{ route('pembeli.profilPembeli') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <img src="{{ asset($pembeli->foto_pembeli) }}" alt="profile" class="w-8 h-8 rounded-full object-cover">
                        <span>{{ $pembeli->nama_pembeli }}</span>
                    </a>
                    <div class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-clock-rotate-left" ></i>
                        <span>History</span>
                    </div>
                    <a href="{{ route('pembeli.AlamatPembeli') }}" class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
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
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">Daftar Alamat</h2>
            <button onclick="toggleModal('add')" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Tambah Alamat</button>
        </div>

        <!-- Alamat List -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if ($alamat->isNotEmpty())
                @foreach ($alamat as $item)
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <div class="font-bold">{{ $item->nama_jalan }}</div>
                        <div>{{ $item->kode_pos }}</div>
                        
                        <!-- Tombol Edit dan Hapus -->
                        <div class="flex space-x-2 mt-4">
                            <button 
                                onclick="editAlamat('{{ $item->id }}', '{{ $item->nama_alamat }}', '{{ $item->detail_alamat }}')" 
                                class="bg-yellow-500 text-white px-4 py-1 rounded-lg"
                            >
                                Edit
                            </button>
                            <form action="{{ route('alamat.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-1 rounded-lg">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <p>Belum ada alamat yang ditambahkan.</p>
            @endif
        </div>


    </div>

    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 justify-center items-center">
        <div class="bg-white p-8 rounded-lg w-1/3">
            <h2 class="text-xl mb-4 font-semibold" id="modalTitle">Tambah Alamat</h2>
            <form id="modalForm" method="POST">
                @csrf
                <input type="hidden" id="alamatId">
                <div class="space-y-4">
                    <div>
                        <label class="block">Nama Jalan:</label>
                        <input type="text" name="nama_jalan" class="w-full p-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label class="block">Kode Pos:</label>
                        <input type="number" name="kode_pos" class="w-full p-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label class="block">Kecamatan:</label>
                        <input type="text" name="kecamatan" class="w-full p-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label class="block">Kelurahan:</label>
                        <input type="text" name="kelurahan" class="w-full p-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label class="block">Status Default:</label>
                        <input type="text" name="status_default" class="w-full p-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label class="block">Kabupaten:</label>
                        <input type="text" name="kabupaten" class="w-full p-2 border rounded-lg" required>
                    </div>
                    <div>
                        <label class="block">Deskripsi Alamat:</label>
                        <textarea name="deskripsi_alamat" class="w-full p-2 border rounded-lg" required></textarea>
                    </div>
                </div>
                <div class="flex justify-end mt-6 space-x-2">
                    <button type="button" onclick="toggleModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(mode = 'add') {
            const modal = document.getElementById('modal');
            const form = document.getElementById('modalForm');
            const title = document.getElementById('modalTitle');
            const alamatId = document.getElementById('alamatId');
            if (mode === 'add') {
                form.action = '{{ route('pembeli.storeAlamat') }}'; // Store route
                title.textContent = "Tambah Alamat";
                alamatId.value = '';
                // Reset form fields
                // document.getElementById('nama_jalan').value = '';
                // document.getElementById('kode_post').value = '';
                // document.getElementById('kecamtan').value = '';
                // document.getElementById('kelurahan').value = '';
                // document.getElementById('status_default').value = '';
                // document.getElementById('kabupaten').value = '';
                // document.getElementById('deskripsi_alamt').value = '';
            }
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function editAlamat(id, nama, detail) {
            toggleModal('edit');
            document.getElementById('modalForm').action = '/alamat/' + id; // Update route
            document.getElementById('modalForm').innerHTML += '@method("PUT")'; // Method PUT for update
            document.getElementById('nama_alamat').value = nama;
            document.getElementById('detail_alamat').value = detail;
            document.getElementById('alamatId').value = id;
        }
    </script>
</body>

</html>