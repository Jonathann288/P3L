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
                    <a href="{{ route('owner.DashboardOwner') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-user-circle mr-2"></i>
                        <span>{{ $pegawaiLogin->nama_pegawai }}</span>
                    </a>
                    <a href="{{ route('owner.DashboardDonasi') }}"
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                        <span>Daftar Request Donasi</span>
                    </a>
                    <a href="{{ route('owner.historyDonasi') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Histroy Request Donasi</span>
                    </a>
                    <a href="{{ route('owner.DashboardLaporanDonasiBarang') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-newspaper"></i>
                        <span>Laporan Donasi Barang</span>
                    </a>
                    <a href="{{ route('owner.DashboardLaporanRequestDonasi') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-book"></i>
                        <span>Laporan RequestDonasi</span>
                    </a>
                    <a href="{{ route('owner.DashboardLaporanTransaksiPenitip') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-book"></i>
                        <span>Laporan Transaksi Penitip</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-8 bg-gray-100">
        <h1 class="text-3xl font-bold mb-5">History Donasi</h1>

        <!-- Approved Donasi -->
        <h2 class="text-2xl font-semibold mb-3">Approved Donasi</h2>
        <table class="min-w-full bg-white border border-gray-200 mb-8">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">No</th>
                    <th class="py-3 px-6 text-left">Nama Organisasi</th>
                    <th class="py-3 px-6 text-left">Deskripsi Request</th>
                    <th class="py-3 px-6 text-left">Tanggal Donasi</th>
                    <th class="py-3 px-6 text-left">Nama Barang</th>
                    <th class="py-3 px-6 text-left">Nama Penerima</th>
                    <th class="py-3 px-6 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @foreach ($donasiApproved as $index => $donasi)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">{{ $index + 1 }}</td>
                        <td class="py-3 px-6">{{ $donasi['nama_organisasi'] }}</td>
                        <td class="py-3 px-6">{{ $donasi['deskripsi_request'] }}</td>
                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($donasi['tanggal_donasi'])->format('d-m-Y') }}</td>
                        <td class="py-3 px-6">{{ $donasi['nama_barang'] }}</td>
                        <td class="py-3 px-6">{{ $donasi['nama_penerima'] }}</td>
                        <td class="py-3 px-6">
                            <button
                                onclick="openModal('{{ $donasi['nama_penerima'] }}', '{{ $donasi['tanggal_donasi'] }}', '{{ $donasi['id_request'] }}')"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Rejected Donasi -->
        <h2 class="text-2xl font-semibold mb-3">Rejected Donasi</h2>
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">No</th>
                    <th class="py-3 px-6 text-left">Nama Organisasi</th>
                    <th class="py-3 px-6 text-left">Deskripsi Request</th>
                    <th class="py-3 px-6 text-left">Tanggal Donasi</th>
                    <th class="py-3 px-6 text-left">Nama Barang</th>
                    <th class="py-3 px-6 text-left">Nama Penerima</th>
                    <th class="py-3 px-6 text-left">Status Request</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @foreach ($donasiRejected as $index => $donasi)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">{{ $index + 1 }}</td>
                        <td class="py-3 px-6">{{ $donasi['nama_organisasi'] }}</td>
                        <td class="py-3 px-6">{{ $donasi['deskripsi_request'] }}</td>
                        <td class="py-3 px-6">-</td>
                        <td class="py-3 px-6">-</td>
                        <td class="py-3 px-6">-</td>
                        <td class="py-3 px-6">
                            <span class="text-red-600 font-bold">{{ $donasi['status_request'] }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-1/3">
            <h2 class="text-2xl font-semibold mb-4">Edit Donasi</h2>
            <form id="editForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="nama_penerima" class="block text-gray-700">Nama Penerima:</label>
                    <input type="text" id="nama_penerima" name="nama_penerima"
                        class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <label for="tanggal_donasi" class="block text-gray-700">Tanggal Donasi:</label>
                    <input type="date" id="tanggal_donasi" name="tanggal_donasi"
                        class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">Batal</button>
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div id="toast" class="fixed bottom-4 right-4 hidden p-4 rounded-lg shadow-lg text-white"></div>
    <script>
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
        @if (session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif

        @if (session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
            function openModal(namaPenerima, tanggalDonasi, id_request) {
                document.getElementById('nama_penerima').value = namaPenerima;
                document.getElementById('tanggal_donasi').value = tanggalDonasi;
                document.getElementById('editForm').action = `/owner/historyDonasi/update/${id_request}`;
                document.getElementById('editModal').classList.remove('hidden');
            }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>