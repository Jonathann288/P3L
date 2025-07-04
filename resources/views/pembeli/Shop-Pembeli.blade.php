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

                    <!-- Cart Icon -->
                    <a href="{{ route('pembeli.cart') }}" class="relative mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-7 h-7 text-white hover:text-gray-200">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386a1.5 1.5 0 011.45 1.114l.637 2.548m0 0h12.522a1.125 1.125 0 011.087 1.47l-1.509 5.276a2.25 2.25 0 01-2.163 1.629H7.125a2.25 2.25 0 01-2.163-1.629L3.453 6.662m0 0L2.25 3m6.375 14.25a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        @php
                            $cartCount = session('cart') ? count(session('cart')) : 0;
                        @endphp
                        @if ($cartCount > 0)
                            <span id="cart-count"
                                class="absolute -top-2 -right-2 bg-red-600 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>


                    <!-- Cek Autentikasi -->
                    @if(Auth::guard('pembeli')->check())
                        <div class="relative">
                            <button id="dropdownToggle"
                                class="bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-blue-800 flex items-center space-x-2">
                                <img src="{{asset(Auth::guard('pembeli')->user()->foto_pembeli) }}" alt="profile"
                                    class="w-8 h-8 rounded-full object-cover">
                                <span>{{ Auth::guard('pembeli')->user()->nama_pembeli }}</span>
                            </button>

                            <div id="dropdownMenu"
                                class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg">
                                <a href="{{ route('pembeli.profilPembeli') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</a>
                                <form action="{{ route('logout.pembeli') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="hidden md:flex space-x-2">
                            <a href="{{ route('loginPembeli') }}"
                                class="bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-blue-800">
                                Log In
                            </a>
                            <a href="{{ route('registerPembeli') }}"
                                class="bg-white text-black px-4 py-2 rounded-lg font-bold shadow-md hover:bg-gray-200">
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

        <div class="mt-24 px-4 pt-20">
            <div class="bg-blue-600 rounded-lg p-4 w-full max-w-6xl mx-auto">
                <!-- Wrapper Scroll untuk Mobile -->
                <div class="overflow-x-auto md:overflow-hidden pl-4 pr-4">
                    <div class="flex md:grid md:grid-cols-5 gap-6 text-center">

                        <!-- Kategori -->
                        @foreach ($kategoris as $index => $kategori)
                            <a href="{{ route('pembeli.categoryPembeli', $kategori->id_kategori) }}" class="cursor-pointer">
                                <div class="flex flex-col items-center min-w-[25%] md:min-w-0 transition-all">
                                    <div class="w-16 h-16 bg-white rounded-md flex items-center justify-center">
                                        <img src="{{ $images[$index] ?? 'images/default.png' }}" alt="Icon {{ $index + 1 }}"
                                            class="w-12 h-12">
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
                        <div
                            class="group flex w-full max-w-[450px] flex-col overflow-hidden rounded-lg border border-gray-100 bg-white shadow-md p-3 h-full">
                            <!-- 1. Link pada Gambar Produk -->
                            <a class="relative flex h-36 overflow-hidden rounded-xl"
                                href="{{ route('pembeli.detail_barangPembeli', $item->id_barang) }}" <!-- Tambahkan route di
                                sini -->
                                >
                                <img class="absolute top-0 right-0 h-full w-full object-cover"
                                    src="{{ asset($item->foto_barang[0] ?? 'default.jpg') }}" alt="{{ $item->nama_barang }}">
                            </a>

                            <div class="flex flex-col flex-grow mt-2 px-2 pb-2">
                                <!-- Link pada Judul Produk -->
                                <a href="{{ route('pembeli.detail_barangPembeli', $item->id_barang) }}">
                                    <h5 class="text-base tracking-tight text-slate-900 hover:text-blue-600">
                                        {{ $item->nama_barang }}
                                    </h5>
                                </a>

                                <div class="mt-1 mb-4 flex items-center justify-between">
                                    <p class="text-xl font-bold text-slate-900">
                                        Rp {{ number_format($item->harga_barang, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div class="mt-auto flex flex-col space-y-2">
                                    <!-- Tombol Add to Cart -->
                                    <form action="{{ route('keranjang.tambah', $item->id_barang) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-semibold rounded-lg text-sm px-5 py-3 text-center min-h-[44px]">
                                            Add to cart
                                        </button>
                                    </form>

                                    <!-- Tombol Beli (dengan JavaScript untuk menambah ke cart dulu) -->
                                    <button onclick="buyNow({{ $item->id_barang }})"
                                        class="w-full text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-semibold rounded-lg text-sm px-5 py-3 text-center min-h-[44px]">
                                        Beli
                                    </button>
                                </div>

                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="toast" class="fixed bottom-4 right-4 hidden p-4 rounded-lg shadow-lg text-white z-50"></div>

        <!-- Form tersembunyi untuk add to cart -->
        <form id="hidden-cart-form" action="" method="POST" style="display: none;">
            @csrf
        </form>

        <!-- Script Toggle Menu -->
        <script>
            document.getElementById("menu-toggle").addEventListener("click", function () {
                document.getElementById("mobile-menu").classList.toggle("hidden");
            });

            document.addEventListener("DOMContentLoaded", function () {
                document.getElementById("content").style.display = "block";
            });

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

            // Fungsi untuk membeli item (tambah ke cart dulu, lalu redirect ke checkout)
            function buyNow(itemId) {
                // Siapkan form tersembunyi
                const form = document.getElementById('hidden-cart-form');
                form.action = `/keranjang/tambah/${itemId}`;

                // Submit form menggunakan fetch untuk menambah ke cart
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                            document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update cart count jika ada
                            updateCartCount();

                            // Show success message
                            showToast('Item berhasil ditambahkan ke keranjang', 'success');

                            // Redirect ke halaman checkout setelah delay singkat
                            setTimeout(() => {
                                window.location.href = "{{ route('pembeli.checkOutPembeli') }}";
                            }, 1000);
                        } else {
                            showToast(data.message || 'Gagal menambahkan ke keranjang', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan saat menambahkan item', 'error');
                    });
            }

            // Fungsi untuk update cart count
            function updateCartCount() {
                fetch('/cart/count', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                            document.querySelector('input[name="_token"]').value,
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        const cartCountElement = document.getElementById('cart-count');
                        if (data.count > 0) {
                            if (cartCountElement) {
                                cartCountElement.textContent = data.count;
                            } else {
                                // Buat elemen cart count baru jika belum ada
                                const cartIcon = document.querySelector('a[href*="cart"]');
                                if (cartIcon) {
                                    const newCountElement = document.createElement('span');
                                    newCountElement.id = 'cart-count';
                                    newCountElement.className = 'absolute -top-2 -right-2 bg-red-600 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full';
                                    newCountElement.textContent = data.count;
                                    cartIcon.appendChild(newCountElement);
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error updating cart count:', error);
                    });
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
@endsection