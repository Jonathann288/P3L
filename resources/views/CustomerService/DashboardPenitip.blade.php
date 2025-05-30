<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penitip</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.tailwindcss.com"></script> 
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">
    
    <!-- Sidebar Navigation -->
    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('CustomerService.DashboardCS') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="text-2xl font-bold"></i> {{$pegawaiLogin->nama_pegawai}}
                    </a>

                    <a href="{{ route('CustomerService.DashboardPenitip') }}"
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fas fa-user mr-2"></i> <span>Penitip</span> 
                    </a>
                </div>
            </nav>
        </div>
    </div>    

    <div class="bg-gray-100 p-10">
        <div class="container mx-auto">
            <div class="flex justify-between items-center mb-5">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold">Data Penitip</h1>
                    <form action="{{ route('CustomerService.penitip.search') }}" method="GET" class="flex items-center space-x-2">
                        <input
                            type="text"
                            name="keyword"
                            placeholder="Cari Penitip..."
                            class="p-2 border rounded-lg"
                            value="{{ request('keyword') }}"
                        />
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                            Cari
                        </button>
                    </form>
                </div>
                
                <button onclick="openModalTambah()" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                    Tambah Penitip
                </button>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 bg-gray-200">No</th>
                            <th class="py-3 px-6 bg-gray-200">Nama Penitip</th>
                            <th class="py-3 px-6 bg-gray-200">Email</th>
                            <th class="py-3 px-6 bg-gray-200">Nomor Telepon</th>
                            <th class="py-3 px-6 bg-gray-200">Tanggal Lahir</th>
                            <th class="py-3 px-6 bg-gray-200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penitip as $index => $p)
                            <tr class="border-b">
                                <td class="py-3 px-6">{{ $index + 1 }}</td>
                                <td class="py-3 px-6">{{ $p->nama_penitip }}</td>
                                <td class="py-3 px-6">{{ $p->email_penitip }}</td>
                                <td class="py-3 px-6">{{ $p->nomor_telepon_penitip }}</td>
                                <td class="py-3 px-6">{{ $p->tanggal_lahir }}</td>
                                <td class="py-3 px-6 flex space-x-2">
                                    <button onclick="openModalEdit({{ json_encode($p) }})"
                                        class="bg-yellow-500 text-white px-4 py-2 rounded-lg">Edit</button>
                                    <button onclick="openDeleteModal('{{ $p->id_penitip }}')"
                                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Tambah Penitip -->
        <div id="modalTambah" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
            <div class="bg-white p-6 rounded-lg w-1/3">
                <h2 class="text-xl font-bold mb-4">Tambah Penitip</h2>
                <form action="{{ route('CustomerService.penitip.register') }}" method="POST">
                    @csrf
                    <input type="text" name="nama_penitip" placeholder="Nama Penitip" class="w-full p-2 mb-4 border">
                    <input type="text" name="nomor_ktp" placeholder="Nomor KTP" class="w-full p-2 mb-4 border">
                    <input type="date" name="tanggal_lahir" class="w-full p-2 mb-4 border">
                    <input type="text" name="nomor_telepon_penitip" placeholder="Nomor Telepon" class="w-full p-2 mb-4 border">
                    <input type="email" name="email_penitip" placeholder="Email Penitip" class="w-full p-2 mb-4 border">
                    <input type="password" name="password_penitip" placeholder="Password Penitip" class="w-full p-2 mb-4 border">
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModalTambah()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                        <button type="submit" onclick="return confirmCreate()" class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Penitip -->
        <div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
            <div class="bg-white p-6 rounded-lg w-1/3">
                <h2 class="text-xl font-bold mb-4">Edit Penitip</h2>
                <form id="formEdit" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="text" id="editNama" name="nama_penitip" class="w-full p-2 mb-4 border">
                    <input type="date" id="editTanggalLahir" name="tanggal_lahir" class="w-full p-2 mb-4 border">
                    <input type="text" id="editTelepon" name="nomor_telepon_penitip" class="w-full p-2 mb-4 border">
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeModalEdit()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                        <button type="submit" onclick="return confirmUpdate()" class="bg-green-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
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
                text: "Data penitip akan disimpan ke database.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form yang ada di dalam modal
                    document.querySelector('#modalTambah form').submit();
                }
            });

            return false;
        }

        function confirmUpdate() {
            event.preventDefault(); // Mencegah form submit langsung
            
            Swal.fire({
                title: 'Yakin ingin menyimpan perubahan?',
                text: "Data penitip akan di-update di database.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form yang ada di dalam modal edit
                    document.querySelector('#modalEdit form').submit();
                }
            });

            return false;
        }

        @if (session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif

        @if (session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
        function openModalTambah() {
            document.getElementById('modalTambah').classList.remove('hidden');
        }

        function closeModalTambah() {
            document.getElementById('modalTambah').classList.add('hidden');
        }

        function openModalEdit(penitip) {
            document.getElementById('formEdit').action = `/DashboardPenitip/update/${penitip.id_penitip}`;
            document.getElementById('editNama').value = penitip.nama_penitip;
            document.getElementById('editTanggalLahir').value = penitip.tanggal_lahir;
            document.getElementById('editTelepon').value = penitip.nomor_telepon_penitip;
            document.getElementById('modalEdit').classList.remove('hidden');
        }

        function closeModalEdit() {
            document.getElementById('modalEdit').classList.add('hidden');
        }

        function openDeleteModal(id) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `/DashboardPenitip/delete/${id}`;
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