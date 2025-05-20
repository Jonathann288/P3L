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
            <h2 class="text-xl font-semibold mb-8">Gudang</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('gudang.DashboardGudang') }}"
                        class="flex items-center space-x-4 p-3 bg-gray-700 rounded-lg">
                        <i class="fas fa-user-circle mr-2"></i>
                        <span>Profile Saya</span>
                    </a>
                    <a href="{{ route('gudang.DashboardTitipanBarang') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-dolly mr-2"></i>
                        <span>Tambah Titip Barang</span>
                    </a>
                
                </div>
            </nav>
        </div>
        <!-- Bottom buttons -->
        <div class="space-y-4 mt-auto">
            <button class="w-full py-2 bg-red-600 text-white rounded-lg hover:bg-red-500">
                Keluar
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-8 bg-gray-100">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-gray-800">Profil Saya</h1>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Info Sections -->
            <div class="col-span-5 space-y-6">
                <!-- Personal Info -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Pribadi</h2>
                        <button class="text-blue-600 hover:underline">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Nama Lengkap:</span>
                            <span>{{ $pegawai->nama_pegawai }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Email:</span>
                            <span>{{ $pegawai->email_pegawai }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Nomor Telepon:</span>
                            <span>{{ $pegawai->nomor_telepon_pegawai }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Jabatan</span>
                            <span>{{ $pegawai->Jabatan->nama_jabatan }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>