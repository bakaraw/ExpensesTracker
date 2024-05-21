<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" className="!scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('fontawesome-free-5.15.4-web/css/all.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/scroll-animation.css')}}">


    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-800 bg-gray-">

    <header class="bg-white shadow-md w-full py-4">
        <div class="max-w-screen-xl mx-auto flex justify-between items-center px-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>
            </div>
            <div class="flex-grow"></div>
            <div class="flex items-center space-x-3">
                @if (Route::has('login'))
                <nav class="flex space-x-3">
                    @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-5 py-2 rounded-lg bg-amber-400 text-white hover:bg-yellow-600">
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                        class="px-5 py-2 rounded-lg bg-white border border-yellow-500 text-yellow-500 tracking-tight hover:bg-yellow-50">
                        Log in
                    </a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="px-5 py-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600">
                        Register
                    </a>
                    @endif
                    @endauth
                </nav>
                @endif
            </div>
        </div>
    </header>

    <div class="py-7 mt-1 bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="h-full flex flex-col lg:flex-row justify-between items-center transition-all">
                <section class="hidden-section">
                    <div class="text-center lg:text-left mb-6 lg:mb-0">
                        <h1 class="font-black text-3xl lg:text-4xl lg:w-[400px] text-gray-800">
                            Track your way to wealth with CashTrail
                        </h1>
                        <p class="text-lg lg:w-96 leading-normal mt-4 mb-8">
                            Your All-in-One Budgeting and Tracking Companion. Stay on Course, Reach Your Financial
                            Goals.
                        </p>
                        <a href="{{route('dashboard')}}"
                            class="bg-amber-400 hover:bg-amber-500  active:bg-orange-400 px-5 py-3 rounded-lg text-gray-800 shadow-md transition">
                            Start tracking
                        </a>
                    </div>
                </section>
                <section class="hidden-section">
                    <div class="w-[750px] h-[630px] overflow-hidden">
                        <img src="{{ URL('imgs/art_CashTrail.png') }}" alt="CashTrail Illustration"
                             class="object-cover w-full h-full">
                      </div>

                </section>

            </div>
        </div>
    </div>

    <div class="bg-gray-700 h-auto">
        <div class="py-7 mt-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center hidden-section mt-10">
                <div class="text-center">
                    <h1 class="text-white font-bold text-4xl mb-2 uppercase">
                        Your wallet's best friend
                    </h1>
                    <p class="text-white">
                        All your money needs, handled by your CashTrail companion!
                    </p>

                </div>
            </div>
            <div class="flex justify-center">
                <div class="flex flex-col items-start max-w-lg px-4 just ">
                    <p class="font-bold text-2xl md:text-3xl lg:text-4xl mt-12 mb-4 text-white hidden-section">
                        <strong class="text-4xl md:text-5xl lg:text-6xl mr-4 hidden-section">1.</strong> Effortlessly portion your
                        budget
                    </p>
                    <img class="hidden-section rounded-lg shadow-md" src="{{ URL('imgs/portion.png') }}" alt="">
                </div>
            </div>


        </div>
    </div>

    <div class="bg-gray-100">
        <div class="py-7 mt-1">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center hidden-section mt-10 space-y-4">
                <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-center hidden-section">
                    <strong class="text-4xl md:text-5xl lg:text-6xl mr-4 ">2. </strong> Track your expenses
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-center w-full max-w-md hidden-section">
                    CashTrail aids you to find your way to financial freedom
                </p>
                <img class="hidden-section rounded-lg shadow-md w-full max-w-sm md:max-w-md" src="{{ URL('imgs/budget-status.png') }}" alt="Budget Status">
            </div>
        </div>
    </div>

    <div class="bg-white">
        <div class="py-7 mt-1 mb-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center hidden-section mt-10 space-y-4">
                <h1 class="font-bold text-2xl md:text-3xl lg:text-4xl text-center hidden-section">
                    <strong class="text-4xl md:text-5xl lg:text-6xl mr-4 ">3. </strong> Maximize Your Financial Power
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-center w-full max-w-md hidden-section">
                    Gain critical insights and transform your money into a bridge to success, not just an expense.
                </p>
                <img class="hidden-section rounded-lg shadow-md w-full max-w-2xl md:max-w-3xl" src="{{ URL('imgs/insights.png') }}" alt="Budget Status">
            </div>
        </div>

    </div>

    <div class="bg-gray-700 text-white">
        <div class="py-3">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center space-y-4">
                <footer class="text-center py-4">
                    <p>&copy; {{ date('Y') }} CashTrail. All Rights Reserved.</p>
                </footer>
            </div>
        </div>
    </div>
    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                }
                // else {
                //     entry.target.classList.remove('show');
                // }
            });
        });

        const hiddenElements = document.querySelectorAll('.hidden-section');
        hiddenElements.forEach((el) => observer.observe(el));
    </script>
</body>

</html>
