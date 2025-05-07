<!-- resources/views/registerPembeli.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReUseMart - Sign Up</title>
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
                <h2 class="text-xl font-bold mb-4 text-white">Sign Up Pembeli</h2>

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
                <form method="POST" action="{{ route('registerPembeli') }}" class="space-y-3">
                    @csrf

                    <div>
                        <input type="text" name="nama_pembeli" class="w-full p-2 rounded-lg" placeholder="Nama pembeli"
                            required>
                    </div>

                    <div>
                        <input type="date" name="tanggal_lahir" class="w-full p-2 rounded-lg"
                            placeholder="Tanggal Lahir" required>
                    </div>

                    <div>
                        <input type="text" name="alamat_pembeli" class="w-full p-2 rounded-lg" placeholder="Alamat"
                            required>
                    </div>

                    <div>
                        <input type="tel" name="nomor_telepon_pembeli" class="w-full p-2 rounded-lg"
                            placeholder="Nomor telepon" required>
                    </div>

                    <div>
                        <input type="email" name="email_pembeli" class="w-full p-2 rounded-lg" placeholder="Email"
                            required>
                    </div>

                    <div>
                        <input type="password" name="password_pembeli" class="w-full p-2 rounded-lg"
                            placeholder="Password" required>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full bg-blue-500 text-white p-2 rounded-md font-medium hover:bg-blue-600">Sign
                            Up</button>
                    </div>
                </form>


                <div class="mt-3 text-center">
                    <a href="#" class="text-black hover:underline text-sm font-bold">Lupa Password?</a>
                </div>

                <div class="mt-4 flex items-center justify-center space-x-2 text-sm">
                    <p class="text-white font-bold">Akun kamu udah ada?</p>
                    <a href='{{ route('loginPembeli') }}'
                        class="bg-transparent border border-white text-white px-3 py-1 rounded-md hover:bg-white hover:text-blue-500">Log
                        in</a>
                </div>
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