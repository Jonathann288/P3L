<!DOCTYPE html>
<html lang="en" x-data="{ showModal: false, donasi: null }" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</head>

<body class="font-sans bg-gray-100 text-gray-800 grid grid-cols-1 md:grid-cols-[250px_1fr] min-h-screen">

    <!-- Sidebar Navigation -->
    <div class="bg-gray-800 text-white p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-xl font-semibold mb-8">Owner</h2>
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
                        class="flex items-center space-x-4 p-3 bg-gray-700 rounded-lg">
                        <i class="fas fa-hand-holding-heart mr-2"></i>
                        <span>History Donasi</span>
                    </a>
                </div>
            </nav>
        </div>
        <div class="space-y-4 mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2 bg-red-600 text-white rounded-lg hover:bg-red-500">
                    Keluar
                </button>
            </form> aa
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">History Donasi ke Organisasi</h1>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Search Form -->
        <form method="GET" action="{{ route('owner.DashboardHistoryDonasi') }}" class="mb-6 flex gap-2">
            <input type="text" name="search" placeholder="Cari organisasi, penerima, atau barang..."
                value="{{ request('search') }}" class="px-4 py-2 border border-gray-300 rounded flex-1">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Organisasi</th>
                        <th class="py-3 px-4">Nama Penerima</th>
                        <th class="py-3 px-4">Nama Barang</th>
                        <th class="py-3 px-4">Tanggal Donasi</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($donasis as $index => $donasi)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4 text-center">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">{{ $donasi->requestdonasi->organisasi->nama_organisasi ?? '-' }}</td>
                            <td class="py-3 px-4">{{ $donasi->nama_penerima }}</td>
                            <td class="py-3 px-4">{{ $donasi->barang->nama_barang ?? '-' }}</td>
                            <td class="py-3 px-4">{{ $donasi->tanggal_donasi }}</td>
                            <td class="py-3 px-4">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold 
                                                                {{ $donasi->requestdonasi->status_request == 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $donasi->requestdonasi->status_request ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button @click="donasi = { 
                                    id_barang: '{{ $donasi->id_barang }}', 
                                    id_request: '{{ $donasi->id_request }}', 
                                    nama_penerima: '{{ $donasi->nama_penerima }}',
                                    tanggal_donasi: '{{ $donasi->tanggal_donasi->format('Y-m-d') }}',
                                    status_request: '{{ $donasi->requestdonasi->status_request }}',
                                    nama_barang: '{{ $donasi->barang->nama_barang }}',
                                    nama_organisasi: '{{ $donasi->requestdonasi->organisasi->nama_organisasi }}'
                                }; 
                                showModal = true" class="px-3 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 px-4 text-center text-gray-500">Tidak ada data donasi ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg" @click.away="showModal = false">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Edit Donasi</h2>
                <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form x-bind :action="'/dashboard-history-donasi/update/' + donasi.id_barang + '/' + donasi.id_request"
                method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-gray-700 mb-1">Nama Organisasi</label>
                    <input type="text" name="nama_organisasi"
                        x-bind:value="donasi?.requestdonasi?.organisasi?.nama_organisasi"
                        class="w-full px-4 py-2 border border-gray-300 rounded bg-gray-100" readonly>
                    <p class="text-xs text-gray-500 mt-1">*Nama organisasi tidak dapat diubah</p>
                </div>

                <div>
                    <label class="block text-gray-700 mb-1">Nama Penerima</label>
                    <input type="text" name="nama_penerima" x-bind:value="donasi?.nama_penerima"
                        class="w-full px-4 py-2 border border-gray-300 rounded" required>
                </div>

                <div>
                    <label class="block text-gray-700 mb-1">Nama Barang</label>
                    <input type="text" name="nama_barang" x-bind:value="donasi?.barang?.nama_barang"
                        class="w-full px-4 py-2 border border-gray-300 rounded bg-gray-100" readonly>
                    <p class="text-xs text-gray-500 mt-1">*Nama barang tidak dapat diubah</p>
                </div>

                <div>
                    <label class="block text-gray-700 mb-1">Tanggal Donasi</label>
                    <input type="date" name="tanggal_donasi" x-bind:value="donasi?.tanggal_donasi"
                        class="w-full px-4 py-2 border border-gray-300 rounded" required>
                </div>

                <div>
                    <label class="block text-gray-700 mb-1">Status Request</label>
                    <select name="status_request" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                        <option value="approved" x-bind:selected="donasi?.requestdonasi?.status_request == 'approved'">
                            Approved</option>
                        <option value="rejected" x-bind:selected="donasi?.requestdonasi?.status_request == 'rejected'">
                            Rejected</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" @click="showModal = false"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>