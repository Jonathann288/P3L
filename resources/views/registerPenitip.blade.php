<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReUseMart - Register Penitip</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="flex flex-col md:flex-row rounded-3xl shadow-xl overflow-hidden max-w-4xl w-full">
        <div class="w-full md:w-1/2 bg-blue-300 p-8 flex flex-col items-center justify-center">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo6.png') }}" alt="ReUseMart" class="h-12">
            </div>

            <div class="mb-6 text-center">
                <p class="text-white">Reduce, Reuse, Recycle</p>
            </div>

            <div class="w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-white">Register Penitip</h2>

                <form method="POST" action="{{ route('registerPenitip.post') }}" class="space-y-3">
                    @csrf

                    <div>
                        <input type="text" name="nama_penitip" class="w-full p-2 rounded-lg" placeholder="Nama Penitip" required>
                    </div>

                    <div>
                        <input type="text" name="nomor_ktp" class="w-full p-2 rounded-lg" placeholder="Nomor KTP" required>
                    </div>

                    <div>
                        <input type="email" name="email_penitip" class="w-full p-2 rounded-lg" placeholder="Email" required>
                    </div>

                    <div>
                        <input type="date" name="tanggal_lahir" class="w-full p-2 rounded-lg" required>
                    </div>

                    <div>
                        <input type="password" name="password_penitip" class="w-full p-2 rounded-lg" placeholder="Password" required>
                    </div>

                    <div>
                        <input type="text" name="nomor_telepon_penitip" class="w-full p-2 rounded-lg" placeholder="Nomor Telepon" required>
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md font-medium hover:bg-blue-600">Register</button>
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
