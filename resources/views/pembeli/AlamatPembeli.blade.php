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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                    <a href="{{ route('pembeli.historyPembeli') }}" class="flex items-center space-x-4 p-3 rounded-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>History</span>
                    </a>
                    <a href="{{ route('pembeli.AlamatPembeli') }}"
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
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
                <!-- Search Bar -->
                <form action="{{ route('pembeli.alamat.search') }}" method="GET" class="flex items-center gap-2">
                    <input type="text" name="nama_jalan" placeholder="Cari Nama Jalan..."
                        class="w-full p-2 border rounded-lg" value="{{ request('nama_jalan') }}">

                    <select name="status_default" class="w-full p-2 border rounded-lg">
                        <option value="" disabled selected>Status Alamat</option>
                        <option value="Rumah {{ request('status_default') == 'Rumah' ? 'selected' : '' }}">Rumah
                        </option>
                        <option value="Toko {{ request('status_default') == 'Toko' ? 'selected' : '' }}">Toko</option>
                        <option value="Gudang {{ request('status_default') == 'Gudang' ? 'selected' : '' }}">Gudang
                        </option>
                        <option value="Apartemen {{ request('status_default') == 'Apartemen' ? 'selected' : '' }}">
                            Apartemen</option>
                    </select>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Cari
                    </button>
                </form>

                <!-- Button Tambah Alamat -->
                <button onclick="toggleModal('add')" class="bg-green-600 text-white px-4 py-2 rounded-lg">
                    Tambah Alamat
                </button>
            </div>


            <!-- Alamat List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if ($alamat->isNotEmpty())
                    @foreach ($alamat as $item)
                        <div class="bg-white p-4 rounded-lg shadow-md">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-bold text-lg">{{ $pembeli->nama_pembeli }}</div>
                                    <div class="text-sm text-gray-500">(+62) {{ $pembeli->nomor_telepon_pembeli }}</div>
                                </div>
                                <div class="flex space-x-2">
                                    <div class="text-blue-500 cursor-pointer hover:underline"
                                        onclick="editAlamat('{{ $item->id }}', '{{ $item->nama_jalan }}', '{{ $item->deskripsi_alamat }}')">
                                        Ubah</div>
                                    <form action="{{ route('pembeli.alamat.delete', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </div>
                            <div class="mt-2 text-gray-700">
                                {{ $item->nama_jalan }}, {{ $item->kelurahan }}, {{ $item->kecamatan }},
                                {{ $item->kabupaten }}, {{ $item->kode_pos }}
                            </div>
                            <div class="mt-2 text-sm text-gray-500">
                                {{ $item->status_default}}
                            </div>
                            <div class="flex space-x-2 mt-4">
                                <button id="btn-utama-{{ $item->id }}" onclick="aturSebagaiUtama('{{ $item->id }}')"
                                    class="border border-gray-300 px-2 py-1 rounded hover:bg-gray-100">
                                    Atur sebagai utama
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>Belum ada alamat yang ditambahkan.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 justify-center items-center">
        <div class="bg-white p-8 rounded-lg w-1/3">
            <h2 class="text-xl mb-4 font-semibold" id="modalTitle">Tambah Alamat</h2>
            <form id="modalForm" method="POST" action="">
                @csrf
                <input type="hidden" id="alamatId">
                <div class="space-y-4">
                    <div>
                        <label class="block">Nama Jalan:</label>
                        <input type="text" name="nama_jalan" id="nama_jalan" class="w-full p-2 border rounded-lg"
                            required>
                    </div>
                    <div>
                        <label class="block">Kode Pos:</label>
                        <input type="number" name="kode_pos" id="kode_pos" class="w-full p-2 border rounded-lg"
                            required>
                    </div>
                    <div>
                        <label class="block">Kecamatan:</label>
                        <input type="text" name="kecamatan" id="kecamatan" class="w-full p-2 border rounded-lg"
                            required>
                    </div>
                    <div>
                        <label class="block">Kelurahan:</label>
                        <input type="text" name="kelurahan" id="kelurahan" class="w-full p-2 border rounded-lg"
                            required>
                    </div>
                    <div>
                        <label class="block">Status Alamat:</label>
                        <select name="status_default" id="status_default" class="w-full p-2 border rounded-lg" required>
                            <option value="" disabled selected>Pilih alamat sebagai</option>
                            <option value="Rumah">Rumah</option>
                            <option value="Toko">Toko</option>
                            <option value="Gudang">Gudang</option>
                            <option value="Apartemen">Apartemen</option>
                        </select>
                    </div>
                    <div>
                        <label class="block">Kabupaten:</label>
                        <input type="text" name="kabupaten" id="kabupaten" class="w-full p-2 border rounded-lg"
                            required>
                    </div>
                    <div>
                        <label class="block">Deskripsi Alamat:</label>
                        <textarea name="deskripsi_alamat" id="deskripsi_alamat" class="w-full p-2 border rounded-lg"
                            required></textarea>
                    </div>
                </div>
                <div class="flex justify-end mt-6 space-x-2">
                    <button type="button" onclick="toggleModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg">Batal</button>
                    <button type="submit" onsubmit="konfirmasi('add', event)"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function konfirmasi(mode = 'add', e) {
            e.preventDefault(); // Prevent form submission

            let pesan = '';
            if (mode === 'add') {
                pesan = 'Apakah Anda yakin ingin menambahkan alamat baru?';
            } else if (mode === 'edit') {
                pesan = 'Apakah Anda yakin ingin mengubah alamat?';
            }

            Swal.fire({
                title: 'Konfirmasi',
                text: pesan,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit(); // Submit form
                }
            });
        }


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
            } else if (mode === 'edit') { // Store route
                title.textContent = "Edit Alamat";
            }
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function editAlamat(id, nama, deskripsi) {
            toggleModal('edit');
            const form = document.getElementById('modalForm');
            form.action = '/alamatPembeli/update/' + id; // Update route
            form.innerHTML += '@method("PUT")'; // Method PUT for update

            document.getElementById('alamatId').value = id;
            document.getElementById('nama_jalan').value = nama;
            document.getElementById('deskripsi_alamat').value = deskripsi;
            // Update other fields as necessary
        }
        let alamatUtamaSebelumnya = null;

        function aturSebagaiUtama(id) {
            // Konfirmasi tindakan
            if (confirm("Apakah Anda yakin ingin mengatur alamat ini sebagai utama?")) {

                // Jika sebelumnya ada alamat utama, tampilkan kembali tombolnya
                if (alamatUtamaSebelumnya !== null) {
                    const previousButton = document.getElementById('btn-utama-' + alamatUtamaSebelumnya);
                    if (previousButton) {
                        previousButton.style.display = 'inline-block';
                    }
                }

                // Hilangkan tombol pada alamat yang baru dipilih
                const currentButton = document.getElementById('btn-utama-' + id);
                if (currentButton) {
                    currentButton.style.display = 'none';
                }

                // Simpan ID alamat yang baru diatur sebagai utama
                alamatUtamaSebelumnya = id;

                // Optional: Kirim AJAX request ke server untuk update status
                /*
                fetch(`/alamatPembeli/setUtama/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Alamat berhasil diatur sebagai utama.');
                }
            }).catch(error => {
                console.error('Error:', error);
            });
                */
            }
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>