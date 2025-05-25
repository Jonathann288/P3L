<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReUseMart</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            overflow-y: auto;
        }

        #loading-screen {
            position: fixed;
            width: 100%;
            height: 100vh;
            background: linear-gradient(90deg, #0072FF, #00C6FF);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        #content {
            display: none;
        }

        #loading-screen img {
            width: 100%;
            max-width: 500px;
            height: auto;
        }

        .loader {
            width: 15px;
            aspect-ratio: 1;
            border-radius: 50%;
            background-color: #ffffff;
            box-shadow: #ffffff -20px 0px, #ffffff 20px 0px;
            animation: l18 1.2s infinite;
        }

        @keyframes l18 {
            25% {
                box-shadow: #ffffff -15px -15px, #ffffff 15px 15px;
            }

            50% {
                box-shadow: #ffffff 0px -20px, #ffffff 0px 20px;
            }

            75% {
                box-shadow: #ffffff 15px -15px, #ffffff -15px 15px;
            }

            100% {
                box-shadow: #ffffff 20px 0px, #ffffff -20px 0px;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s forwards;
        }

        .fade-out {
            animation: fadeOut 0.5s forwards;
        }
    </style>
</head>

<body>
    <!-- Loading Screen -->
    <div id="loading-screen">
        <img src="{{ asset('images/logo.png') }}" alt="ReUseMart">
        <div class="loader"></div>
    </div>

    @yield('content')

    <footer class="bg-white dark:bg-blue-500 ">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                    <a href="https://flowbite.com/" class="flex items-center">
                        <img src="{{ asset('images/logo6.png') }}" class="h-22 me-3" alt="FlowBite Logo" />
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
                            <li class="mb-4">
                                <!-- nanti ganti ke link shop -->
                                <a href="http://127.0.0.1:8000/donasi" class="hover:underline text-white">Donasi</a>
                            </li>
                            <li>
                                <!-- nanti ganti ke link shop -->
                                <a href="http://127.0.0.1:8000/" class="hover:underline text-white">Beranda</a>
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
                    <img src="{{ asset('images/playstore.png') }}" class="h-10" alt="Google Play">
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            setTimeout(function () {
                document.getElementById("loading-screen").style.display = "none";
                document.getElementById("content").style.display = "block";
            }, 1000);
        });

    </script>
</body>

</html>