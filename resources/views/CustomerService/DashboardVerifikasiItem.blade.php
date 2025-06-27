<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pembayaran</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white p-6 flex flex-col justify-between w-64">
            <div>
                <h2 class="text-xl font-semibold mb-8">MyAccount</h2>
                <nav>
                    <div class="space-y-4">
                        <a href="{{ route('CustomerService.DashboardCS') }}"
                            class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                            <i class="text-2xl font-bold"></i> {{$pegawaiLogin->nama_pegawai}}
                        </a>

                        <a href="{{ route('CustomerService.DashboardPenitip') }}"
                            class="flex items-center space-x-4 p-3 hover:bg-gray-600 rounded-lg">
                            <i class="fas fa-user mr-2"></i> <span>Penitip</span>
                        </a>

                        <a href="{{ route('CustomerService.DashboardVerifikasiItem') }}"
                            class="flex items-center space-x-4 p-3 bg-blue-600 hover:bg-gray-600 rounded-lg">
                            <i class="fa-solid fa-check-to-slot"></i> <span>Verifikasi</span>
                        </a>
                        <a href="{{ route('CustomerService.DashboardClaimMerchandise') }}"
                            class="flex items-center space-x-4 p-3 bg-blue-600 hover:bg-gray-600 rounded-lg">
                            <i class="fa-solid fa-check-to-slot"></i> <span>Klaim Merchandise</span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <div class="container mx-auto px-4 py-8">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Verifikasi Pembayaran</h1>
                        <p class="text-gray-600">Mengelola dan memverifikasi transaksi pelanggan</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                            {{ count($transaksiMenunggu) }} Pending
                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            {{ count($transaksiTerverifikasi) }} Verified
                        </span>
                    </div>
                </div>

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <strong class="font-medium">Error: </strong> {{ session('error') }}
                        </div>
                    </div>
                @endif

                <!-- Pending Transactions Section -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-10">
                    <div class="p-6 bg-blue-600 border-b border-blue-700">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 text-yellow-300 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Menunggu Konfirmasi Pembayaran
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Pembeli</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID Transaksi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Barang</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Transaksi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Pembayaran</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($transaksiMenunggu as $index => $transaksi)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span
                                                        class="text-blue-600 font-medium">{{ substr($transaksi->pembeli->nama_pembeli ?? 'N', 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $transaksi->pembeli->nama_pembeli ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-gray-800 text-xs font-medium rounded">{{ $transaksi->id }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1 max-w-xs">
                                                @foreach($transaksi->detailTransaksi as $detail)
                                                    <span
                                                        class="px-2 py-1 bg-blue-100 text-gray-800 text-xs rounded">{{ $detail->barang->nama_barang }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaksi->tanggal_transaksi->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full capitalize">{{ $transaksi->status_pembayaran }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <button onclick="showModal(
                                                                                            '{{ $transaksi->id }}',
                                                                                            '{{ $transaksi->pembeli->nama_pembeli }}',
                                                                                            '{{ $transaksi->tanggal_transaksi->format('d M Y') }}',
                                                                                            '{{ asset('storage/bukti_pembayaran/' . $transaksi->bukti_pembayaran) }}'
                                                                                        )"
                                                class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                View
                                            </button>

                                            <form action="{{ route('approve.transaction', $transaksi->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" onclick="return confirm('Approve this transaction?')"
                                                    class="text-green-600 hover:text-green-900 inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Approve
                                                </button>
                                            </form>

                                            <form action="{{ route('reject.transaction', $transaksi->id) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" onclick="return confirm('Tolak transaksi ini?')"
                                                    class="text-red-600 hover:text-red-900 inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.536-10.464a1 1 0 00-1.414-1.414L10 8.586 7.879 6.464a1 1 0 10-1.414 1.414L8.586 10l-2.121 2.121a1 1 0 101.414 1.414L10 11.414l2.121 2.121a1 1 0 001.414-1.414L11.414 10l2.122-2.121z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Reject
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center py-8">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                    </path>
                                                </svg>
                                                <p class="mt-2 text-sm font-medium text-gray-600">No pending transactions
                                                </p>
                                                <p class="text-xs text-gray-500">All transactions are verified</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Verified Transactions Section -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6 bg-blue-600 border-b border-blue-700">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 text-green-300 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Konfirmasi Pembayaran
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Pembeli</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID Transaksi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Barang</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Transaksi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Pembayaran</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($transaksiTerverifikasi as $index => $transaksi)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span
                                                        class="text-blue-600 font-medium">{{ substr($transaksi->pembeli->nama_pembeli ?? 'N', 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $transaksi->pembeli->nama_pembeli ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-gray-800 text-xs font-medium rounded">{{ $transaksi->id }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-1 max-w-xs">
                                                @foreach($transaksi->detailTransaksi as $detail)
                                                    <span
                                                        class="px-2 py-1 bg-blue-100 text-gray-800 text-xs rounded">{{ $detail->barang->nama_barang }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaksi->tanggal_transaksi->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full capitalize">{{ $transaksi->status_pembayaran }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <button onclick="showModal(
                                                                                            '{{ $transaksi->id }}',
                                                                                            '{{ $transaksi->pembeli->nama_pembeli }}',
                                                                                            '{{ $transaksi->tanggal_transaksi->format('d M Y') }}',
                                                                                            '{{ asset('storage/bukti_pembayaran/' . $transaksi->bukti_pembayaran) }}'
                                                                                        )"
                                                class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                View
                                            </button>

                                            <button class="text-gray-400 cursor-not-allowed inline-flex items-center"
                                                disabled>
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Approved
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center py-8">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                    </path>
                                                </svg>
                                                <p class="mt-2 text-sm font-medium text-gray-600">No verified transactions
                                                </p>
                                                <p class="text-xs text-gray-500">Approve pending transactions to see them
                                                    here</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="buktiModal" class="fixed inset-0 hidden bg-black bg-opacity-50 justify-center items-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden transform transition-all">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Bukti Pembayaran</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500">ID Transaksi</h3>
                            <p id="modalId" class="mt-1 text-lg font-semibold text-gray-900"></p>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500">Nama Pembeli</h3>
                            <p id="modalNama" class="mt-1 text-lg text-gray-900"></p>
                        </div>
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-500">Tanggal Transaksi</h3>
                            <p id="modalTanggal" class="mt-1 text-lg text-gray-900"></p>
                        </div>
                    </div>
                    <div class="border rounded-lg overflow-hidden">
                        <img id="modalGambar" src="" alt="Payment Receipt" class="w-full h-auto object-contain" />
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 flex justify-end border-t">
                <button onclick="closeModal()"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-gray-600 focus:outline-none">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Approve Confirmation Modal -->
    <div id="approveModal" class="fixed inset-0 hidden bg-black bg-opacity-50 justify-center items-center z-50 p-4">
        <div
            class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden transform transition-all mx-auto my-auto">
            <!-- Added mx-auto and my-auto -->
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Confirm Approval</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-center mb-4">
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-center text-gray-700 mb-6">Apakah Anda yakin ingin menyetujui transaksi ini?</p>
                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <p class="text-sm font-medium text-gray-500">Transaction ID:</p>
                    <p id="approveModalId" class="font-semibold"></p>
                    <p class="text-sm font-medium text-gray-500 mt-2">Customer:</p>
                    <p id="approveModalCustomer"></p>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 flex justify-end space-x-3 border-t">
                <button onclick="closeApproveModal()"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none">
                    Cancel
                </button>
                <form id="approveForm" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none">
                        Confirm Approval
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="rejectModal" class="fixed inset-0 hidden bg-black bg-opacity-50 justify-center items-center z-50 p-4">
        <div
            class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden transform transition-all mx-auto my-auto">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Confirm Rejection</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-center mb-4">
                    <div class="bg-red-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-center text-gray-700 mb-6">Apakah Anda yakin ingin menolak transaksi ini?</p>
                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <p class="text-sm font-medium text-gray-500">Transaction ID:</p>
                    <p id="rejectModalId" class="font-semibold"></p>
                    <p class="text-sm font-medium text-gray-500 mt-2">Customer:</p>
                    <p id="rejectModalCustomer"></p>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 flex justify-end space-x-3 border-t">
                <button onclick="closeRejectModal()"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none">
                    Cancel
                </button>
                <form id="rejectForm" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none">
                        Confirm Rejection
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showModal(id, nama, tanggal, buktiUrl) {
            document.getElementById('modalId').innerText = id;
            document.getElementById('modalNama').innerText = nama;
            document.getElementById('modalTanggal').innerText = tanggal;
            document.getElementById('modalGambar').src = buktiUrl;
            document.getElementById('buktiModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            document.getElementById('buktiModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Close modal when clicking outside
        document.getElementById('buktiModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeModal();
            }
        });

        function showApproveModal(transactionId, customerName, formAction) {
            document.getElementById('approveModalId').innerText = transactionId;
            document.getElementById('approveModalCustomer').innerText = customerName;
            document.getElementById('approveForm').action = formAction;
            document.getElementById('approveModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Update your existing approve buttons to use this modal
        document.addEventListener('DOMContentLoaded', function () {
            // Replace all approve buttons to use the modal
            const approveButtons = document.querySelectorAll('[onclick^="return confirm(\'Approve this transaction?\')"]');

            approveButtons.forEach(button => {
                const form = button.closest('form');
                const row = button.closest('tr');
                const transactionId = row.querySelector('td:nth-child(3) span').textContent;
                const customerName = row.querySelector('td:nth-child(2) div.ml-4 div').textContent.trim();

                // Remove the old onclick handler
                button.removeAttribute('onclick');

                // Add new click handler
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    showApproveModal(transactionId, customerName, form.action);
                });
            });
        });

        function showRejectModal(transactionId, customerName, formAction) {
        document.getElementById('rejectModalId').innerText = transactionId;
        document.getElementById('rejectModalCustomer').innerText = customerName;
        document.getElementById('rejectForm').action = formAction;
        document.getElementById('rejectModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Update all reject buttons to use the modal
    document.addEventListener('DOMContentLoaded', function() {
        const rejectButtons = document.querySelectorAll('[onclick^="return confirm(\'Tolak transaksi ini?\')"]');

        rejectButtons.forEach(button => {
            const row = button.closest('tr');
            const transactionId = row.querySelector('td:nth-child(3) span').textContent;
            const customerName = row.querySelector('td:nth-child(2) div.ml-4 div').textContent.trim();
            const form = button.closest('form');
            
            // Remove the old onclick handler
            button.removeAttribute('onclick');

            // Add new click handler
            button.addEventListener('click', function(e) {
                e.preventDefault();
                showRejectModal(transactionId, customerName, form.action);
            });
        });
    });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>