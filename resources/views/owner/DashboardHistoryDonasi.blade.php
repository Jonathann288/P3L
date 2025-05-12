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
                    <a href="{{ route('owner.DashboardOwner') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-user-circle mr-2"></i>
                        <span>Profile Owner</span>
                    </a>
                    <a href="{{ route('owner.DashboardDonasi') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-gift mr-2"></i>
                        <span>Donasi</span>
                    </a>
                    <a href="{{ route('owner.DashboardHistoryDonasi') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-hand-holding-heart mr-2"></i>
                        <span>History Donasi</span>
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


    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">History Donasi ke Organisasi</h1>

        <table class="min-w-full bg-white shadow-md rounded">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="py-2 px-4">Organisasi</th>
                    <th class="py-2 px-4">Nama Penerima</th>
                    <th class="py-2 px-4">Tanggal Donasi</th>
                    <th class="py-2 px-4">Nama Barang</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donasis as $donasi)
                <tr class="border-t">
                    <td class="py-2 px-4">{{ $donasi->requestdonasi->organisasi->nama_organisasi ?? '-' }}</td>
                    <td class="py-2 px-4">{{ $donasi->nama_penerima }}</td>
                    <td class="py-2 px-4">{{ \Carbon\Carbon::parse($donasi->tanggal_donasi)->format('d-m-Y') }}</td>
                    <td class="py-2 px-4">{{ $donasi->barang->nama_barang ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>




</html>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>