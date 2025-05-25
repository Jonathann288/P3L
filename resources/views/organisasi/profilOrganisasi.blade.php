<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Organisasi</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            <a href="{{ route('organisasi.profilOrganisasi') }}"
                class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                <i class="fas fa-user"></i>
                <span>Profil</span>
            </a>
            <a href="{{ route('organisasi.requestDonasiOrganisasi') }}"
                class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Request Donasi</span>
            </a>
            <button class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">
                <a href="{{ route('organisasi.donasi-organisasi') }}">Kembali</a>
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
                            <a href="{{ route('organisasi.profilOrganisasi') }}"
                                class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                                <i class="fas fa-user"></i>
                                <span>Profil</span>
                            </a>
                            <a href="{{ route('organisasi.requestDonasiOrganisasi') }}"
                                class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <span>Request Donasi</span>
                            </a>
                        </div>
                    </nav>
                </div>
                <div class="mt-auto">
                    <button class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">
                        <a href="{{ route('organisasi.donasi-organisasi') }}">Kembali</a>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-4 md:p-8 bg-gray-100">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl md:text-3xl font-semibold text-gray-800">Profil Organisasi</h1>
            </div>

            <!-- Profile Header -->
            <div class="bg-white p-6 rounded-lg shadow-md text-center mb-6">
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-4 overflow-hidden">
                        @if($organisasi->foto_organisasi)
                            <img src="{{ asset($organisasi->foto_organisasi) }}" alt="Foto Profil"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-blue-100 flex items-center justify-center">
                                <span class="text-4xl font-bold text-blue-600">
                                    {{ substr($organisasi->nama_organisasi, 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $organisasi->nama_organisasi }}</h2>
                </div>
            </div>

            <!-- Info Sections -->
            <div class="space-y-6">
                <!-- Organization Info -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Organisasi</h2>
                        <button onclick="toggleEditModal()" class="text-blue-600 hover:underline">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <div>
                                <span class="font-semibold text-gray-600 block">Nama Organisasi:</span>
                                <span class="block">{{ $organisasi->nama_organisasi }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600 block">Email:</span>
                                <span class="block">{{ $organisasi->email_organisasi }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <span class="font-semibold text-gray-600 block">Nomor Telepon:</span>
                                <span class="block">{{ $organisasi->nomor_telepon }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600 block">Alamat:</span>
                                <span class="block">{{ $organisasi->alamat_organisasi }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


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