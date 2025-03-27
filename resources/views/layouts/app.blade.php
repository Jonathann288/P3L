<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReUseMart</title>
    <link rel="icon" type="image/png" sizes="128x128" href="images/logo2.png">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            overflow: hidden;
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
    </style>
</head>

<body>
    <!-- Loading Screen -->
    <div id="loading-screen">
        <img src="{{ asset('images/logo.png') }}" alt="ReUseMart">
        <div class="loader"></div>
    </div>

    @yield('content')

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