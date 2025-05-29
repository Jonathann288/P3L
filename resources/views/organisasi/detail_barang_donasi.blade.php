<!-- SEMUA COPY -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReuseMart - Detail Barang Donasi</title>
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
        .thumbnail-container {
            scrollbar-width: thin;
            scrollbar-color: #3B82F6 #E5E7EB;
        }
        .thumbnail-container::-webkit-scrollbar {
            height: 6px;
        }
        .thumbnail-container::-webkit-scrollbar-thumb {
            background-color: #3B82F6;
            border-radius: 3px;
        }
    </style>
</head>
<body class="bg-gray-50">
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
                <a href="{{ route('organisasi.donasi-organisasi') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo6.png') }}" alt="ReUseMart" class="h-12">
                </a>

                <div class="hidden md:block flex-grow mx-4">
                    <input type="text" placeholder="Mau cari apa nih kamu?"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                </div>

                <!-- Cek Autentikasi -->
                @if(Auth::guard('organisasi')->check())
                    <div class="relative">
                        <button id="dropdownToggle"
                            class="bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-md hover:bg-blue-800 flex items-center space-x-2">
                            <span>{{ Auth::guard('organisasi')->user()->nama_organisasi }}</span>
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


    <main class="pb-12 px-4 max-w-6xl mx-auto">
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
                        <img id="main-image" src="{{ asset($mainImage) }}" alt="Foto Utama" class="max-h-full max-w-full object-contain">
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

            <div class="w-full md:w-1/2">

                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $barang->nama_barang }}</h1>

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
            </div>
        </div>
    </main>

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

// Image handling
        function changeMainImage(thumbElement) {
            const mainImage = document.getElementById('mainImage');
            const newSrc = thumbElement.getAttribute('data-src');
            
            // Add loading effect
            mainImage.classList.add('opacity-0');
            
            // Preload image
            const img = new Image();
            img.src = newSrc;
            img.onload = function() {
                mainImage.src = newSrc;
                mainImage.classList.remove('opacity-0');
                
                // Update active thumbnail
                document.querySelectorAll('.thumbnail-container img').forEach(img => {
                    img.classList.remove('border-blue-500');
                    img.classList.add('border-transparent');
                });
                thumbElement.classList.add('border-blue-500');
                thumbElement.classList.remove('border-transparent');
            };
            
            img.onerror = function() {
                document.getElementById('imageFallback').classList.remove('hidden');
                mainImage.classList.add('hidden');
            };
        }

        // Handle error on main image
        document.getElementById('mainImage')?.addEventListener('error', function() {
            this.classList.add('hidden');
            document.getElementById('imageFallback').classList.remove('hidden');
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