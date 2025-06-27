<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pembeli</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
</head>

<body class="font-sans bg-gray-100 text-gray-800 min-h-screen">

   <div class="bg-gray-800 text-white p-6 w-64 flex flex-col h-screen justify-between">
    <div>
        <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
        <nav>
            <div class="space-y-4">
                <div class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                    <span>{{ ($penitip->nama_penitip) }}</span>
                </div>

                <a href="{{ route('penitip.barang-titipan') }}"
                   class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                    <i class="fa-solid fa-box w-6 text-center"></i>
                    <span>Titipan</span>
                </a>

                <a href="{{ route('penitip.penarikan.form') }}"
                   class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                    <i class="fa-solid fa-money-bill-wave w-6 text-center"></i>
                    <span>Penarikan Saldo</span>
                </a>
            </div>
        </nav>
    </div>

    <div class="mt-auto">
        <a href="{{ route('penitip.Shop-Penitip') }}" class="block w-full">
            <button class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500">
                Kembali
            </button>
        </a>
    </div>
</div>
    

      
        <div class="flex-1 p-4 md:p-8 bg-gray-100">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl md:text-3xl font-semibold text-gray-800">Profil Penitip</h1>
            </div>

            <!-- Profile nya bre-->
            <div class="bg-white p-6 rounded-lg shadow-md text-center mb-6">
                <div class="flex flex-col items-center">
                    
                    <div class="w-32 h-32 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                        <span class="text-4xl font-bold text-blue-600">
                            {{ substr($penitip->nama_penitip, 0, 1) }}
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $penitip->nama_penitip }}</h2>
                </div>
            </div>

            <!-- Info Sections -->
            <div class="space-y-6">
                <!-- Personal Info -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pribadi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <div>
                                <span class="font-semibold text-gray-600 block">Nama Lengkap:</span>
                                <span class="block">{{ $penitip->nama_penitip }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600 block">Email:</span>
                                <span class="block">{{ $penitip->email_penitip }}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div>
                                <span class="font-semibold text-gray-600 block">Nomor Telepon:</span>
                                <span class="block">{{ $penitip->nomor_telepon_penitip }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-600 block">Tanggal Lahir:</span>
                                <span
                                    class="block">{{ \Carbon\Carbon::parse($penitip->tanggal_lahir)->format('d F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Points Info -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Poin dan Penjualan Saya</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <span class="font-semibold text-gray-600 block">Total Poin</span>
                            <span class="text-xl font-bold text-orange-500 block">{{ $penitip->total_poin }}</span>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <span class="font-semibold text-gray-600 block">Saldo Saya</span>
                            <span class="block">Rp {{ number_format($penitip->saldo_penitip, 0, ',', '.') }}</span>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <span class="font-semibold text-gray-600 block">Jumlah Penjualan</span>
                            <span class="block">{{ $penitip->jumlah_penjualan }}</span>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <span class="font-semibold text-gray-600 block">Rating Saya</span>
                            <span class="block">{{ number_format($penitip->Rating_penitip, 1, '.', '') }} ★</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
      
        document.getElementById('mobile-menu-toggle').addEventListener('click', function () {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>

</html>