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
            <!-- Ikon Sosial Media & Text -->
            <div class="flex items-center space-x-2">
                <span class="font-semibold">Ikuti kami di</span>
                <a href="#" class="text-gray-700 hover:text-gray-900">
                    <svg class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M21.7 8.037a4.26 4.26 0 0 0-.789-1.964 2.84 2.84 0 0 0-1.984-.839c-2.767-.2-6.926-.2-6.926-.2s-4.157 0-6.928.2a2.836 2.836 0 0 0-1.983.839 4.225 4.225 0 0 0-.79 1.965 30.146 30.146 0 0 0-.2 3.206v1.5a30.12 30.12 0 0 0 .2 3.206c.094.712.364 1.39.784 1.972.604.536 1.38.837 2.187.848 1.583.151 6.731.2 6.731.2s4.161 0 6.928-.2a2.844 2.844 0 0 0 1.985-.84 4.27 4.27 0 0 0 .787-1.965 30.12 30.12 0 0 0 .2-3.206v-1.516a30.672 30.672 0 0 0-.202-3.206Zm-11.692 6.554v-5.62l5.4 2.819-5.4 2.801Z" clip-rule="evenodd"/>
                    </svg>
                </a>
                <a href="#" class="text-gray-700 hover:text-gray-900">
                    <svg class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path fill="currentColor" fill-rule="evenodd" d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z" clip-rule="evenodd"/>
                    </svg>
                </a>
                <a href="#" class="text-gray-700 hover:text-gray-900">
                    <svg class="w-6 h-6 text-gray-800 dark:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>

            <!-- Menu Navigasi -->
            <div class="hidden md:flex space-x-6 text-gray-700">
                <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
                <a href="{{ route('donasi')}}" class="hover:underline">Donasi</a>
                <a href="{{ route('loginPenitip')}}" class="hover:underline">Login Sebagai Penitip</a>
            </div>
        </div>

        <div class="bg-blue-600 p-4">
            <div class="container mx-auto flex items-center justify-between flex-wrap">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo6.png') }}" alt="ReUseMart" class="h-12">
                </div>

                <!-- Input Pencarian -->
                <div class="hidden md:block flex-grow mx-4">
                    <input type="text" placeholder="Mau cari apa nih kamu?" 
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                </div>

                <!-- Tombol Login & Sign In (Hanya Muncul di Desktop) -->
                <div class="hidden md:flex space-x-2">
                    <button class="bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-md transition-all hover:bg-blue-800 active:bg-blue-900">
                        <a href="{{ route('loginPembeli') }}">Log In</a>
                    </button>
                    <button class="bg-white text-black px-4 py-2 rounded-lg font-bold shadow-md transition-all hover:bg-gray-200 active:bg-gray-300">
                    <a href="{{ route('registerPembeli') }}">Sign in</a>
                    </button>
                </div>

                <!-- Hamburger Menu (Mobile) -->
                <div class="md:hidden flex items-center">
                    <button id="menu-toggle" class="text-white focus:outline-none">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Menu Mobile (Muncul saat tombol hamburger diklik) -->
            <div id="mobile-menu" class="hidden md:hidden mt-4">
                <!-- Input Pencarian di Mobile -->
                <input type="text" placeholder="Mau cari apa nih kamu?" 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none mb-2">
                <button class="bg-blue-700 text-white w-full px-4 py-2 rounded-lg font-bold shadow-md transition-all hover:bg-blue-800 active:bg-blue-900">
                    Log In
                </button>
                <button class="bg-white text-black w-full px-4 py-2 rounded-lg font-bold shadow-md transition-all hover:bg-gray-200 active:bg-gray-300 mt-2">
                    Sign In
                </button>

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
        const minusBtn = document.querySelector('.fa-minus').parentElement;
        const plusBtn = document.querySelector('.fa-plus').parentElement;
        const quantityInput = document.querySelector('input[type="text"]');

        minusBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                quantityInput.value = value - 1;
            }
        });

        plusBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            quantityInput.value = value + 1;
        });
    </script>
</body>
</html>