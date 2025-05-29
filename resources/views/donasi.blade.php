@extends('layouts.loading')
@section('content')
    <div id="content" style="display: none;" class="min-h-screen">
        <nav class="bg-blue-600 p-4">
            <div class="container mx-auto flex items-center justify-between flex-wrap">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('donasi') }}">
                        <img src="{{ asset('images/logo6.png') }}" alt="ReUseMart" class="h-12">
                    </a>
                </div>

                <div class="flex-grow mx-4 hidden md:block">
                    <input type="text" placeholder="Mau cari donasi apa nih kamu?"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                </div>

                <div class="flex space-x-2">
                    <button
                        style="background-color: #0056b3; border: none; color: white; padding: 8px 15px; border-radius: 10px; margin-right: 10px; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 4px 8px rgba(0,0,0,0.2);"
                        onmouseover="this.style.backgroundColor='#00336e'; this.style.transform='translateY(2px)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.1)';"
                        onmouseout="this.style.backgroundColor='#0056b3'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)';"
                        onmousedown="this.style.backgroundColor='#001f4d'; this.style.transform='translateY(4px)'; this.style.boxShadow='0 0px 0px rgba(0,0,0,0)';"
                        onmouseup="this.style.backgroundColor='#00336e'; this.style.transform='translateY(2px)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.1)';"
                        onclick="window.location.href='{{ route('loginOrganisasi') }}'">
                        Log In
                    </button>
                    <button
                        style="background-color: white; color: black; padding: 8px 15px; border-radius: 10px; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 4px 8px rgba(0,0,0,0.2);"
                        onmouseover="this.style.backgroundColor='#e0e0e0'; this.style.transform='translateY(2px)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.1)';"
                        onmouseout="this.style.backgroundColor='white'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)';"
                        onmousedown="this.style.backgroundColor='#c0c0c0'; this.style.transform='translateY(4px)'; this.style.boxShadow='0 0px 0px rgba(0,0,0,0)';"
                        onmouseup="this.style.backgroundColor='#e0e0e0'; this.style.transform='translateY(2px)'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.1)';"
                        onclick="window.location.href='{{ route('registerOrganisasi') }}'">
                        Sign In
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

            <!-- Menu untuk Mobile -->
            <div id="mobile-menu" class="hidden md:hidden mt-4">
                <input type="text" placeholder="Mau cari apa nih kamu?"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none mb-2">
                <button
                    class="bg-blue-700 text-white w-full px-4 py-2 rounded-lg font-bold shadow-md transition-all hover:bg-blue-800 active:bg-blue-900">
                    Log In
                </button>
                <button
                    class="bg-white text-black w-full px-4 py-2 rounded-lg font-bold shadow-md transition-all hover:bg-gray-200 active:bg-gray-300 mt-2">
                    Sign In
                </button>
            </div>
        </nav>

        <!-- Improved Carousel -->
        <div id="donation-carousel" class="relative w-full" data-carousel="slide">
            <!-- Carousel wrapper -->
            <div class="relative h-56 overflow-hidden md:h-96">
                <!-- Slide 1 -->
                <div class="carousel-item active relative w-full h-full">
                    <img src="{{ asset('images/donasiPic1.png') }}" class="w-full object-cover h-full" alt="Donation Banner 1">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="bg-black bg-opacity-50 p-4 rounded-lg text-white text-center max-w-lg">
                            <h2 class="text-2xl font-bold mb-2">Request Barang</h2>
                            <p class="mb-4">Tidak menemukan yang Anda cari? Ajukan permintaan barang</p>
                            <button class="bg-orange-500 hover:bg-orange-600 text-white py-2 px-6 rounded-full font-bold"
                                onclick="window.location.href='{{ route('loginOrganisasi') }}'">
                                Request Sekarang
                            </button>
                        </div>
                    </div>
                </div>
        </div>

        <div class="my-10 px-4">
            <div class="bg-gray-100 py-4 max-w-6xl mx-auto">
                <div>
                    <div class="text-center text-blue-500 font-semibold text-lg">
                        BARANG DONASI
                    </div>
                    <div class="mt-2 border-b-4 border-blue-500 w-full"></div>
                </div>
            </div>

        <div class="my-3 px-4">
            <div class="py-2 max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach ($barang as $item)
                    <div class="group flex w-full max-w-[450px] flex-col overflow-hidden rounded-lg border border-gray-100 bg-white shadow-md p-3 h-full">
                        <a 
                            class="relative flex h-36 overflow-hidden rounded-xl" 
                            href="{{ route('donasi.detail_barang_donasi', $item->id_barang) }}"  
                        >
                        <img 
                            class="absolute top-0 right-0 h-full w-full object-cover" 
                            src="{{ asset($item->foto_barang[0] ?? 'default.jpg') }}" 
                            alt="{{ $item->nama_barang }}"
                        >
                        </a>

                        <div class="flex flex-col flex-grow mt-2 px-2 pb-2">
                            <a href="{{ route('donasi.detail_barang_donasi', $item->id_barang) }}">  
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