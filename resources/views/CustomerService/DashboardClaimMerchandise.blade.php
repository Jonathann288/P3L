<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Klaim Merchandise</title>
    <link rel="icon" type="image/png" sizes="128x128" href="{{ asset('images/logo2.png') }}">
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
                    <a href="{{ route('CustomerService.DashboardCS') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-user-circle mr-2"></i>
                        <span>Profil</span>
                    </a>
                    <a href="{{ route('CustomerService.DashboardPenitip') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-user mr-2"></i>
                        <span>Penitip</span>
                    </a>
                    <a href="{{ route('CustomerService.DashboardVerifikasiItem') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-user mr-2"></i>
                        <span>Verifikasi</span>
                    </a>
                    <a href="{{ route('CustomerService.DashboardClaimMerchandise') }}" class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fas fa-gift mr-2"></i>
                        <span>Klaim Merchandise</span>
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

    <!-- Main Content -->
    <div class="p-8 bg-gray-100">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-semibold text-gray-800">Daftar Klaim Merchandise</h1>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6">
                <div class="flex space-x-4">
                    <a href="{{ route('CustomerService.DashboardClaimMerchandise', ['filter' => 'all']) }}" 
                       class="px-4 py-2 rounded-lg {{ $filter == 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }}">
                        Semua
                    </a>
                    <a href="{{ route('CustomerService.DashboardClaimMerchandise', ['filter' => 'unclaimed']) }}" 
                       class="px-4 py-2 rounded-lg {{ $filter == 'unclaimed' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }}">
                        Belum Diambil
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left">No</th>
                            <th class="py-3 px-4 text-left">Pembeli</th>
                            <th class="py-3 px-4 text-left">Merchandise</th>
                            <th class="py-3 px-4 text-left">Tanggal Klaim</th>
                            <th class="py-3 px-4 text-left">Tanggal Pengambilan</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($claims as $index => $claim)
                            <tr class="{{ $index % 2 == 0 ? 'bg-gray-50' : 'bg-white' }}">
                                <td class="py-3 px-4">{{ $index + 1 }}</td>
                                <td class="py-3 px-4">{{ $claim->pembeli->nama_pembeli }}</td>
                                <td class="py-3 px-4">{{ $claim->merchandise->nama_merchandise }}</td>
                                <td class="py-3 px-4">{{ $claim->tanggal_claim->format('d-m-Y H:i') }}</td>
                                <td class="py-3 px-4">
                                    {{ $claim->tanggal_pengambilan ? $claim->tanggal_pengambilan->format('d-m-Y H:i') : '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($claim->status == 'sudah_diambil')
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">Sudah Diambil</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm">Belum Diambil</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($claim->status == 'belum_diambil')
                                        <form action="{{ route('CustomerService.updateClaimStatus', $claim->id_transaksi_claim_merchandise) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded-lg text-sm">
                                                Tandai Sudah Diambil
                                            </button>
                                        </form>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-gray-500">Tidak ada data klaim merchandise</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html> 