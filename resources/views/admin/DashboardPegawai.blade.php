<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.2/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        #toast {
            opacity: 0;
            transition: opacity 0.5s;
        }

        #toast.show {
            opacity: 1;
        }
    </style>
</head>

<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">

    <!-- Sidebar Navigation -->
    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('admin.Dashboard') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="text-2xl font-bold"></i> {{$pegawaiLogin->nama_pegawai}}
                    </a>

                    <a href="{{ route('admin.DashboardPegawai') }}"
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fas fa-users mr-2"></i> Pegawai
                    </a>
                    <div class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-handshake"></i>
                        <span>Jabatan</span>
                    </div>
                    <a href="{{ route('admin.DashboardOrganisasi') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-sitemap"></i>
                        <span>Organisasi</span>
                    </a>
                    <div class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-gift"></i>
                        <span>Merchandise</span>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-8 bg-gray-100">
        <div class="mb-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <h1 class="text-3xl font-semibold text-gray-800">Daftar Pegawai</h1>
                <form action="{{ route('admin.pegawai.search') }}" method="GET" class="flex items-center gap-2">
                    <input type="text" name="keyword" placeholder="Cari Pegawai (Nama, Jabatan, Email)..."
                        class="p-2 border rounded-lg w-80" value="{{ request('keyword') }}" />
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Cari
                    </button>
                </form>
            </div>
            <button onclick="openModal()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                Tambah Pegawai
            </button>
        </div>


        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-3 px-6 bg-gray-200 font-bold uppercase text-gray-600 text-sm text-left">no</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold uppercase text-gray-600 text-sm text-left">Nama</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold uppercase text-gray-600 text-sm text-left">Jabatan
                        </th>
                        <th class="py-3 px-6 bg-gray-200 font-bold uppercase text-gray-600 text-sm text-left">Email</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold uppercase text-gray-600 text-sm text-left">No.
                            Telepon</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold uppercase text-gray-600 text-sm text-left">Tanggal
                            Lahir</th>
                        <th class="py-3 px-6 bg-gray-200 font-bold uppercase text-gray-600 text-sm text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pegawai as $index => $p)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left">{{ (int) $index + 1 }}</td>
                            <td class="py-3 px-6 text-left">{{ $p->nama_pegawai }}</td>
                            <td class="py-3 px-6 text-left">{{ $p->jabatan->nama_jabatan }}</td>
                            <td class="py-3 px-6 text-left">{{ $p->email_pegawai }}</td>
                            <td class="py-3 px-6 text-left">{{ $p->nomor_telepon_pegawai }}</td>
                            <td class="py-3 px-6 text-left">{{ $p->tanggal_lahir_pegawai }}</td>
                            <td class="py-3 px-6 text-left flex space-x-2">
                                <button onclick="openEditModal({{ json_encode($p) }})"
                                    class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
                                    Ubah
                                </button>

                                <button onclick="openDeleteModal({{ $p->id_pegawai }})"
                                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                                    Hapus
                                </button>

                                <button onclick="confirmResetPassword({{ json_encode($p) }})"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                    Reset Password
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Pegawai -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-8 rounded-lg w-[500px]">
            <h2 class="text-2xl font-bold mb-6">Tambah Pegawai Baru</h2>
            <form action="{{ route('admin.pegawai.register') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block mb-2">Jabatan</label>
                    <select id="editJabatan" name="id_jabatan" class="w-full p-2 border rounded">
                        @foreach ($jabatan->where('nama_jabatan', '!=', 'Owner') as $j)
                            <option value="{{ $j->id_jabatan }}">{{ $j->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block mb-2">Nama Pegawai</label>
                    <input type="text" name="nama_pegawai" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-2">No. Telepon</label>
                    <input type="text" name="nomor_telepon_pegawai" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <input type="date" name="tanggal_lahir_pegawai" class="w-full p-2 rounded-lg"
                        placeholder="Tanggal Lahir Pegawai" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-2">Email Pegawai</label>
                    <input type="email" name="email_pegawai" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-2">Password</label>
                    <input type="password" name="password_pegawai" class="w-full p-2 border rounded" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" onclick="return confirmCreate()"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!--Edit MOdal-->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-8 rounded-lg w-[500px]">
            <h2 class="text-2xl font-bold mb-6">Ubah Data Pegawai</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block mb-2">Jabatan</label>
                    <select name="id_jabatan" class="w-full p-2 border rounded">
                        @foreach ($jabatan->where('nama_jabatan', '!=', 'Owner') as $j)
                            <option value="{{ $j->id_jabatan }}">{{ $j->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block mb-2">Nama Pegawai</label>
                    <input type="text" id="editNama" name="nama_pegawai" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-2">No. Telepon</label>
                    <input type="text" id="editTelepon" name="nomor_telepon_pegawai" class="w-full p-2 border rounded"
                        required>
                </div>
                <div class="mb-4">
                    <label class="block mb-2">Tanggal Lahir</label>
                    <input type="date" id="editTanggalLahir" name="tanggal_lahir_pegawai"
                        class="w-full p-2 border rounded" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" onclick="return confirmUpdate()"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-8 rounded-lg w-[400px]">
            <h2 class="text-2xl font-bold mb-6 text-center">Konfirmasi Hapus</h2>
            <p class="text-center mb-6">Apakah Anda yakin ingin menghapus pegawai ini?</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center space-x-4">
                    <button type="button" onclick="closeDeleteModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 hidden p-4 rounded-lg shadow-lg text-white"></div>

    <!-- Script Modal -->
    <script>
        function confirmCreate() {
            event.preventDefault(); // Mencegah form submit langsung

            Swal.fire({
                title: 'Yakin ingin menyimpan data?',
                text: "Data pegawai akan disimpan ke database.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form yang ada di dalam modal
                    document.querySelector('#modal form').submit();
                }
            });

            return false;
        }

        function confirmUpdate() {
            event.preventDefault(); // Mencegah form submit langsung

            Swal.fire({
                title: 'Yakin ingin menyimpan perubahan?',
                text: "Data pegawai akan di-update di database.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form yang ada di dalam modal edit
                    document.querySelector('#editModal form').submit();
                }
            });

            return false;
        }

        function confirmResetPassword(pegawai) {
            Swal.fire({
                title: 'Reset Password Pegawai?',
                html: `Password untuk <b>${pegawai.nama_pegawai}</b> akan direset menjadi tanggal lahir mereka (<b>${pegawai.tanggal_lahir_pegawai}</b>)`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Reset Password',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request reset password
                    fetch(`/DashboardPegawai/reset-password/${pegawai.id_pegawai}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            _method: 'PUT'
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast('Password berhasil direset ke tanggal lahir', 'success');
                            } else {
                                showToast('Gagal mereset password', 'error');
                            }
                        })
                        .catch(error => {
                            showToast('Terjadi kesalahan', 'error');
                            console.error('Error:', error);
                        });
                }
            });
        }

        @if (session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif

        @if (session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
            function openModal() {
                document.getElementById('modal').classList.remove('hidden');
            }
        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
        function openEditModal(pegawai) {
            document.getElementById('editJabatan').value = pegawai.id_jabatan;
            document.getElementById('editNama').value = pegawai.nama_pegawai;
            document.getElementById('editTelepon').value = pegawai.nomor_telepon_pegawai;
            document.getElementById('editTanggalLahir').value = pegawai.tanggal_lahir_pegawai;
            document.getElementById('editForm').action = `/DashboardPegawai/update/${pegawai.id_pegawai}`;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        function openDeleteModal(id) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/DashboardPegawai/delete/${id}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;

            // Set warna berdasarkan tipe
            if (type === 'success') {
                toast.classList.add('bg-green-500');
                toast.classList.remove('bg-red-500');
            } else if (type === 'error') {
                toast.classList.add('bg-red-500');
                toast.classList.remove('bg-green-500');
            }

            // Tampilkan toast dengan animasi
            toast.classList.remove('hidden');
            toast.classList.add('show');

            // Hilangkan toast setelah 3 detik
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 500);
            }, 3000);
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>