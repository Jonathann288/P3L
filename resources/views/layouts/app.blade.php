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

<<<<<<< HEAD
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
=======
        .pl,
        .pl__worm {
        animation-duration: 3s;
        animation-iteration-count: infinite;
        }

        .pl {
        animation-name: bump9;
        animation-timing-function: linear;
        width: 8em;
        height: 8em;
        }

        .pl__ring {
        stroke: hsla(var(--hue),10%,10%,0.1);
        transition: stroke 0.3s;
        }

        .pl__worm {
        animation-name: worm9;
        animation-timing-function: cubic-bezier(0.42,0.17,0.75,0.83);
        }

        /* Animations */
        @keyframes bump9 {
        from,
        42%,
        46%,
        51%,
        55%,
        59%,
        63%,
        67%,
        71%,
        74%,
        78%,
        81%,
        85%,
        88%,
        92%,
        to {
            transform: translate(0,0);
        }

        44% {
            transform: translate(1.33%,6.75%);
        }

        53% {
            transform: translate(-16.67%,-0.54%);
        }

        61% {
            transform: translate(3.66%,-2.46%);
        }

        69% {
            transform: translate(-0.59%,15.27%);
        }

        76% {
            transform: translate(-1.92%,-4.68%);
        }

        83% {
            transform: translate(9.38%,0.96%);
        }

        90% {
            transform: translate(-4.55%,1.98%);
        }
        }

        @keyframes worm9 {
        from {
            stroke-dashoffset: 10;
        }

        25% {
            stroke-dashoffset: 295;
        }

        to {
            stroke-dashoffset: 1165;
        }
>>>>>>> 7490b42cee78c2bb391f49953cd63ef2b429d01a
        }
    </style>
</head>

<body>
    <!-- Loading Screen -->
    <div id="loading-screen">
        <img src="{{ asset('images/logo.png') }}" alt="ReUseMart">
<<<<<<< HEAD
        <div class="loader"></div>
=======
        <div>
            <svg class="pl" viewBox="0 0 128 128" width="128px" height="128px" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="pl-grad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="hsl(193,90%,55%)"></stop>
                        <stop offset="100%" stop-color="hsl(223,90%,55%)"></stop>
                    </linearGradient>
                </defs>
                <circle class="pl__ring" r="56" cx="64" cy="64" fill="none" stroke="hsla(0,10%,10%,0.1)" stroke-width="16" stroke-linecap="round"></circle>
                <path class="pl__worm" d="M92,15.492S78.194,4.967,66.743,16.887c-17.231,17.938-28.26,96.974-28.26,96.974L119.85,59.892l-99-31.588,57.528,89.832L97.8,19.349,13.636,88.51l89.012,16.015S81.908,38.332,66.1,22.337C50.114,6.156,36,15.492,36,15.492a56,56,0,1,0,56,0Z" fill="none" stroke="url(#pl-grad)" stroke-width="16" stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="44 1111" stroke-dashoffset="10"></path>
            </svg>
        </div>
>>>>>>> 7490b42cee78c2bb391f49953cd63ef2b429d01a
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            setTimeout(function () {
<<<<<<< HEAD
                document.getElementById("loading-screen").style.display = "none";
                document.getElementById("content").style.display = "block";
            }, 1000);
=======
                document.getElementById("loading-screen").style.display = "flex";
                window.location.href = "/home";
            }, 3500);
>>>>>>> 7490b42cee78c2bb391f49953cd63ef2b429d01a
        });
    </script>
</body>

</html>