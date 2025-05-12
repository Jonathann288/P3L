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
</head>

<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">

    <!-- Sidebar Navigation -->
    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('admin.Dashboard') }}"
                        class="block px-4 py-2 rounded text-white  hover:bg-blue-600">
                        <i class="fas fa-user mr-2"></i> Profile Admin
                    </a>

                    <a href="{{ route('admin.DashboardPegawai') }}"
                        class="block px-4 py-2 rounded text-white  hover:bg-blue-600">
                        <i class="fas fa-users mr-2"></i> Pegawai
                    </a>


                    <div class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-cog"></i>
                        <span>Jabatan</span>
                    </div>
                    <div class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-cog"></i>
                        <span>Organisasi</span>
                    </div>
                    <div class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-cog"></i>
                        <span>Merchandise</span>
                    </div>
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
            <h1 class="text-3xl font-semibold text-gray-800">Profil Admin</h1>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Profile Card -->
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-6">
                    <img src="public/images/fotoprofil.jpg" alt="Foto Profil"
                        class="w-full h-full object-cover rounded-full">
                </div>
                <div class="font-bold text-3xl mb-2">
                    <span>{{ $pegawai->nama_pegawai }}</span>
                </div>
                <!-- Tombol untuk Tampilkan Form -->
                <button onclick="document.getElementById('editForm').classList.toggle('hidden')"
                    class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-500 w-full mt-4">
                    <i class="fas fa-pencil-alt"></i> Edit Profil
                </button>

                <!-- Form Edit Profil -->
                <form id="editForm" action="{{ route('admin.updateProfil') }}" method="POST" class="mt-4 hidden">
                    @csrf
                    <input type="text" name="nama_pegawai" value="{{ $pegawai->nama_pegawai }}"
                        class="w-full p-2 border rounded mb-2" placeholder="Nama Lengkap" required>
                    <input type="text" name="nomor_telepon_pegawai" value="{{ $pegawai->nomor_telepon_pegawai }}"
                        class="w-full p-2 border rounded mb-2" placeholder="Nomor Telepon" required>
                    <input type="date" name="tanggal_lahir_pegawai" value="{{ $pegawai->tanggal_lahir_pegawai }}"
                        class="w-full p-2 border rounded mb-2" required>
                    <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-500 w-full">
                        Simpan Perubahan
                    </button>
                </form>

            </div>

            <!-- Info Sections -->
            <div class="col-span-2 space-y-6">
                <!-- Personal Info -->
                <div class="space-y-4">
                    @if(isset($pegawai))
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
                            <span class="font-semibold text-gray-600">Tanggal Lahir:</span>
                            <span>{{ \Carbon\Carbon::parse($pegawai->tanggal_lahir_pegawai)->translatedFormat('d F Y') }}</span>
                        </div>
                    @else
                        <div class="text-red-600">
                            Data pegawai tidak tersedia. Silakan login terlebih dahulu.
                        </div>
                    @endif
                </div>


            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>