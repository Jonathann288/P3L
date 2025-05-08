<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReUseMart - Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
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
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Toast for error message -->
                <div id="toast" class="fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded shadow-lg opacity-0 transition-opacity duration-500 z-50">
                    <span id="toast-message"></span>
                </div>

                <form method="POST" action="{{ route('loginOrganisasi.post') }}" class="space-y-3">
                    @csrf

                    <div>
                        <input type="email" name="emailOrganisasi" id="emailOrganisasi" class="w-full p-2 rounded-lg @error('emailOrganisasi') border-red-500 @enderror"
                            placeholder="Email" value="{{ old('emailOrganisasi') }}" required>
                    </div>

                    <div>
                        <input type="password" name="passwordOrganisasi" id="passwordOrganisasi"
                            class="w-full p-2 rounded-lg @error('passwordOrganisasi') border-red-500 @enderror" placeholder="Password" required>
                        @error('passwordOrganisasi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full bg-blue-500 text-white p-2 rounded-md font-medium hover:bg-blue-600">Log
                            In</button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <a href='{{ route('LupaPasswordOrg.lupaPasswordOrganisasi') }}' class="text-black hover:underline text-sm font-bold">Lupa Password ?</a>
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

    <script>
        function showToast(message) {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toast-message');
            toastMsg.textContent = message;
            toast.classList.remove('opacity-0');
            toast.classList.add('opacity-100');

            setTimeout(() => {
                toast.classList.remove('opacity-100');
                toast.classList.add('opacity-0');
            }, 3000);
        }

        window.onload = () => {
            @if (session('toast_error'))
                showToast(@json(session('toast_error')));
            @endif
        };
    </script>
</body>

</html>