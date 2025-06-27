<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReuseMart</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <header id="navbar" class="fixed top-0 w-full z-50 bg-transparent transition-all duration-300">
        <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex-1 md:flex md:items-center md:gap-12">
                    <a class="block text-teal-600" href="#">
                        <span class="sr-only">Home</span>
                        <img src="images/logo3.png" alt="Logo" class="h-8">
                    </a>
                </div>

                <div class="md:flex md:items-center md:gap-12">
                    <nav aria-label="Global" class="hidden md:block">
                        <ul class="flex items-center gap-6 text-base">
                            <li>
                                <a class="text-gray-700 font-bold text-lg block relative after:content-[''] after:block after:w-0 after:h-1 
                                after:bg-blue-500 after:transition-all after:duration-300 hover:after:w-full" href="#">
                                    Beranda </a>
                            </li>

                            <li>
                                <a class="text-gray-700 font-bold text-lg block relative after:content-[''] after:block after:w-0 after:h-1 
                                after:bg-blue-500 after:transition-all after:duration-300 hover:after:w-full"
                                    href="{{ route('shop') }}"> Shop </a>
                            </li>

                            <li>
                                <a class="text-gray-700 font-bold text-lg block relative after:content-[''] after:block after:w-0 after:h-1 
                                after:bg-blue-500 after:transition-all after:duration-300 hover:after:w-full"
                                    href="{{ route('donasi') }}"> Donasi </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="flex items-center gap-4">
                        <div class="block md:hidden">
                            <button class="rounded-sm bg-gray-100 p-2 text-gray-600 transition hover:text-gray-600/75"
                                id="mobile-menu-button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="fixed inset-0 z-50 backdrop-blur-lg hidden ">
            <div class="flex justify-between items-center p-4 border-b border-gray-700">
                <a class="block text-teal-600" href="#">
                    <span class="sr-only">Home</span>
                    <img src="images/logo3.png" alt="Logo" class="h-8">
                </a>
                <button id="close-menu" class="text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="p-4">
                <ul class="space-y-6">
                    <li>
                        <a href="#" class="text-white font-bold text-lg block relative after:content-[''] after:block after:w-0 after:h-1 
                    after:bg-white-900 after:transition-all after:duration-300 hover:after:w-full">Beranda</a>
                    </li>

                    <li>
                        <a href="{{ route('shop') }}" class="text-white font-bold text-lg block relative after:content-[''] after:block after:w-0 after:h-1 
                    after:bg-white-900 after:transition-all after:duration-300 hover:after:w-full">Shop</a>
                    </li>

                    <li>
                        <a href="{{ route('donasi') }}" class="text-white font-bold text-lg block relative after:content-[''] after:block after:w-0 after:h-1 
                    after:bg-white-900 after:transition-all after:duration-300 hover:after:w-full">Donasi</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <!-- Carousel -->
    <div id="carouselContainer" class="relative w-full">
        <div id="carouselExample" class="relative h-56 overflow-hidden md:h-[787px]">
            <div class="block duration-700 ease-in-out relative w-full h-full">
                <video autoplay muted loop class="absolute top-0 left-0 w-full h-full object-cover">
                    <source src="{{ asset('images/videoAwal.mp4') }}" type="video/mp4">
                </video>
                <div class="absolute top-0 left-0 w-full h-full bg-black opacity-50"></div>
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                    <h1 class="text-4xl md:text-6xl font-bold text-blue-500 drop-shadow-lg mb-4">ReuseMart</h1>
                    <p class="text-base md:text-2xl font-bold text-white drop-shadow-lg">REDUCE REUSE RECYCLE</p>
                </div>
            </div>
        </div>
    </div>

    <div class="@container px-4" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="200">
        <div class="my-25">
            <h1 class="flex justify-center text-blue-500 font-bold uppercase text-center">
                TENTANG KAMI
            </h1>
            <h1 class="flex justify-center font-bold my-10 text-3xl text-center">
                Tingginya Limbah dan Konsumsi yang Tidak Berkelanjutan
            </h1>
            <p class="text-gray-700 max-w-7xl mx-auto text-center">
                Saat ini, dunia menghadapi permasalahan limbah yang semakin meningkat akibat konsumsi berlebihan dan
                penggunaan barang sekali pakai.
                Banyak produk yang masih layak pakai berakhir sebagai sampah,
                sementara daur ulang dan pemanfaatan kembali belum menjadi kebiasaan umum. Hal ini berdampak pada
                lingkungan, meningkatkan pencemaran,
                serta menguras sumber daya alam yang semakin terbatas.
            </p>

            <div class="flex flex-wrap justify-center gap-10 md:gap-20 mt-10">
                <div
                    class="bg-white shadow-lg rounded-xl p-6 text-center w-40 md:w-52 transition transform hover:scale-105 hover:shadow-[0_10px_30px_rgba(59,130,246,0.5)]">
                    <h3 class="text-4xl font-bold text-blue-500">70%</h3>
                    <p class="text-gray-600">limbah bertambah setiap tahun</p>
                </div>
                <div
                    class="bg-white shadow-lg rounded-xl p-6 text-center w-40 md:w-52 transition transform hover:scale-105 hover:shadow-[0_10px_30px_rgba(59,130,246,0.5)]">
                    <h3 class="text-4xl font-bold text-blue-500">50%</h3>
                    <p class="text-gray-600">Produk yang bisa digunakan kembali</p>
                </div>
                <div
                    class="bg-white shadow-lg rounded-xl p-6 text-center w-40 md:w-52 transition transform hover:scale-105 hover:shadow-[0_10px_30px_rgba(59,130,246,0.5)]">
                    <h3 class="text-4xl font-bold text-blue-500">9%</h3>
                    <p class="text-gray-600">Plastik berhasil didaur ulang</p>
                </div>
            </div>
        </div>
    </div>

    <div class="@container" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="200">
        <div class="my-7">
            <div class="container mx-auto px-6 py-16 flex flex-col lg:flex-row items-center gap-10 justify-center">
                <!-- Bagian Kiri: Gambar -->
                <div class="flex flex-col gap-5">
                    <div class="flex gap-5">
                        <img src="images/gambar1.jpg" alt="Gambar 1" class="w-40 md:w-56 lg:w-64 rounded-2xl shadow-md">
                    </div>
                    <div>
                        <img src="images/gambar3.jpg" alt="Gambar 2" class="w-40 md:w-56 lg:w-64 rounded-2xl shadow-md">
                    </div>
                </div>
                <div>
                    <img src="images/gambar2.jpg" alt="Gambar 3" class="w-64 md:w-80 lg:w-96 rounded-2xl shadow-md">
                </div>

                <!-- Bagian Kanan: Teks -->
                <div class="max-w-lg">
                    <h4 class="text-blue-500 font-bold uppercase">MISI KAMI</h4>
                    <h2 class="text-gray-900 font-bold text-3xl my-4">
                        REDUCE REUSE RECYCLE
                    </h2>
                    <p class="text-gray-600 text-lg mb-6">
                        ReuseMart adalah Sebuah platform yang berfokus pada pengurangan limbah, pemanfaatan kembali, dan
                        daur ulang produk-produk yang sudah tidak digunakan lagi.
                        dengan menjualnya kembali kepada masyarakat atau mendonasikan kepada pihak tertentu.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="@container" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="200">
        <div class="text-center my-10">
            <h1 class="flex justify-center text-blue-500 font-bold uppercase text-center">
                BISNIS KAMI
            </h1>
            <h1 class="text-xl md:text-2xl font-semibold max-w-4xl mx-auto my-10">
                Kami Menyedikan Platform untuk membantu masyarakat untuk membeli
                dan menitipkan barangnya serta yayasan yang mencari barang untuk digunakan.
            </h1>
        </div>

        <div class="flex flex-wrap justify-center gap-8 mt-10">
            <div
                class="bg-white shadow-md rounded-xl p-6 w-80 text-center border border-gray-200 hover:shadow-[0_10px_30px_rgba(59,130,246,0.5)]">
                <div class="w-12 h-12 flex items-center justify-center bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z" />
                    </svg>
                </div>
                <div class="mt-4 text-left">
                    <h3 class="text-lg font-bold my-2">E-Commerce</h3>
                    <p class="text-gray-600 text-sm">
                        ReUseMart menyediakan layanan E-Commerce dengan menyediakan berbagai produk bekas yang masih
                        bisa digunakan dengan harga yang pass dikantong.
                    </p>
                </div>
            </div>

            <div
                class="bg-white shadow-md rounded-xl p-6 w-80 text-center border border-gray-200 hover:shadow-[0_10px_30px_rgba(59,130,246,0.5)]">
                <div class="w-12 h-12 flex items-center justify-center bg-blue-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                </div>
                <div class="mt-4 text-left">
                    <h3 class="text-lg font-bold mt-4">Pengiriman dan Pengambilan</h3>
                    <p class="text-gray-600 mt-2">
                        Masyarakat bisa memilih untuk barang dikirim kerumah atau bisa mengambilnya secara sendiri
                        digudang ReUseMart.
                    </p>
                </div>
            </div>

            <div
                class="bg-white shadow-md rounded-xl p-6 w-80 text-center border border-gray-200 hover:shadow-[0_10px_30px_rgba(59,130,246,0.5)]">
                <div class="w-12 h-12 flex items-center justify-center bg-blue-100 rounded-full">
                    <img src="images/charity.png" alt="Icon 3" class="w-6 h-6 text-blue-500">
                </div>
                <div class="mt-4 text-left">
                    <h3 class="text-lg font-bold mt-4">Donasi</h3>
                    <p class="text-gray-600 mt-2">
                        Barang-barang yang tidak terjual atau penitip ingin mendonasikan kepada yayasan yang
                        membutuhkan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="@container">
        <div class="my-40" data-aos="fade-down" data-aos-duration="1000" data-aos-delay="200">
            <div class="text-center">
                <h1 class="flex justify-center text-blue-500 font-bold uppercase text-center">
                    KATEGORI BARANG
                </h1>
                <h1 class="text-xl md:text-2xl font-semibold max-w-4xl mx-auto my-10">
                    Beberapa Kategori yang ada di ReUseMart.
                </h1>
            </div>

            <div class="my-10 justify-items-center text-center" data-aos="fade-down" data-aos-duration="1000"
                data-aos-delay="200" data-aos-anchor-placement="top-bottom">
                <swiper-container class="mySwiper" effect="cards" grab-cursor="true">
                    <swiper-slide>
                        <div class="w-22 h-22 flex items-center justify-center bg-blue-100 rounded-full">
                            <img src="images/gadgets.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        Elektronik & Gadget
                    </swiper-slide>
                    <swiper-slide>
                        <div class="w-22 h-22 flex items-center justify-center bg-blue-100 rounded-full">
                            <img src="images/shopping.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        Pakaian & Aksesori
                    </swiper-slide>
                    <swiper-slide>
                        <div class="w-22 h-22 flex items-center justify-center bg-blue-100 rounded-full">
                            <img src="images/electric-appliances.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        Perabotan Rumah Tangga
                    </swiper-slide>
                    <swiper-slide>
                        <div class="w-22 h-22 flex items-center justify-center bg-blue-100 rounded-full">
                            <img src="images/stationery.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        Buku, Alat Tulis, & Peralatan Sekolah
                    </swiper-slide>
                    <swiper-slide>
                        <div class="w-22 h-22 flex items-center justify-center bg-blue-100 rounded-full">
                            <img src="images/hobbies.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        Hobi, Mainan, & Koleksi
                    </swiper-slide>
                    <swiper-slide>
                        <div class="w-22 h-22 flex items-center justify-center bg-blue-100 rounded-full">
                            <img src="images/stroller.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        Perlengkapan Bayi & Anak
                    </swiper-slide>
                    <swiper-slide>
                        <div class="w-22 h-22 flex items-center justify-center bg-blue-100 rounded-full">
                            <img src="images/gadgets.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        Otomotif & Aksesori
                    </swiper-slide>
                    <swiper-slide>
                        <div class="w-22 h-22 flex items-center justify-center bg-blue-100 rounded-full">
                            <img src="images/sport-car.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        Perlengkapan Taman & Outdoor
                    </swiper-slide>
                    <swiper-slide>
                        <div class="w-22 h-22 flex items-center justify-center bg-blue-100 rounded-full">
                            <img src="images/workspace.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        Peralatan Kantor & Industri
                    </swiper-slide>
                    <swiper-slide>
                        <div class="w-22 h-22 flex items-center justify-center bg-blue-100 rounded-full">
                            <img src="images/cosmetics.png" alt="Icon 3" class="w-12 h-12">
                        </div>
                        Kosmetik & Perawatan Diri
                    </swiper-slide>
                </swiper-container>
            </div>
        </div>
    </div>

    <footer class="bg-white dark:bg-blue-500 ">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                    <a href="https://flowbite.com/" class="flex items-center">
                        <img src="images/logo6.png" class="h-30 me-3" alt="FlowBite Logo" />
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Resources</h2>
                        <ul class="text-gray-500 dark:text-gray-400 font-medium">
                            <li class="mb-4">
                                <!-- nanti ganti ke link shop -->
                                <a href="http://127.0.0.1:8000/shop" class="hover:underline text-white">Shop</a>
                            </li>
                            <li>
                                <!-- nanti ganti ke link shop -->
                                <a href="http://127.0.0.1:8000/donasi" class="hover:underline text-white">Donasi</a>
                            </li>

                            <li>
                                <a href="{{ route('testing') }}" class="text-white font-bold text-lg block relative after:content-[''] after:block after:w-0 after:h-1 
                    after:bg-white-900 after:transition-all after:duration-300 hover:after:w-full">testing</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Follow us</h2>
                        <ul class="flex space-x-4 text-gray-500 dark:text-gray-400">
                            <li>
                                <a href="https://instagram.com" class="hover:text-gray-700">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor" fill-rule="evenodd"
                                            d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://facebook.com" class="hover:text-gray-700">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path fill-rule="evenodd"
                                            d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://youtube.com" class="hover:text-red-500">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path fill-rule="evenodd"
                                            d="M21.7 8.037a4.26 4.26 0 0 0-.789-1.964 2.84 2.84 0 0 0-1.984-.839c-2.767-.2-6.926-.2-6.926-.2s-4.157 0-6.928.2a2.836 2.836 0 0 0-1.983.839 4.225 4.225 0 0 0-.79 1.965 30.146 30.146 0 0 0-.2 3.206v1.5a30.12 30.12 0 0 0 .2 3.206c.094.712.364 1.39.784 1.972.604.536 1.38.837 2.187.848 1.583.151 6.731.2 6.731.2s4.161 0 6.928-.2a2.844 2.844 0 0 0 1.985-.84 4.27 4.27 0 0 0 .787-1.965 30.12 30.12 0 0 0 .2-3.206v-1.516a30.672 30.672 0 0 0-.202-3.206Zm-11.692 6.554v-5.62l5.4 2.819-5.4 2.801Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-black-200 sm:mx-auto dark:border-black-700 lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-white sm:text-center dark:text-white">© 2025 <a href="https://flowbite.com/"
                        class="hover:underline">ReUseMart</a>. All Rights Reserved.
                </span>
                <div class="flex items-center mt-4 sm:mt-0 sm:gap-2">
                    <h5 class="text-white whitespace-nowrap">Jangan Lupa unduh kami di</h5>
                    <img src="images/playstore.png" class="h-10" alt="Google Play">
                </div>
            </div>
        </div>
    </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init();
</script>
<script>
    // JavaScript untuk menampilkan/menyembunyikan menu mobile
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeMenuButton = document.getElementById('close-menu');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', function () {
            mobileMenu.classList.remove('hidden');
        });

        closeMenuButton.addEventListener('click', function () {
            mobileMenu.classList.add('hidden');
        });
    });
    window.addEventListener('scroll', function () {
        var navbar = document.getElementById("navbar");
        var carouselContainer = document.getElementById("carouselContainer");
        var scrollPosition = window.scrollY;

        if (scrollPosition > carouselContainer.offsetHeight - 100) {
            navbar.classList.remove("bg-transparent");
            navbar.classList.add("bg-white", "shadow-md");
        } else {
            navbar.classList.add("bg-transparent");
            navbar.classList.remove("bg-white", "shadow-md");
        }
    });
</script>

</html>