<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReUseMart - Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <style>
        /* Efek blur untuk background */
        .background-blur {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            /* Layer semi-transparan */
            backdrop-filter: blur(10px);
            /* Efek blur */
            z-index: -1;
            /* Menempatkan di belakang konten */
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <!-- Tambahkan elemen untuk efek blur -->
    <div class="background-blur"></div>

    <a href="{{ route('donasi') }}"
        class="absolute top-4 left-4 bg-white text-blue-600 p-2 rounded-full shadow-md hover:bg-gray-200 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </a>
    <div class="flex flex-col md:flex-row rounded-3xl shadow-xl overflow-hidden max-w-4xl w-full">

        <div class="w-full md:w-1/2 bg-blue-300 p-8 flex flex-col items-center justify-center">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo6.png') }}" alt="ReUseMart" class="h-12">
            </div>

            <div class="mb-6 text-center">
                <p class="text-white">Reduce, Reuse, Recycle</p>
            </div>

            <div class="w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-white">Login Organisasi</h2>
                @if (session('success'))
                    <div class="mb-4 text-green-700 bg-green-100 p-3 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->has('email_organisasi'))
                    <div class="mb-4 text-red-700 bg-red-100 p-3 rounded-lg text-sm">
                        {{ $errors->first('email_organisasi') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('loginOrganisasi.post') }}" class="space-y-3">
                    @csrf

                    <div>
                        <input type="email" name="email_organisasi" class="w-full p-2 rounded-lg" placeholder="Email"
                            required>
                    </div>

                    <div>
                        <input type="password" name="password_organisasi" class="w-full p-2 rounded-lg"
                            placeholder="Password" required>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full bg-blue-500 text-white p-2 rounded-md font-medium hover:bg-blue-600">Log
                            In</button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <a href="{{ route('forgotPassword') }}"
                        class="text-black hover:underline text-sm font-bold">Lupa Password ?</a>
                </div>

                <div class="mt-4 flex items-center justify-center space-x-2 text-sm">
                    <p class="text-white font-bold">Baru di ReUseMart? </p>
                    <a href='{{ route('registerOrganisasi') }}'
                        class="bg-transparent border border-white text-white px-3 py-1 rounded-md hover:bg-white hover:text-blue-500">Sign
                        up</a>
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
    <div id="toast" class="fixed bottom-4 right-4 hidden p-4 rounded-lg shadow-lg text-white z-50"></div>
    <script>
        // Fungsi untuk menampilkan Toast
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;

            // Set warna berdasarkan tipe
            if (type === 'success') {
                toast.classList.add('bg-green-500');
                toast.classList.remove('bg-red-500');
            } else if (type === 'error') {
                toast.classList.add('bg-red-500');
                toast.classList.remove('bg-green-500');
            }

            // Tampilkan toast dengan animasi fade-in
            toast.classList.remove('hidden');
            toast.classList.add('fade-in');

            // Hilangkan toast setelah 3 detik
            setTimeout(() => {
                toast.classList.remove('fade-in');
                toast.classList.add('fade-out');
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 500);
            }, 3000);
        }

        // Saat halaman dimuat, cek jika ada session flash
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif

            @if (session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif
        });
    </script>
</body>

</html>