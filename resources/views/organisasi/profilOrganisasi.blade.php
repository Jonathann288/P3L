<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Organisasi</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">

    <!-- Sidebar Navigation -->
    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between md:h-screen overflow-y-auto">
        <div>
            <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
            <nav>

                <a href="{{ route('organisasi.profilOrganisasi') }}"
                    class="flex items-center space-x-4 p-3 rounded-lg 
                        {{ request()->routeIs('organisasi.profilOrganisasi') ? 'bg-blue-600 text-white' : 'hover:bg-gray-700 text-gray-300' }}">
                    <i class="fas fa-user"></i>
                    <span>Profil</span>
                </a>
                <div class="space-y-4">
                    <a href="{{ route('organisasi.requestDonasiOrganisasi') }}"
                        class="flex items-center space-x-4 p-3 rounded-lg 
           {{ request()->routeIs('organisasi.requestDonasiOrganisasi') ? 'bg-blue-600 text-white' : 'hover:bg-gray-700 text-gray-300' }}">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Request Donasi</span>
                    </a>

                </div>
            </nav>
        </div>
        <div class="space-y-4 mt-auto">
            <button class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">
                <a href="{{ route('organisasi.donasi-organisasi') }}">Kembali</a>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-8 bg-gray-100">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-gray-800">Profil Organisasi</h1>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Profile Card -->
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div
                    class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-6 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/fotoprofil.jpg') }}" alt="ReUseMart" class="w-full h-full object-cover">
                </div>
                <div class="font-semibold text-xl mb-2">{{ $organisasi->nama_organisasi }}</div>
            </div>

            <!-- Info Sections -->
            <div class="col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Organisasi</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Nama organisasi:</span>
                            <strong>{{ $organisasi->nama_organisasi }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Email Organisasi:</span>
                            <strong>{{ $organisasi->email_organisasi }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Nomor Telepon Organisasi:</span>
                            <strong>{{ $organisasi->nomor_telepon }}</strong>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Alamat Organisasi:</span>
                            <strong>{{ $organisasi->alamat_organisasi }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        function toggleModal() {
            const modal = document.getElementById('modal');
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    </script>
</body>

</html>