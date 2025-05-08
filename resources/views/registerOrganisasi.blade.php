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
                <h2 class="text-xl font-bold mb-4 text-white">Sign Up Organisasi</h2>

                <form method="POST" action="{{ route('registerOrganisasi') }}" class="space-y-3">
                    @csrf

                    <div>
                        <input type="text" name="nama_organisasi" id="nameOrganisasi" class="w-full p-2 rounded-lg"
                            placeholder="Nama Organisasi" required autofocus>
                    </div>

                    <div>
                        <input type="alamat" name="alamat_organisasi" id="alamatOrganisasi" class="w-full p-2 rounded-lg"
                            placeholder="Alamat" required>
                    </div>

                    <div>
                        <input type="tel" name="nomor_telepon" id="phoneOrganisasi" class="w-full p-2 rounded-lg"
                            placeholder="Nomor telepon" required>
                    </div>

                    <div>
                        <input type="email" name="email_organisasi" id="emailOrganisasi" class="w-full p-2 rounded-lg" placeholder="Email"
                            required>
                    </div>

                    <div>
                        <input type="password" name="password_organisasi" id="passwordOrganisasi" class="w-full p-2 rounded-lg"
                            placeholder="Password" required>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full bg-blue-500 text-white p-2 rounded-md font-medium hover:bg-blue-600">Log
                            In</button>
                    </div>
                </form>

                <div class="mt-4 flex items-center justify-center space-x-2 text-sm">
                    <p class="text-white font-bold">Akun kamu udah ada?</p>
                    <a href='{{ route('loginOrganisasi') }}'
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