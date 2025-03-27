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
    </style>
</head>

<body>
    <!-- Loading Screen -->
    <div id="loading-screen">
        <img src="{{ asset('images/logo.png') }}" alt="ReUseMart">
        <div class="loader"></div>
    </div>

    <div id="content">
        @yield('content')
    </div>

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