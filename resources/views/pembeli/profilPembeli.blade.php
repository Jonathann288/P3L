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
                    <div class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <img src="{{ asset($pembeli->foto_pembeli) }}" alt="profile"
                            class="w-8 h-8 rounded-full object-cover">
                        <span>{{ $pembeli->nama_pembeli }}</span>
                    </div>
                    <a href="{{ route('pembeli.historyPembeli') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>History</span>
                    </a>
                    <a href="{{ route('pembeli.AlamatPembeli') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-cog"></i>
                        <span>Alamat</span>
                    </a>
                    <a href="{{ route('pembeli.pembatalanTransaksiValid') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-ban"></i>
                        <span>Pembatalan Transaksi</span>
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
            <h1 class="text-3xl font-semibold text-gray-800">Profil Pembeli</h1>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Profile Card -->
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-6">
                    <img src="{{ asset($pembeli->foto_pembeli) }}" alt="Foto Profil"
                        class="w-full h-full object-cover rounded-full">
                </div>
                <div class="font-semibold text-xl mb-2">{{ $pembeli->nama_pembeli }}</div>
                <button onclick="toggleModal()"
                    class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-500 w-full mt-4">
                    <i class="fas fa-pencil-alt"></i> Edit Profil
                </button>
            </div>

            <!-- Info Sections -->
            <div class="col-span-2 space-y-6">
                <!-- Personal Info -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Pribadi</h2>
                        <button onclick="toggleModal()" class="text-blue-600 hover:underline">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Nama Lengkap:</span>
                            <span>{{ $pembeli->nama_pembeli }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Email:</span>
                            <span>{{ $pembeli->email_pembeli }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Nomor Telepon:</span>
                            <span>{{ $pembeli->nomor_telepon_pembeli}}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Tanggal Lahir:</span>
                            <span>{{ \Carbon\Carbon::parse($pembeli->tanggal_lahir)->format('d F Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Points Info -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Poin Saya</h2>
                        <button class="text-blue-600 hover:underline">
                            <i class="fas fa-gift"></i> Tukar Poin
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Total Poin:</span>
                            <span class="text-xl font-bold text-orange-500">{{ $pembeli->total_poin}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="bg-white p-8 rounded-lg w-1/3">
            <h2 class="text-xl mb-4 font-semibold">Ubah Foto Profil</h2>
            <form action="{{ route('pembeli.updateProfil', $pembeli->id_pembeli) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block">Foto Profil:</label>
                        <input type="file" name="foto_pembeli" class="w-full p-2 border rounded-lg" required>
                    </div>
                </div>
                <div class="flex justify-end mt-6 space-x-2">
                    <button type="button" onclick="toggleModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="bg-white p-8 rounded-lg w-1/3">
            <h2 class="text-xl mb-4 font-semibold">Edit Profil</h2>
            <form action="{{ route('pembeli.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_pembeli" value="{{ $pembeli->id_pembeli }}">

                <div class="space-y-4">
                    <div>
                        <label for="nama_pembeli" class="block font-semibold">Nama Lengkap:</label>
                        <input type="text" id="nama_pembeli" name="nama_pembeli" value="{{ $pembeli->nama_pembeli }}"
                            class="w-full p-2 border rounded-lg">
                    </div>

                    <div>
                        <label for="email_pembeli" class="block font-semibold">Email:</label>
                        <input type="email" id="email_pembeli" name="email_pembeli"
                            value="{{ $pembeli->email_pembeli }}" class="w-full p-2 border rounded-lg">
                    </div>

                    <div>
                        <label for="nomor_telepon_pembeli" class="block font-semibold">Nomor Telepon:</label>
                        <input type="text" id="nomor_telepon_pembeli" name="nomor_telepon_pembeli"
                            value="{{ $pembeli->nomor_telepon_pembeli }}" class="w-full p-2 border rounded-lg">
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="block font-semibold">Tanggal Lahir:</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ $pembeli->tanggal_lahir }}"
                            class="w-full p-2 border rounded-lg">
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-2">
                    <button type="button" onclick="toggleModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        function toggleModal() {
            const modal = document.getElementById('modal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

        function toggleModal() {
            const modal = document.getElementById('editModal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }

    </script>
</body>

</html>