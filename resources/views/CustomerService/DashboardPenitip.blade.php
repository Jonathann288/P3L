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
                    <a href="{{ route('CustomerService.DashboardCS') }}" class="flex items-center space-x-4 p-3 hover:bg-gray-700 rounded-lg ">
                        <img src="images/fotoprofil.jpg" alt="profile" class="w-8 h-8 rounded-full object-cover">
                        <span>{{$pegawaiLogin->nama_pegawai}}</span>
                    </a>
                    <a href="{{ route('CustomerService.DashboardPegawai') }}" class="flex items-center space-x-4 p-3 bg-blue-600 rounded-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Penitip</span>
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
        <!-- Data Penitip -->
        <div class="bg-white p-6 rounded-lg shadow-md mt-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Data Penitip</h2>
                
                <!-- Tombol untuk membuka modal (posisi kanan) -->
                <button onclick="toggleModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">
                    Tambah Penitip
                </button>
            </div>
            <table class="min-w-full bg-white border">
                <thead>
                    <tr class="w-full bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">#</th>
                        <th class="py-3 px-6 text-left">Nama Penitip</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-left">Nomor Telepon</th>
                        <th class="py-3 px-6 text-left">tanggal Lahir</th>
                        <th class="py-3 px-6 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 ">
                    @foreach($penitip as $index => $p)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left">{{ $index + 1 }}</td>
                        <td class="py-3 px-6 text-left">{{ $p->nama_penitip }}</td>
                        <td class="py-3 px-6 text-left">{{ $p->email_penitip }}</td>
                        <td class="py-3 px-6 text-left">{{ $p->nomor_telepon_penitip }}</td>
                        <td class="py-3 px-6 text-left">{{ $p->tanggal_lahir}}</td>
                        <td class="py-3 px-6 text-left">
                            <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-400">Edit</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-400">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="modalTambahPenitip" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-8 rounded-lg w-full max-w-lg">
            <h2 class="text-2xl font-bold mb-4">Tambah Penitip</h2>
            
            <form action="{{ route('CustomerService.penitip.register') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="nama_penitip" placeholder="Nama Penitip" class="w-full p-2 border rounded">
                    <input type="text" name="nomor_ktp" placeholder="Nomor KTP" class="w-full p-2 border rounded">
                    <input type="email" name="email_penitip" placeholder="Email Penitip" class="w-full p-2 border rounded">
                    <input type="date" name="tanggal_lahir" placeholder="Tanggal Lahir" class="w-full p-2 border rounded">
                    <input type="text" name="nomor_telepon_penitip" placeholder="Nomor Telepon" class="w-full p-2 border rounded">
                    <input type="password" name="password_penitip" placeholder="Password" class="w-full p-2 border rounded">
                    <input type="file" name="foto_ktp" class="w-full p-2 border rounded">
                </div>

                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" onclick="toggleModal()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
    function toggleModal() {
        const modal = document.getElementById('modalTambahPenitip');
        modal.classList.toggle('hidden');
        modal.classList.toggle('flex');
    }
</script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>