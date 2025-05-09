<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet"> 
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
                        <img src="images/fotoprofil.jpg" alt="profile" class="w-8 h-8 rounded-full object-cover">
                        <span>Profil Saya</span>
                    </div>
                    <div class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Pegawai</span>
                    </div>
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
            <h1 class="text-3xl font-semibold text-gray-800">Profil Pembeli</h1>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Profile Card -->
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="w-32 h-32 bg-gray-200 rounded-full mx-auto mb-6">
                    <img src="images/fotoprofil.jpg" alt="Foto Profil"class="w-full h-full object-cover rounded-full">
                </div>
                <div class="font-semibold text-xl mb-2">John Doe</div>
                <button class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-500 w-full mt-4">
                    <i class="fas fa-pencil-alt"></i> Edit Profil
                </button>
            </div>

            <!-- Info Sections -->
            <div class="col-span-2 space-y-6">
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
                            <span>John Doe</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Email:</span>
                            <span>john.doe@example.com</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Nomor Telepon:</span>
                            <span>+62 812-3456-7890</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Tanggal Lahir:</span>
                            <span>15 Januari 1990</span>
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
                            <span class="text-xl font-bold text-orange-500">1,250</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-600">Status Member:</span>
                            <span>Gold (500 poin lagi untuk Platinum)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>