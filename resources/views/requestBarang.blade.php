<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Form Request Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white-400 min-h-screen flex items-center justify-center">
    <a href="{{ route('organisasi.donasi-organisasi') }}"
        class="absolute top-4 left-4 bg-white text-blue-600 p-2 rounded-full shadow-md hover:bg-gray-200 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </a>
    <div class="flex w-full max-w-5xl rounded-2xl overflow-hidden shadow-lg">
        <!-- Left Panel - Form -->
        <div class="w-1/2 bg-blue-200 p-10">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Form Request Barang</h2>
                <p class="text-gray-600">Silakan isi formulir berikut untuk mengajukan permintaan barang.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('requestBarang.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="deskripsi_request" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi
                        Request</label>
                    <textarea id="deskripsi_request" name="deskripsi_request" rows="4" required
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Isi request barang disini."></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-md transition duration-200">
                    Kirim Request
                </button>
            </form>
        </div>

        <!-- Right Panel - Info -->
        <div class="w-1/2 bg-blue-700 text-white p-10 flex flex-col justify-center">
            <h2 class="text-3xl font-bold mb-4">REUSEMART</h2>
            <h3 class="text-xl font-semibold mb-4">SOLUSI RAMAH LINGKUNGAN UNTUK MASA DEPAN</h3>
            <p class="text-sm leading-relaxed">
                ReuseMart hadir sebagai platform yang mendukung gaya hidup berkelanjutan. Dengan konsep Reduce, Reuse,
                Recycle,
                kami membantu mengurangi limbah dengan memberikan barang-barang bekas berkualitas kesempatan kedua. Mari
                bersama-sama menjaga bumi dengan pilihan yang lebih bijak!
            </p>
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