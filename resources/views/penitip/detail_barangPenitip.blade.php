<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReuseMart - Detail Barang</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="{{asset('css/style.css')}}"> 
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        .rating-stars {
            display: inline-flex;
            position: relative;
            unicode-bidi: bidi-override;
            color: #ddd;
            font-size: 24px;
        }
        .rating-stars .filled {
            color: #fbbf24;
            position: absolute;
            left: 0;
            top: 0;
            width: 90%;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="w-full">
        <div class="bg-blue-300 text-sm py-2 px-6 flex justify-around items-center">
            <div class="flex items-center space-x-2">
                <span class="font-semibold">Ikuti kami di</span>
                <!-- Social Media Icons -->
            </div>

            <div class="hidden md:flex space-x-6 text-gray-700">
                <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>

            </div>
        </div>

        <div class="bg-blue-600 p-4">
            <div class="container mx-auto flex items-center justify-between flex-wrap">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo6.png') }}" alt="ReUseMart" class="h-12">
                </div>

                <div class="hidden md:block flex-grow mx-4">
                    <input type="text" placeholder="Mau cari apa nih kamu?" 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                </div>

                <!-- Cek Autentikasi -->
                @if(Auth::guard('penitip')->check())
                    <div class="relative">
                        <button id="dropdownToggle" class="bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-blue-800">
                            {{ Auth::guard('penitip')->user()->nama_penitip }}
                        </button>
                        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg">
                            <a href="{{ route('penitip.profilPenitip') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Transaksi</a>
                            <form action="#" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="hidden md:flex space-x-2">
                        <a href="{{ route('loginPembeli') }}" class="bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-blue-800">
                            Log In
                        </a>
                        <a href="{{ route('registerPembeli') }}" class="bg-white text-black px-4 py-2 rounded-lg font-bold shadow-md hover:bg-gray-200">
                            Sign In
                        </a>
                    </div>
                @endif

                <div class="md:hidden flex items-center">
                    <button id="menu-toggle" class="text-white focus:outline-none">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pb-12 px-4 max-w-6xl mx-auto">
        <!-- Product Section -->
        <div class="flex flex-col md:flex-row gap-8 pt-8">
            <!-- Product Images -->
            <div class="w-full md:w-1/2 bg-white p-4 rounded-lg shadow-sm">
                <div class="bg-gray-100 rounded-lg h-96 flex items-center justify-center">
                    <!-- Ganti dengan gambar dari database -->
                    <img src="{{ asset($barang->foto_barang) }}" alt="{{ $barang->nama_barang }}" class="max-h-full max-w-full">
                </div>
            </div>

            <!-- Product Details -->
            <div class="w-full md:w-1/2">
                <!-- Nama Barang -->
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $barang->nama_barang }}</h1>
                
                <!-- Rating -->
                <div class="flex items-center mb-4">
                    <div class="flex items-center">
                        <div class="rating-stars">
                            <span>★★★★★</span>
                            <!-- Hitung persentase rating -->
                            <span class="filled" style="width: {{ ($barang->rating_barang / 5) * 100 }}%">★★★★★</span>
                        </div>
                        <span class="ml-1 text-gray-700">{{ number_format($barang->rating_barang, 1) }}</span>
                    </div>
                    <span class="mx-2 text-gray-400">|</span>
                    <span class="text-gray-600">{{ $barang->jumlah_penilaian }} Penilaian</span>
                    <span class="mx-2 text-gray-400">|</span>
                    <span class="text-gray-600">{{ $barang->jumlah_terjual }} Terjual</span>
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <p class="text-3xl font-bold text-red-600">Rp{{ number_format($barang->harga_barang, 0, ',', '.') }}</p>
                    @if($barang->harga_asli)
                    <div class="flex items-center mt-1">
                        <p class="text-lg text-gray-500 line-through mr-2">Rp{{ number_format($barang->harga_asli, 0, ',', '.') }}</p>
                        @php
                            $diskon = (($barang->harga_asli - $barang->harga_barang) / $barang->harga_asli) * 100;
                        @endphp
                        <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-sm font-medium">-{{ floor($diskon) }}%</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById("menu-toggle").addEventListener("click", function () {
            document.getElementById("mobile-menu").classList.toggle("hidden");
        });

        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("content").style.display = "block";
        });

        // Quantity controls
        // const minusBtn = document.querySelector('.fa-minus').parentElement;
        // const plusBtn = document.querySelector('.fa-plus').parentElement;
        // const quantityInput = document.querySelector('input[type="text"]');

        // minusBtn.addEventListener('click', function() {
        //     let value = parseInt(quantityInput.value);
        //     if (value > 1) {
        //         quantityInput.value = value - 1;
        //     }
        // });

        // plusBtn.addEventListener('click', function() {
        //     let value = parseInt(quantityInput.value);
        //     quantityInput.value = value + 1;
        // });

        const toggle = document.getElementById('dropdownToggle');
        const menu = document.getElementById('dropdownMenu');
        
        toggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
        
        window.addEventListener('click', (e) => {
            if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>