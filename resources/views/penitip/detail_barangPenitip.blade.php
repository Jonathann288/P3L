<!-- SEMUA COPY -->
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
    <!-- Tambahkan ini di head -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                <a href="{{ route('penitip.Shop-Penitip') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo6.png') }}" alt="ReUseMart" class="h-12">
                </a>

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
            <!-- Product Images Section -->
            @php
                $fotos = $barang->foto_barang;
                $mainImage = $fotos[0] ?? null; // Ambil gambar pertama sebagai default
            @endphp

            <div class="w-full md:w-1/2 bg-white p-4 rounded-lg shadow-sm">
                <!-- Gambar Utama Besar -->
                <div class="bg-gray-100 rounded-lg h-96 flex items-center justify-center mb-4">
                    @if ($mainImage)
                        <img id="main-image" src="{{ asset($mainImage) }}" alt="Foto Utama"
                            class="max-h-full max-w-full object-contain">
                    @else
                        <span class="text-gray-400">Tidak ada gambar</span>
                    @endif
                </div>

                <!-- Thumbnail Gambar Kecil -->
                <div class="flex gap-2 overflow-x-auto py-2">
                    @foreach ($fotos as $index => $foto)
                        <div class="flex-shrink-0">
                            <img src="{{ asset($foto) }}" alt="Thumbnail {{ $index + 1 }}"
                                class="w-20 h-20 object-cover rounded border {{ $index === 0 ? 'border-blue-500' : 'border-gray-300 hover:border-blue-300' }} cursor-pointer"
                                onclick="changeMainImage(this)">
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Product Details -->
            <div class="w-full md:w-1/2">
                <!-- Nama Barang -->
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $barang->nama_barang }}</h1>

                <div class="mb-6">
                    <p class="text-3xl font-bold text-red-600">Rp{{ number_format($barang->harga_barang, 0, ',', '.') }}
                    </p>
                    @if($barang->harga_asli)
                        <div class="flex items-center mt-1">
                            <p class="text-lg text-gray-500 line-through mr-2">
                                Rp{{ number_format($barang->harga_asli, 0, ',', '.') }}</p>
                            @php
                                $diskon = (($barang->harga_asli - $barang->harga_barang) / $barang->harga_asli) * 100;
                            @endphp
                            <span
                                class="bg-red-100 text-red-600 px-2 py-0.5 rounded text-sm font-medium">-{{ floor($diskon) }}%</span>
                        </div>
                    @endif
                </div>

                <!-- Rating -->
                <div class="flex items-center mb-4">
                    <div id="rating-stars-container" data-rating="{{ $barang->rating_barang }}">
                    </div>
                    <span class="ml-2 text-gray-700">{{ number_format($barang->rating_barang, 1) }}</span>
                    <span class="mx-2 text-gray-400">|</span>
                    <span class="text-gray-600">{{ $barang->jumlah_penilaian }} Penilaian</span>
                    <span class="mx-2 text-gray-400">|</span>
                    <span class="text-gray-600">{{ $barang->jumlah_terjual }} Terjual</span>
                </div>

                {{-- AWAL BAGIAN INFORMASI PENJUAL (PENITIP) --}}
                @php
                    $penitipInfo = null; // Inisialisasi untuk keamanan
                    // Langsung gunakan variabel $barang yang merupakan objek utama halaman ini
                    // Asumsikan $barang selalu ada di view ini. Accessor 'penitip_data' akan mengembalikan null jika tidak ada penitip.
                    if (isset($barang) && $barang) { // Pastikan $barang ada dan tidak null
                        $penitipInfo = $barang->penitip_data;
                    }
                @endphp

                @if ($penitipInfo)
                    <div class="mb-6 pt-4 border-t border-gray-200">
                        <h3 class="text-md font-semibold text-gray-800 mb-2">Informasi Penjual:</h3>
                        <div class="flex items-center">
                            @if(isset($penitipInfo->foto_profil) && $penitipInfo->foto_profil)
                                <img src="{{ asset($penitipInfo->foto_profil) }}" alt="{{ $penitipInfo->nama_penitip }}"
                                    class="w-10 h-10 rounded-full mr-3 object-cover border">
                            @else
                                <div
                                    class="w-10 h-10 rounded-full mr-3 bg-gray-200 flex items-center justify-center text-gray-500 border">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <div>
                                <p class="text-lg font-medium text-gray-900">{{ $penitipInfo->nama_penitip }}</p>
                               
                                @if (isset($penitipInfo->Rating_penitip))
                                    <div class="flex items-center text-sm text-gray-600" title="Rata-rata rating penjual">
                                        <span class="text-yellow-500 mr-1">
                                            @php $roundedRating = round($penitipInfo->Rating_penitip * 2) / 2; @endphp
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $roundedRating)
                                                    <i class="fas fa-star"></i>
                                                @elseif ($i - 0.5 <= $roundedRating)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </span>
                                        <span>{{ number_format($penitipInfo->Rating_penitip, 1) }}/5.0</span>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">Belum ada rating untuk penjual ini.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mb-6 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500">Informasi penjual tidak tersedia.</p>
                    </div>
                @endif
                {{-- AKHIR BAGIAN INFORMASI PENJUAL (PENITIP) --}}

                <!-- Kotak Deskripsi Produk -->
                <!-- INI JUGA COPY -->
                <div class="mt-6 border rounded-lg p-4 bg-gray-50 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi Produk</h2>

                    <p class="text-gray-700 leading-relaxed whitespace-pre-line mb-4">
                        {{ $barang->deskripsi_barang }}
                    </p>

                    <p class="text-gray-800 font-medium">
                        Berat Barang: <span class="font-normal">{{ $barang->berat_barang }} gram</span>
                    </p>
                    @if($isElektronik)
                        <p class="text-gray-800 font-medium mt-2">
                            Garansi Hingga:
                            <span class="font-normal">
                                {{ \Carbon\Carbon::parse($barang->tanggal_garansi)->translatedFormat('d F Y') }}
                            </span>
                        </p>
                    @endif
                </div>
                <!--SAMPE INI -->
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
        function changeMainImage(element) {
            const mainImage = document.getElementById('main-image');
            mainImage.src = element.src;

            // Optional: atur border aktif
            document.querySelectorAll('.flex-shrink-0 img').forEach(img => {
                img.classList.remove('border-blue-500');
                img.classList.add('border-gray-300');
            });
            element.classList.remove('border-gray-300');
            element.classList.add('border-blue-500');
        }
        document.addEventListener("DOMContentLoaded", function () {
            const ratingContainer = document.getElementById('rating-stars-container');
            if (!ratingContainer) return;

            const rating = parseFloat(ratingContainer.dataset.rating) || 0;
            const fullStars = Math.floor(rating);
            const halfStar = rating % 1 >= 0.5;
            const totalStars = 5;

            for (let i = 0; i < fullStars; i++) {
                const star = document.createElement('i');
                star.className = 'fas fa-star text-yellow-400';
                ratingContainer.appendChild(star);
            }

            if (halfStar) {
                const half = document.createElement('i');
                half.className = 'fas fa-star-half-alt text-yellow-400';
                ratingContainer.appendChild(half);
            }

            const emptyStars = totalStars - fullStars - (halfStar ? 1 : 0);
            for (let i = 0; i < emptyStars; i++) {
                const star = document.createElement('i');
                star.className = 'far fa-star text-yellow-400';
                ratingContainer.appendChild(star);
            }
        });
        function changeMainImage(element) {
            const mainImage = document.getElementById('main-image');
            mainImage.src = element.src;

            // Optional: atur border aktif
            document.querySelectorAll('.flex-shrink-0 img').forEach(img => {
                img.classList.remove('border-blue-500');
                img.classList.add('border-gray-300');
            });
            element.classList.remove('border-gray-300');
            element.classList.add('border-blue-500');
        }
    </script>
</body>
</html>