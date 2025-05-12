<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Organisasi</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
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
                    class="flex items-center space-x-4 p-3 rounded-lg {{ request()->routeIs('organisasi.profilOrganisasi') ? 'bg-blue-600 text-white' : 'hover:bg-gray-700 text-gray-300' }}">
                    <i class="fas fa-user"></i>
                    <span>Profil</span>
                </a>
                <a href="{{ route('organisasi.requestDonasiOrganisasi') }}"
                    class="flex items-center space-x-4 p-3 rounded-lg {{ request()->routeIs('organisasi.requestDonasiOrganisasi') ? 'bg-blue-600 text-white' : 'hover:bg-gray-700 text-gray-300' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Request Donasi</span>
                </a>
            </nav>
        </div>
        <div class="space-y-4 mt-auto">
            <a href="{{ route('organisasi.donasi-organisasi') }}"
                class="block w-full py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-500">
                Kembali
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-8 bg-gray-100 w-full">
        <h1 class="text-3xl font-semibold mb-8">Request Donasi</h1>
        <form action="{{ route('organisasi.requestDonasiOrganisasi') }}" method="GET" class="mb-6">
            <div class="relative w-full md:w-1/3">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi request..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300">
            </div>
        </form>


        @if ($requestdonasi->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md mb-4 text-center text-gray-500">
                <i class="fas fa-box-open text-4xl text-gray-400 mb-2"></i>
                <p>Belum ada request donasi.</p>
            </div>
        @endif

        @foreach ($requestdonasi as $request)
            <div class="bg-white p-6 rounded-lg shadow-md mb-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Informasi Request</h2>
                    <div class="flex space-x-2">
                        <button
                            onclick="openEditModal('{{ $request->id }}', '{{ $request->deskripsi_request }}', '{{ $request->tanggal_request }}')"
                            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </button>

                        <button onclick="openDeleteModal('{{ $request->id }}')"
                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                            <i class="fa-solid fa-trash"></i> Delete
                        </button>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-600">Deskripsi Request:</span>
                        <strong>{{ $request->deskripsi_request }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-600">Tanggal Request:</span>
                        <strong>{{ \Carbon\Carbon::parse($request->tanggal_request)->format('d F Y') }}</strong>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Modal Edit -->
        <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
            <div class="bg-white p-8 rounded-lg w-1/3">
                <h2 class="text-xl mb-4 font-semibold">Edit Request Donasi</h2>
                <form method="POST" id="editForm" action="">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" id="edit_id">
                    <div class="space-y-4">
                        <div>
                            <label for="edit_deskripsi" class="block font-semibold">Deskripsi Request:</label>
                            <input type="text" id="edit_deskripsi" name="deskripsi_request"
                                class="w-full p-2 border rounded-lg">
                        </div>
                        <div>
                            <label for="edit_tanggal" class="block font-semibold">Tanggal Request:</label>
                            <input type="date" id="edit_tanggal" name="tanggal_request"
                                class="w-full p-2 border rounded-lg">
                        </div>
                    </div>
                    <div class="flex justify-end mt-6 space-x-2">
                        <button type="button" onclick="toggleEditModal()"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg">
                            Batal
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Delete -->
        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg w-1/3">
                <h2 class="text-xl mb-4 font-semibold text-red-600">Hapus Request Donasi</h2>
                <p class="mb-6">Apakah Anda yakin ingin menghapus request donasi ini?</p>
                <form id="deleteForm" method="POST" action="{{ route('organisasi.destroy', ['id' => 0]) }}">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="toggleDeleteModal()"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg">
                            Batal
                        </button>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg">
                            Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>


    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        function openEditModal(id, deskripsi_request, tanggal_request) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_deskripsi').value = deskripsi_request;
            document.getElementById('edit_tanggal').value = tanggal_request;

            const form = document.getElementById('editForm');
            form.action = "{{ url('request-donasi/update') }}/" + id;


            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }


        function toggleEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        function openDeleteModal(id) {
            const form = document.getElementById('deleteForm');
            form.action = form.action.replace(/\/\d+$/, '/' + id);
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function toggleDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

        function openDeleteModal(id) {
            const form = document.getElementById('deleteForm');
            form.action = "{{ url('organisasi/destroy') }}/" + id;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function toggleDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }

    </script>
</body>

</html>