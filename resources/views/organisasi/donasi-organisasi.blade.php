@extends('layouts.loading')
@section('content')
    <div id="content" style="display: none;" class="min-h-screen">
        <nav class="fixed top-0 left-0 w-full z-50">
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
                    @if(Auth::guard('organisasi')->check())
                        <div class="relative">
                            <button id="dropdownToggle" class="bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-blue-800">
                                {{ Auth::guard('organisasi')->user()->nama_organisasi }}
                            </button>
                            <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg">
                                <a href="{{ route('organisasi.profilOrganisasi') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</a>
                                <form action="{{route('logout.organisasi')}}" method="POST">
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
                            href="{{ route('organisasi.detail_barang_donasi', $item->id_barang) }}"  
                        >
                            <img 
                                class="absolute top-0 right-0 h-full w-full object-cover" 
                                src="{{ $item->foto_barang }}" 
                                alt="{{ $item->nama_barang }}"
                            >
                        </a>

                        <div class="flex flex-col flex-grow mt-2 px-2 pb-2">
                            <a href="{{ route('organisasi.detail_barang_donasi', $item->id_barang) }}">  
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

    <div id="toast" class="fixed bottom-4 right-4 hidden p-4 rounded-lg shadow-lg text-white z-50"></div>
     <!-- Script Toggle Menu -->
    <script>
        document.getElementById("menu-toggle").addEventListener("click", function () {
        document.getElementById("mobile-menu").classList.toggle("hidden");
        });

        // document.addEventListener("DOMContentLoaded", function () {
        // document.getElementById("content").style.display = "block";
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
@endsection