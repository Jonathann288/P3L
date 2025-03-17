<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReUseMart</title>
    <style>
        #loading-screen {
            position: fixed;
            width: 100%;
            height: 110%;
            background: #0072FF;
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

        .dots {
            display: flex;
            justify-content: center;
            margin-top: 250px;
        }

        .dot {
            width: 10px;
            height: 10px;
            margin: 0 5px;
            background-color: white;
            border-radius: 50%;
            animation: bounce 1.5s infinite ease-in-out;
        }

        .dot:nth-child(1) {
            animation-delay: 0s;
        }

        .dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes bounce {

            0%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }
        }

    </style>
</head>

<body>

    <!-- Loading Screen -->
    <div id="loading-screen">
        <img src="{{ asset('images/logo.png') }}" alt="ReUseMart">
        <div class="dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>

    <div id="content">
        @yield('content')
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            setTimeout(function () {
                document.getElementById("loading-screen").style.display = "none";
                document.getElementById("content").style.display = "block";
            }, 3500);
        });
    </script>

</body>

</html>