<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner<object data="wner" type=""></object></title>
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
            <h2 class="text-xl font-semibold mb-8">Owner</h2>
            <nav>
                <div class="space-y-4">
                    <a href="{{ route('owner.DashboardOwner') }}"
                        class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg">
                        <i class="fas fa-user-circle mr-2"></i>
                        <span>Profile Owner</span>
                    </a>
                    <a href="{{ route('owner.DashboardDonasi') }}"
                        class="flex items-center space-x-4 p-3 bg-gray-700 rounded-lg">
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
        <h1 class="text-3xl font-bold mb-6">Permintaan Donasi (Pending)</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="min-w-full bg-white shadow-md rounded mb-6">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="py-2 px-4">Nama Organisasi</th>
                    <th class="py-2 px-4">Deskripsi Request</th>
                    <th class="py-2 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requestDonasis as $request)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $request->organisasi->nama_organisasi ?? '-' }}</td>
                        <td class="py-2 px-4">{{ $request->deskripsi_request }}</td>
                        <td class="py-2 px-4 space-x-2">
                            <form action="{{ route('owner.approveDonasi', $request->id_request) }}" method="POST"
                                class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-green-500 text-white px-4 py-1 rounded hover:bg-green-600">Donasikan</button>
                            </form>
                            <form action="{{ route('owner.rejectDonasi', $request->id_request) }}" method="POST"
                                class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600">Tolak</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>