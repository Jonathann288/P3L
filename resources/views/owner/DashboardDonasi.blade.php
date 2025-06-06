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
            <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('owner.DashboardOwner') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-user-circle"></i>
                        <span>{{ $pegawaiLogin->nama_pegawai }}</span>
                    </a>
                    <a href="{{ route('owner.DashboardDonasi') }}"
                        class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fa-solid fa-hand-holding-dollar"></i>
                        <span>Daftar Request Donasi</span>
                    </a>
                    <a href="{{ route('owner.historyDonasi') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>History Request Donasi</span>
                    </a>
                    <a href="{{ route('owner.DashboardLaporan') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span>Laporan</span>
                    </a>
                </div>
            </nav>
        </div>
        <!-- Bottom buttons -->
        <div class="space-y-4 mt-auto">
            <form action="{{ route('logout.pegawai') }}"  method="POST">
                @csrf
                <button class="w-full py-2 bg-red-600 text-white rounded-lg hover:bg-red-500">
                    Keluar
                </button>
            </form>
        </div>
    </div>

       <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Dashboard Donasi</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="min-w-full bg-white shadow-md rounded mb-6">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="py-2 px-4">No</th>
                    <th class="py-2 px-4">Nama Organisasi</th>
                    <th class="py-2 px-4">Deskripsi Request</th>
                    <th class="py-2 px-4">Tanggal Request</th>
                    <th class="py-2 px-4">Status Request</th>
                    <th class="py-2 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requestdonasi as $index => $d)
                <tr class="border-t">
                    <td class="py-3 px-6">{{ $index + 1 }}</td>
                    <td class="py-3 px-6">{{ $d->organisasi->nama_organisasi }}</td>
                    <td class="py-3 px-6">{{ $d->deskripsi_request }}</td>
                    <td class="py-3 px-6">{{ $d->tanggal_request }}</td>
                    <td class="py-3 px-6">
                        @if(strtolower($d->status_request) == 'approved')
                            <span class="status-badge status-approved">
                                <i class="fas fa-check-circle mr-1"></i> Approved
                            </span>
                        @elseif(strtolower($d->status_request) == 'pending')
                            <span class="status-badge status-pending">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                        @else
                            <span class="status-badge status-rejected">
                                <i class="fas fa-times-circle mr-1"></i> {{ $d->status_request }}
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-6">
                        @if(strtolower($d->status_request) == 'pending')
                            <div class="flex space-x-2">
                                <button onclick="openDonationModal('{{ $d->id_request }}')" 
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-hand-holding-heart mr-1"></i> Donasi
                                </button>

                                <button onclick="rejectRequest('{{ $d->id_request }}')" 
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    <i class="fas fa-times mr-1"></i> Tolak
                                </button>
                            </div>
                        @else
                            <span class="text-gray-500">Tidak ada aksi</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Modal Donasi -->
    <div id="donationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="bg-white w-1/3 rounded-lg shadow-lg p-6">
            <h2 class="text-2xl mb-4">Form Donasi</h2>
            <form id="donationForm" method="POST" action="{{ route('owner.donasi.submit') }}">
                @csrf
                <input type="hidden" id="id_request" name="id_request">

                <div class="mb-4">
                    <label for="nama_penerima" class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima:</label>
                    <input type="text" id="nama_penerima" name="nama_penerima" required class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="tanggal_donasi" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Donasi:</label>
                    <input type="date" id="tanggal_donasi" name="tanggal_donasi" required class="w-full px-4 py-2 border rounded-lg">
                </div>

                <div class="mb-4">
                    <label for="id_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang:</label>
                    <select id="id_barang" name="id_barang" required class="w-full px-4 py-2 border rounded-lg">
                        <option value="" disabled selected>Pilih Barang</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id_barang }}">{{ $barang->nama_barang }} - ({{ $barang->status_barang }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeDonationModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">
                        Simpan Donasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tolak Request -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
    <div class="bg-white w-1/4 rounded-lg shadow-lg p-6">
        <h2 class="text-xl mb-4">Tolak Request Donasi</h2>
        <form id="rejectForm" method="POST" action="{{ route('owner.donasi.reject') }}">
            @csrf
            @method('PUT')
            <input type="hidden" id="reject_id_request" name="id_request">

            <p>Apakah Anda yakin ingin menolak request donasi ini?</p>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">
                    Tolak Request
                </button>
            </div>
        </form>
    </div>
</div>
<!-- Modal Tolak Request -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
    <div class="bg-white w-1/4 rounded-lg shadow-lg p-6">
        <h2 class="text-xl mb-4">Tolak Request Donasi</h2>
        <form id="rejectForm" method="POST" action="{{ route('owner.donasi.reject') }}">
            @csrf
            @method('PUT')
            <input type="hidden" id="reject_id_request" name="id_request">

            <p>Apakah Anda yakin ingin menolak request donasi ini?</p>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-500 text-white rounded-lg">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">
                    Tolak Request
                </button>
            </div>
        </form>
    </div>
</div>


    <script>
        function openDonationModal(id_request) {
            document.getElementById('id_request').value = id_request;
            document.getElementById('donationModal').classList.remove('hidden');
        }

        function closeDonationModal() {
            document.getElementById('donationModal').classList.add('hidden');
        }

        function rejectRequest(id_request) {
            document.getElementById('reject_id_request').value = id_request;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>