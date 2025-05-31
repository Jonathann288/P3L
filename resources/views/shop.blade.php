@extends('layouts.loading')
@section('content')
<div id="content" style="display: none;" class="min-h-screen">
    <nav class="fixed top-0 left-0 w-full z-50">
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
                    <form action="{{ route('barang.search') }}" method="GET" class="flex">
                        <input 
                            type="text" name="nama_barang" placeholder="Mau cari apa nih kamu?" value="{{ request('nama_barang') }}"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                    </form>
                </div>

                <!-- Tombol Login & Sign In (Hanya Muncul di Desktop) -->
                <div class="hidden md:flex space-x-2">
                    <button class="bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-md transition-all hover:bg-blue-800 active:bg-blue-900">
                        <a href="{{ route('login') }}">Log In</a>
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

    <div class="mt-24 px-4 pt-20">
        <div class="bg-blue-600 rounded-lg p-4 w-full max-w-6xl mx-auto">
            <!-- Wrapper Scroll untuk Mobile -->
            <div class="overflow-x-auto md:overflow-hidden pl-4 pr-4">
                <div class="flex md:grid md:grid-cols-5 gap-6 text-center">
                    
                    <!-- Kategori -->
                    @foreach ($kategoris as $index => $kategori)
                    <a href="{{ route('shop.category', $kategori->id_kategori) }}" class="cursor-pointer">
                        <div class="flex flex-col items-center min-w-[25%] md:min-w-0 transition-all">
                            <div class="w-16 h-16 bg-white rounded-md flex items-center justify-center">
                                <img src="{{ $images[$index] ?? 'images/default.png' }}" alt="Icon {{ $index + 1 }}" class="w-12 h-12">
                            </div>
                            <p class="text-white mt-2 text-sm">{{ $kategori->nama_kategori }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    <div class="my-10 px-4">
        <div class="bg-gray-100 py-4 max-w-6xl mx-auto">
            <div>
                <div class="text-center text-blue-500 font-semibold text-lg">
                    REKOMENDASI
                </div>
            <div class="mt-2 border-b-4 border-blue-500 w-full"></div>
        </div>
    </div>

    <div class="my-3 px-4">
        <div class="py-2 max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach ($barang as $item)
                <div class="group flex w-full max-w-[450px] flex-col overflow-hidden rounded-lg border border-gray-100 bg-white shadow-md p-3 h-full">
                    <!-- 1. Link pada Gambar Produk -->
                    <a 
                        class="relative flex h-36 overflow-hidden rounded-xl" 
                        href="{{ route('shop.detail_barang', $item->id_barang) }}"  <!-- Tambahkan route di sini -->
                    >
                        <img 
                            class="absolute top-0 right-0 h-full w-full object-cover" 
                            src="{{ asset($item->foto_barang[0] ?? 'default.jpg') }}" 
                            alt="{{ $item->nama_barang }}"
                        >
                    </a>

                    <div class="flex flex-col flex-grow mt-2 px-2 pb-2">
                        <!-- 2. Link pada Judul Produk -->
                        <a href="{{ route('shop.detail_barang', $item->id_barang) }}">  <!-- Tambahkan route di sini -->
                            <h5 class="text-base tracking-tight text-slate-900 hover:text-blue-600">
                                {{ $item->nama_barang }}
                            </h5>
                        </a>

                        <div class="mt-1 mb-2 flex items-center justify-between">
                            <p>
                                <span class="text-xl font-bold text-slate-900">
                                    Rp {{ number_format($item->harga_barang, 0, ',', '.') }}
                                </span>
                            </p>
                        </div>

                        <!-- Tombol "Add to Cart" (opsional) -->
                        <div class="mt-auto">
                            <button class="w-full rounded-md bg-slate-900 px-3 py-1 text-xs font-medium text-white hover:bg-gray-700">
                                Add to cart
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
</div>
<!-- Script Toggle Menu -->
<script>
    document.getElementById("menu-toggle").addEventListener("click", function () {
        document.getElementById("mobile-menu").classList.toggle("hidden");
    });

    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("content").style.display = "block";
    });
</script>
@endsection