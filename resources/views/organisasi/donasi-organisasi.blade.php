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
                    <a href="{{ route('donasi')}}" class="hover:underline">Donasi</a>
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

        <!-- Improved Carousel -->
        <div id="donation-carousel" class="relative w-full" data-carousel="slide">
            <!-- Carousel wrapper -->
            <div class="relative h-56 overflow-hidden md:h-96">
                <!-- Item 1 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img src="{{ asset('images/donasiPic1.png') }}"
                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                        alt="Donation Banner 3">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="bg-black bg-opacity-50 p-4 rounded-lg text-white text-center max-w-lg">
                            <h2 class="text-2xl font-bold mb-2">Request Barang</h2>
                            <p class="mb-4">Tidak menemukan yang Anda cari? Ajukan permintaan barang</p>
                            <button class="bg-orange-500 hover:bg-orange-600 text-white py-2 px-6 rounded-full font-bold"
                                onclick="window.location.href='{{ route('requestBarang') }}'">
                                Request Sekarang / sudah login
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Item 2 -->
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <img src="{{ asset('images/donasiPic1.png') }}"
                        class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2"
                        alt="Donation Banner 1">
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

            <div class="my-6 px-4">
                <div class="py-2 max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                    id="donation-container">

                </div>
            </div>



            <!-- Script Toggle Menu -->
            <script>

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
                document.getElementById("menu-toggle").addEventListener("click", function () {
                    document.getElementById("mobile-menu").classList.toggle("hidden");
                });

                document.addEventListener("DOMContentLoaded", function () {
                    document.getElementById("content").style.display = "block";
                });

                const donationItems = [
                    { name: "Sepatu adidas - Ukuran 42", condition: "Bekas - Baik", location: "Jakarta Selatan", img: "https://images.unsplash.com/flagged/photo-1556637640-2c80d3201be8?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8M3x8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" },
                    { name: "Jaket Denim - Size L", condition: "Bekas - Sangat Baik", location: "Bandung", img: "https://images.unsplash.com/photo-1551537482-f2075a1d41f2?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" },
                    { name: "Smartphone Samsung", condition: "Bekas - Baik", location: "Surabaya", img: "https://images.unsplash.com/photo-1598327105666-5b89351aff97?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" },
                    { name: "Buku Novel Fiksi", condition: "Bekas - Sangat Baik", location: "Jakarta Pusat", img: "https://images.unsplash.com/photo-1544947950-fa07a98d237f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" },
                    { name: "Meja Belajar Minimalis", condition: "Bekas - Baik", location: "Depok", img: "https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" },
                    { name: "Sepatu Nike - ukuran 38", condition: "Bekas - Sangat Baik", location: "Tangerang", img: "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8OHx8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" },
                    { name: "Kursi Kantor Ergonomis", condition: "Bekas - Baik", location: "Jakarta Timur", img: "https://images.unsplash.com/photo-1505843513577-22bb7d21e455?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" },
                    { name: "Alat Masak Set", condition: "Bekas - Sangat Baik", location: "Jakarta Barat", img: "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8OHx8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" }
                ];

                const container = document.getElementById("donation-container");
                donationItems.forEach(item => {
                    const card = `
                        <div class="flex flex-col overflow-hidden rounded-lg border border-gray-100 bg-white shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2" 
                                onmouseover="this.querySelector('.card-image').classList.add('scale-110'); this.querySelector('.request-btn').classList.add('bg-blue-700'); this.querySelector('.request-btn').classList.remove('bg-blue-600');" 
                                onmouseout="this.querySelector('.card-image').classList.remove('scale-110'); this.querySelector('.request-btn').classList.remove('bg-blue-700'); this.querySelector('.request-btn').classList.add('bg-blue-600');">
                            <a class="relative flex h-40 overflow-hidden rounded-xl" href="#">
                                <img class="absolute top-0 right-0 h-full w-full object-cover transition-transform duration-500 card-image" src="${item.img}" alt="${item.name}" />
                                <div class="absolute bottom-0 left-0 bg-gradient-to-t from-black to-transparent p-2 w-full">
                                    <div class="inline-flex items-center rounded-lg bg-white px-2 py-1 text-xs font-semibold text-gray-900 transform hover:scale-105 transition-transform">
                                        ${item.condition}
                                    </div>
                                </div>
                            </a>
                            <div class="flex flex-col p-3">
                                <a href="#">
                                    <h5 class="text-base font-semibold tracking-tight text-slate-900 hover:text-blue-600 transition-colors">${item.name}</h5>
                                </a>
                                <div class="mt-1 flex items-center">
                                    <span class="text-sm text-gray-600">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        ${item.location}
                                    </span>
                                </div>
                                <div class="mt-3">
                                    <a href="#" class="flex items-center justify-center rounded-md bg-gray-900 px-3 py-2 text-center text-sm font-medium text-white hover:bg-black transition-colors request-btn transform hover:scale-105">
                                        Minta Barang
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                    container.innerHTML += card;
                });

                document.head.insertAdjacentHTML('beforeend', `
                    <style>
                        /* Orange Request Button Animation */
                        .request-now-btn {
                            position: relative;
                            overflow: hidden;
                            transition: all 0.3s ease;
                            box-shadow: 0 4px 6px rgba(234, 88, 12, 0.3);
                        }

                        /* Hover state */
                        .request-now-btn:hover {
                            transform: translateY(-3px);
                            box-shadow: 0 8px 15px rgba(234, 88, 12, 0.4);
                        }

                        /* Active/click state */
                        .request-now-btn:active {
                            transform: translateY(1px);
                            box-shadow: 0 2px 4px rgba(234, 88, 12, 0.5);
                        }

                        /* Shine effect animation */
                        .request-now-btn::before {
                            content: '';
                            position: absolute;
                            top: 0;
                            left: -100%;
                            width: 50%;
                            height: 100%;
                            background: linear-gradient(
                                to right,
                                rgba(255, 255, 255, 0) 0%,
                                rgba(255, 255, 255, 0.3) 50%,
                                rgba(255, 255, 255, 0) 100%
                            );
                            transform: skewX(-25deg);
                            transition: all 0.75s;
                        }

                        .request-now-btn:hover::before {
                            left: 150%;
                        }

                        /* Pulse animation */
                        @keyframes orangePulse {
                            0% {
                                box-shadow: 0 0 0 0 rgba(234, 88, 12, 0.7);
                            }
                            70% {
                                box-shadow: 0 0 0 15px rgba(234, 88, 12, 0);
                            }
                            100% {
                                box-shadow: 0 0 0 0 rgba(234, 88, 12, 0);
                            }
                        }

                        /* Apply animation after a delay */
                        .animate-pulse-orange {
                            animation: orangePulse 2s infinite;
                        }
                    </style>
                    `);

            
                document.addEventListener('DOMContentLoaded', function () {
                    // Find all orange request buttons
                    const orangeButtons = document.querySelectorAll('.bg-orange-500');

                    orangeButtons.forEach(button => {
                        // Add our custom class
                        button.classList.add('request-now-btn');

                        // Set up animations
                        setTimeout(() => {
                            button.classList.add('animate-pulse-orange');
                        }, 2000); // Start pulsing after 2 seconds

                        // Add click effect
                        button.addEventListener('click', function (e) {
                            // Create ripple element
                            const ripple = document.createElement('span');
                            ripple.style.position = 'absolute';
                            ripple.style.backgroundColor = 'rgba(255, 255, 255, 0.4)';
                            ripple.style.borderRadius = '50%';
                            ripple.style.pointerEvents = 'none';
                            ripple.style.transform = 'scale(0)';
                            ripple.style.transition = 'transform 0.6s, opacity 0.6s';

                            // Position the ripple at click position
                            const rect = button.getBoundingClientRect();
                            const size = Math.max(rect.width, rect.height) * 2;
                            const x = e.clientX - rect.left - size / 2;
                            const y = e.clientY - rect.top - size / 2;

                            ripple.style.width = ripple.style.height = `${size}px`;
                            ripple.style.left = `${x}px`;
                            ripple.style.top = `${y}px`;

                            // Make sure button has position relative
                            if (getComputedStyle(button).position === 'static') {
                                button.style.position = 'relative';
                            }

                            button.appendChild(ripple);

                            // Animate the ripple
                            setTimeout(() => {
                                ripple.style.transform = 'scale(1)';
                                ripple.style.opacity = '0';

                                // Remove the ripple element after the animation completes
                                setTimeout(() => {
                                    if (button.contains(ripple)) {
                                        button.removeChild(ripple);
                                    }
                                }, 600);
                            }, 10);
                        });
                    });
                });

                // Update button HTML with more visual elements
                document.addEventListener('DOMContentLoaded', function () {
                    const orangeButtons = document.querySelectorAll('.bg-orange-500');

                    orangeButtons.forEach(button => {
                        // Get the original text
                        const originalText = button.textContent.trim();

                        // Update the button content with icon
                        button.innerHTML = `
                                <div class="flex items-center justify-center">
                                    <span>${originalText}</span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </div>
                            `;
                    });
                });

                // Initialize the carousel
                const carousel = document.getElementById('donation-carousel');
                let currentSlide = 0;
                const slides = carousel.querySelectorAll('[data-carousel-item]');
                const totalSlides = slides.length;
                const indicators = carousel.querySelectorAll('[data-carousel-slide-to]');
                const prevButton = carousel.querySelector('[data-carousel-prev]');
                const nextButton = carousel.querySelector('[data-carousel-next]');

                // Function to show a specific slide
                function showSlide(index) {
                    // Hide all slides
                    slides.forEach(slide => {
                        slide.classList.add('hidden');
                        slide.classList.remove('block');
                    });

                    // Show the selected slide
                    slides[index].classList.remove('hidden');
                    slides[index].classList.add('block');

                    // Update indicators
                    indicators.forEach((indicator, idx) => {
                        if (idx === index) {
                            indicator.setAttribute('aria-current', 'true');
                            indicator.classList.add('bg-white');
                            indicator.classList.remove('bg-white/50');
                        } else {
                            indicator.setAttribute('aria-current', 'false');
                            indicator.classList.remove('bg-white');
                            indicator.classList.add('bg-white/50');
                        }
                    });

                    currentSlide = index;
                }

                showSlide(0);
            </script>
        </div>
@endsection