@extends('layouts.loading')
@section('content')
<div id="content" style="display: none;" class="min-h-screen"">
    <nav class="bg-blue-600 p-4">
        <div class="container mx-auto flex items-center justify-between flex-wrap">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo6.png') }}" alt="ReUseMart" class="h-12">
            </div>

            <!-- Input Pencarian -->
            <div class="flex-grow mx-4 hidden md:block">
                <input type="text" placeholder="Mau cari apa nih kamu?" 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
            </div>

            <!-- Tombol Login & Sign In -->
            <div class="flex space-x-2">
                <button class="bg-blue-700 text-white px-4 py-2 rounded-lg font-bold shadow-md transition-all hover:bg-blue-800 active:bg-blue-900">
                    Log In
                </button>
                <button class="bg-white text-black px-4 py-2 rounded-lg font-bold shadow-md transition-all hover:bg-gray-200 active:bg-gray-300">
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
            <!-- <button class="bg-blue-700 text-white w-full px-4 py-2 rounded-lg font-bold shadow-md transition-all hover:bg-blue-800 active:bg-blue-900">
                Log In
            </button>
            <button class="bg-white text-black w-full px-4 py-2 rounded-lg font-bold shadow-md transition-all hover:bg-gray-200 active:bg-gray-300 mt-2">
                Sign In
            </button> -->
        </div>
    </nav>

    <div class="my-5 px-4">
        <div class="bg-blue-600 rounded-lg p-4 w-full max-w-6xl mx-auto">
            <!-- Wrapper Scroll untuk Mobile -->
            <div class="overflow-x-auto md:overflow-hidden pl-4 pr-4">
                <div class="flex md:grid md:grid-cols-5 gap-6 text-center">
                    <!-- Kategori -->
                    <div class="flex flex-col items-center min-w-[25%] md:min-w-0">
                        <div class="w-16 h-16 bg-white rounded-md flex items-center justify-center">
                            <img src="images/gadgets.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        <p class="text-white mt-2 text-sm">Elektronik & Gadget</p>
                    </div>
                    
                    <div class="flex flex-col items-center min-w-[25%] md:min-w-0">
                        <div class="w-16 h-16 bg-white rounded-md flex items-center justify-center">
                            <img src="images/shopping.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        <p class="text-white mt-2 text-sm">Pakaian & Aksesoris</p>
                    </div>

                    <div class="flex flex-col items-center min-w-[25%] md:min-w-0">
                        <div class="w-16 h-16 bg-white rounded-md flex items-center justify-center">
                            <img src="images/electric-appliances.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        <p class="text-white mt-2 text-sm">Perabotan Rumah Tangga</p>
                    </div>

                    <div class="flex flex-col items-center min-w-[25%] md:min-w-0">
                        <div class="w-16 h-16 bg-white rounded-md flex items-center justify-center">
                            <img src="images/stationery.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        <p class="text-white mt-2 text-sm">Buku, Alat Tulis, & Peralatan Sekolah</p>
                    </div>

                    <div class="flex flex-col items-center min-w-[25%] md:min-w-0">
                        <div class="w-16 h-16 bg-white rounded-md flex items-center justify-center">
                            <img src="images/hobbies.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        <p class="text-white mt-2 text-sm">Hobi, Mainan, & Koleksi</p>
                    </div>

                    <div class="flex flex-col items-center min-w-[25%] md:min-w-0">
                        <div class="w-16 h-16 bg-white rounded-md flex items-center justify-center">
                            <img src="images/stroller.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        <p class="text-white mt-2 text-sm">Perlengkapan Bayi & Anak</p>
                    </div>

                    <div class="flex flex-col items-center min-w-[25%] md:min-w-0">
                        <div class="w-16 h-16 bg-white rounded-md flex items-center justify-center">
                            <img src="images/gadgets.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        <p class="text-white mt-2 text-sm">Otomotif & Aksesoris</p>
                    </div>

                    <div class="flex flex-col items-center min-w-[25%] md:min-w-0">
                        <div class="w-16 h-16 bg-white rounded-md flex items-center justify-center">
                            <img src="images/sport-car.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        <p class="text-white mt-2 text-sm">Perlengkapan Taman & Outdoor</p>
                    </div>

                    <div class="flex flex-col items-center min-w-[25%] md:min-w-0">
                        <div class="w-16 h-16 bg-white rounded-md flex items-center justify-center">
                            <img src="images/workspace.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        <p class="text-white mt-2 text-sm">Peralatan Kantor & Industri</p>
                    </div>

                    <div class="flex flex-col items-center min-w-[25%] md:min-w-0">
                        <div class="w-16 h-16 bg-white rounded-md flex items-center justify-center">
                            <img src="images/cosmetics.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        <p class="text-white mt-2 text-sm">Kosmetik & Perawatan Diri</p>
                    </div>
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
        <div class="py-2 max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4" id="product-container">
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

    const products = [
        { name: "Nike Air MX Super 2500 - Red", price: "$449", oldPrice: "$699", img: "https://images.unsplash.com/flagged/photo-1556637640-2c80d3201be8?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8M3x8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60?a=b" },
        { name: "Nike Air Zoom - Blue", price: "$399", oldPrice: "$599", img: "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8OHx8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" },
        { name: "Nike Revolution 5 - Black", price: "$299", oldPrice: "$499", img: "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8OHx8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" },
        { name: "Nike Pegasus - Green", price: "$359", oldPrice: "$579", img: "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8OHx8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" },
        { name: "Nike Free Run - White", price: "$289", oldPrice: "$479", img: "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8OHx8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" },
        { name: "Nike Free Run - White", price: "$289", oldPrice: "$479", img: "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8OHx8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" },
        { name: "Nike Air MX Super 2500 - Red", price: "$449", oldPrice: "$699", img: "https://images.unsplash.com/flagged/photo-1556637640-2c80d3201be8?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8M3x8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60?a=b" },
        { name: "Nike Air Zoom - Blue", price: "$399", oldPrice: "$599", img: "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8OHx8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" },
        { name: "Nike Revolution 5 - Black", price: "$299", oldPrice: "$499", img: "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8OHx8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" },
        { name: "Nike Pegasus - Green", price: "$359", oldPrice: "$579", img: "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8OHx8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" },
        { name: "Nike Free Run - White", price: "$289", oldPrice: "$479", img: "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8OHx8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" },
        { name: "Nike Free Run - White", price: "$289", oldPrice: "$479", img: "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8OHx8c25lYWtlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" }
    ];

    const container = document.getElementById("product-container");
    products.forEach(product => {
        const card = `
            <div class="group flex w-full max-w-[450px] flex-col overflow-hidden rounded-lg border border-gray-100 bg-white shadow-md p-3 h-full">
                <a class="relative flex h-36 overflow-hidden rounded-xl" href="#">
                    <img class="absolute top-0 right-0 h-full w-full object-cover" src="${product.img}" alt="product image" />
                </a>
                <div class="flex flex-col flex-grow mt-2 px-2 pb-2">
                    <a href="#">
                        <h5 class="text-base tracking-tight text-slate-900">${product.name}</h5>
                    </a>
                    <div class="mt-1 mb-2 flex items-center justify-between">
                        <p>
                            <span class="text-xl font-bold text-slate-900">${product.price}</span>
                            <span class="text-xs text-slate-900 line-through">${product.oldPrice}</span>
                        </p>
                    </div>
                    <div class="mt-auto">
                        <a href="#" class="flex items-center justify-center rounded-md bg-slate-900 px-3 py-1 text-center text-xs font-medium text-white hover:bg-gray-700">
                            Add to cart
                        </a>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += card;
    });
</script>
@endsection
