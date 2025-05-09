<!-- resources/views/registerPembeli.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReUseMart - Tambah Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="flex flex-col md:flex-row rounded-3xl shadow-xl overflow-hidden max-w-4xl w-full">
        <a href="{{ route('donasi') }}"
            class="absolute top-4 left-4 bg-white text-blue-600 p-2 rounded-full shadow-md hover:bg-gray-200 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div class="w-full md:w-1/2 bg-blue-300 p-8 flex flex-col items-center justify-center">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo6.png') }}" alt="ReUseMart" class="h-12">
            </div>

            <div class="mb-6 text-center">
                <p class="text-white">Reduce, Reuse, Recycle</p>
            </div>

            <div class="w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-white">Tambah Pegawai</h2>

                {{-- Tampilkan error validasi --}}
                @if($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('registerPegawai.post') }}" class="space-y-3">
                    @csrf

                    <div>
                        <input type="text" name="nama_pegawai" class="w-full p-2 rounded-lg" placeholder="Nama Pegawai"
                            required>
                    </div>

                    <div>
                        <input type="date" class="w-full p-2 rounded-lg" name="tanggal_lahir_pegawai" required>
                    </div>

                    <div>
                        <input type="text" class="w-full p-2 rounded-lg" name="nomor_telepon_pegawai" required>
                    </div>

                    <div>
                        <input type="email" class="w-full p-2 rounded-lg" name="email_pegawai" required>
                    </div>

                    <div>
                        <input type="password" class="w-full p-2 rounded-lg" name="password_pegawai" required>
                    </div>

                    <div>
                        <select name="id_jabatan" required class="w-full p-2 rounded-lg">
                            <option value="">Pilih Jabatan </option>
                            @foreach ($jabatans as $jabatan)
                                @if (strtolower($jabatan->nama_jabatan) !== 'owner')
                                    <option value="{{ $jabatan->id_jabatan }}">{{ $jabatan->nama_jabatan }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>


                    <div>
                        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="w-full md:w-1/2 bg-blue-600 p-8 flex flex-col items-center justify-center">
            <div class="max-w-md text-white">
                <h2 class="text-2xl font-bold mb-1">REUSEMART</h2>
                <h3 class="text-xl font-bold mb-4">SOLUSI RAMAH LINGKUNGAN UNTUK MASA DEPAN</h3>

                <p class="text-sm">
                    ReuseMart hadir sebagai platform yang mendukung gaya hidup berkelanjutan. Dengan konsep Reduce,
                    Reuse, Recycle, kami membantu mengurangi limbah dengan memberikan barang-barang bekas berkualitas
                    kesempatan kedua. Mari bersama-sama menjaga bumi dengan pilihan yang lebih bijak!
                </p>
            </div>
        </div>
    </div>
</body>

</html>