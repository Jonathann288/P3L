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

<body class="font-sans bg-gray-100 text-gray-800 min-h-screen">

    <!-- Sidebar Navigation (Mobile) -->
    <div class="md:hidden bg-gray-800 text-white p-4 flex justify-between items-center">
        <h2 class="text-xl font-semibold">MyAccount</h2>
        <button id="mobile-menu-toggle" class="text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-gray-800 text-white p-4">
        <div class="space-y-4">
            <div class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                <img src="{{ asset($pembeli->foto_pembeli) }}" alt="profile" class="w-8 h-8 rounded-full object-cover">
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
            <button class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">
                <a href="{{ route('pembeli.Shop-Pembeli') }}">Kembali</a>
            </button>
        </div>
    </div>

    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar Navigation (Desktop) -->
        <div class="hidden md:block bg-gray-800 text-white p-6 w-64 flex-shrink-0">
            <div class="flex flex-col h-full">
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
                        </div>
                    </nav>
                </div>
                <div class="mt-auto">
                    <button class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">
                        <a href="{{ route('pembeli.Shop-Pembeli') }}">Kembali</a>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-4 md:p-8 bg-gray-100">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl md:text-3xl font-semibold text-gray-800">Profil Pembeli</h1>
            </div>

            <!-- Profile Header -->
            <div class="bg-white p-6 rounded-lg shadow-md text-center mb-6">
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-4 overflow-hidden">
                        @if($pembeli->foto_pembeli)
                            <img src="{{ asset($pembeli->foto_pembeli) }}" alt="Foto Profil"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-blue-100 flex items-center justify-center">
                                <span class="text-4xl font-bold text-blue-600">
                                    {{ substr($pembeli->nama_pembeli, 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $pembeli->nama_pembeli }}</h2>
                    <button onclick="toggleEditModal()"
                        class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-500 mt-4">
                        <i class="fas fa-pencil-alt"></i> Edit Profil
                    </button>
                </div>
            </div>

            <!-- Info Sections -->
            <div class="space-y-6">
                <!-- Personal Info -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Pribadi</h2>
                        <button onclick="toggleEditModal()" class="text-blue-600 hover:underline">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <div>
                                <span class="font-semibold text-gray-600 block">Nama Lengkap:</span>
                                <span class="block">{{ $pembeli->nama_pembeli }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600 block">Email:</span>
                                <span class="block">{{ $pembeli->email_pembeli }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <span class="font-semibold text-gray-600 block">Nomor Telepon:</span>
                                <span class="block">{{ $pembeli->nomor_telepon_pembeli }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600 block">Tanggal Lahir:</span>
                                <span
                                    class="block">{{ \Carbon\Carbon::parse($pembeli->tanggal_lahir)->format('d F Y') }}</span>
                            </div>
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
                    <div class="bg-blue-50 p-4 rounded-lg text-center">
                        <span class="font-semibold text-gray-600 block">Total Poin</span>
                        <span class="text-xl font-bold text-orange-500 block">{{ $pembeli->total_poin }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
        <div class="bg-white p-8 rounded-lg w-full md:w-1/2 lg:w-1/3">
            <h2 class="text-xl mb-4 font-semibold">Edit Profil</h2>
            <form action="{{ route('pembeli.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_pembeli" value="{{ $pembeli->id_pembeli }}">

                <div class="space-y-4">
                    <div>
                        <label for="foto_pembeli" class="block font-semibold">Foto Profil:</label>
                        <input type="file" id="foto_pembeli" name="foto_pembeli" class="w-full p-2 border rounded-lg">
                    </div>
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
                    <button type="button" onclick="toggleEditModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg">
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
        // Mobile menu toggle
        document.getElementById('mobile-menu-toggle').addEventListener('click', function () {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Edit modal toggle
        function toggleEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    </script>
</body>

</html>