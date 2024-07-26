<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('./favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('./favicon/favicon-16x16.png') }}">



    <title>Meson - মেসন </title>
    <meta name="title" content="Meson is an uprising E-learning platform focusing on HSC & Admission preparation." />
    <meta name="description" content="Meson is an uprising E-learning platform focusing on HSC & Admission preparation. তোমদের HSC এবং এডমিশন প্রস্তুতিকে সহজতর করতে আমরা আছি ''মেসন'' টিম।" />
    <meta property="og:image" content="https://mesonacademy.com/image/logo2.png" />
    <meta name="og:title" content="Meson is an uprising E-learning platform focusing on HSC & Admission preparation." />
    <meta name="og:description" content="Meson is an uprising E-learning platform focusing on HSC & Admission preparation. তোমদের HSC এবং এডমিশন প্রস্তুতিকে সহজতর করতে আমরা আছি ''মেসন'' টিম।" />




    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @layer  {
            .overlay{
                left: 37%;
                right: 50%;
                filter: blur(100px);
                bottom: 8rem;
                z-index: 1;
                background: #0000ff30;
            }
        }
    </style>

</head>
<body>
<nav class="fixed top-0 w-full backdrop-blur-sm bg-gray-50/20 border-b border-gray-100/40 py-5 z-20">
    <div class="container mx-auto flex justify-between px-3 lg:px-0">
        <a href="/">
            <img class="w-32 lg:w-52 h-auto" src="./image/white-logo.png" alt="">
        </a>
        <div class="flex items-center gap-3  lg:gap-5">
            @if(Auth::check())
                @if(Auth::user()->role == 'admin')
                    <a href='/panel/dashboard' class="block border border-gray-100  hover:bg-amber-500 transition-all ease-in-out duration-300 py-1 px-2 lg:py-2 lg:px-10 rounded-full text-white hover:border-amber-500 text-sm lg:text-lg">Dashboard</a>
                @elseif(Auth::user()->role == 'student')
                    <a href='/dashboard' class="block border border-gray-100  hover:bg-amber-500 transition-all ease-in-out duration-300 py-1 px-2 lg:py-2 lg:px-10 rounded-full text-white hover:border-amber-500 text-sm lg:text-lg">Dashboard</a>
                @endif

                <a href='/logout' class="block border border-amber-500 py-1 px-2 lg:py-2 lg:px-10 rounded-full text-white bg-amber-500 text-sm lg:text-lg">Logout</a>
            @else
                <a href='/register' class="block border border-gray-100  hover:bg-amber-500 transition-all ease-in-out duration-300 py-1 px-2 lg:py-2 lg:px-10 rounded-full text-white hover:border-amber-500 text-sm lg:text-lg">Registration</a>
                <a href='/login' class="block border border-amber-500 py-1 px-2 lg:py-2 lg:px-10 rounded-full text-white bg-amber-500 text-sm lg:text-lg">Login</a>
            @endif
        </div>
    </div>
</nav>
@yield('content')
<footer class="bg-slate-950 py-20">
    <div class="container mx-auto flex flex-col items-center  px-3 lg:px-0">
        <div class="mb-10">
            <img class="w-24 h-auto" src="./image/logo2.png" alt="">
        </div>
        <div>
            <p class="text-sm lg:text-xl text-white tracking-normal text-center">Meson is an uprising E-learning platform focusing on HSC & Admission preparation.</p>
        </div>
    </div>
</footer>
</body>
</html>
