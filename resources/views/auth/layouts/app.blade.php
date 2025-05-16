<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __("Account") }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap');
        [x-cloak] { display: none }
    </style>
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
</head>
<body class="bg-white h-screen antialiased leading-none font-Poppins">
    <div id="app">
        <header class="bg-gray-900 py-4 sticky top-0 z-50 shadow-2xl">
            <div class="container mx-auto flex sm:justify-between justify-end items-center px-4 w-full text-sm md:text-base" >
                {{-- Logo --}}
                <div class="sm:flex items-center mr-5 w-full hidden">
                    <div class="mr-5 w-30">
                        <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100">
                            <img src="{{ asset('/assets/image/logo.png') }}" class="w-28">
                        </a>
                    </div>
                </div>
                <nav class="flex justify-end items-center space-x-2 whitespace-nowrap">
                    @if(Route::is('register') )
                        <span class="text-white whitespace-nowrap">{{ __("Have an account?") }}</span>
                        <a class="px-3 py-1 bg-gray-700 text-white hover:bg-yellow-500 hover:text-white uppercase tracking-widest font-medium transition duration-200 ease" href="{{ route('login') }}">{{ __('Login') }}</a>
                    @elseif(Route::is('login') )
                        <span class="text-white whitespace-nowrap">{{ __("Don't have an account?") }}</span>
                        <a class="px-3 py-1 bg-gray-700 text-white hover:bg-yellow-500 hover:text-white uppercase tracking-widest font-medium transition duration-200 ease" href="{{ route('register') }}">{{ __("Join") }}</a>
                    @else
                        <a class="px-3 py-1 bg-gray-700 text-white hover:bg-yellow-500 hover:text-white uppercase tracking-widest font-medium transition duration-200 ease" href="{{ route('login') }}">{{ __('Login') }}</a>
                        <a class="px-3 py-1 bg-gray-700 text-white hover:bg-yellow-500 hover:text-white uppercase tracking-widest font-medium transition duration-200 ease" href="{{ route('register') }}">{{ __("Join") }}</a>
                    @endif
                </nav>
            </div>
        </header>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
</body>
</html>
