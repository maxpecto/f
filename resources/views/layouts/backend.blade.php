<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/assets/image/{{ $general->site_favicon }}" type="image/png">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Control Panel - {{ $general->site_name }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://angry-mahavira-9b32ec.netlify.app/js/app.8653ff03.js" defer></script>
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" />

    <link href="{{ asset('css/metisMenu.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/metisMenu.min.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

    <!-- CHARTJS PLUGIN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>

    <!-- J.VECTOR.MAP PLUGIN -->
    <link rel="stylesheet" href="{{ asset('plugins/jvectormap/jquery-jvectormap-2.0.3.css') }}">
    <script src="{{ asset('plugins/jvectormap/jquery-jvectormap-2.0.3.min.js') }}"></script>
    <script src="{{ asset('plugins/jvectormap/jquery-jvectormap-world.js') }}"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap');
        [x-cloak] { display: none }

        #main {
            display: grid;
            grid-template-columns: 6rem auto;
        }

        @media (min-width: 1200px) {
            #main { grid-template-columns: 12rem auto; }
        }

        a {
            text-decoration: none;
        }

        .pt-16 {
            padding-top: 4rem;
        }

        .top-full { top: 100%; }
        .left-full { left: 100%; }

        @media (min-width: 1200px) {
            .xl\:pin-none { left: auto; }
        }
        .group:hover .group-hover\:block {
            display: block;
        }
    </style>
</head>
<body class="bg-white h-screen antialiased leading-none font-Poppins">
    <div id="app">
        <main class="container mx-auto w-full">
            <div x-data="{ sidebarOpen: true }" class="flex overflow-x-hidden h-full">
                <aside class="flex-shrink-0 w-64 flex flex-col border-r transition-all duration-300" :class="{ '-ml-64': !sidebarOpen }">
                    <div class="items-center p-4 text-semibold text-gray-100 bg-gray-900 text-center">
                        <div class="mx-auto mb-2">
                            <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100">
                                <img src="{{ asset('/assets/image') }}/{{ $general->site_logo }}">
                            </a>
                        </div>
                        <div class="p-2 uppercase border-1 border-t border-gray-500">Control Panel</div>
                    </div>
                    <div class="bg-gray-800 flex items-center p-4 text-white text-2xl justify-center">
                        {{-- Account --}}
                        <div class="relative">
                            <button class="flex items-center text-white text-xl hover:text-gray-300 cursor-pointer h-12 w-12 rounded-full border border-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                                <img src="{{ asset('/assets/users/') }}{{Auth::user()->profile_img}}" class="rounded-full">
                            </button>
                        </div>
                        <div class="relative mx-2 grid grid-row">
                            <span class="text-xs">Welcome Back,</span>
                            <span class="text-sm"><a href="/&#64;{{Auth::user()->username}}">{{Auth::user()->username}}</a></span>
                        </div>
                    </div>
                    <nav class="flex-1 flex flex-col bg-gray-800 text-white">
                        <div class="nav">
                            <ul id="menu">
                                <li><a href="/admin" class="flex items-center px-6 py-4 bg-gray-800"><span class="iconify" data-icon="fa:dashboard" data-inline="false"></span><span>Dashboard</span></a></li>
                                <li class="has-submenu"><a href="#" class="flex items-center px-6 py-4"><span class="iconify" data-icon="bx:bx-movie" data-inline="false"></span><span>Movies</span></a>
                                    <ul>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/movies"><span class="iconify" data-icon="mdi:movie-open-outline" data-inline="false"></span><span>Movies</span></a></li>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/movies/add"><span class="iconify" data-icon="mdi:movie-open-plus" data-inline="false"></span><span>Add Movies</span></a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu"><a href="#" class="flex items-center px-6 py-4"><span class="iconify" data-icon="bx:bx-movie" data-inline="false"></span><span>Series</span></a>
                                    <ul>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/series"><span class="iconify" data-icon="mdi:movie-open-outline" data-inline="false"></span><span>Series</span></a></li>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/series/add"><span class="iconify" data-icon="mdi:movie-open-plus" data-inline="false"></span><span>Add Series</span></a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu"><a href="#" class="flex items-center px-6 py-4"><span class="iconify" data-icon="bx:bx-movie" data-inline="false"></span><span>Episodes</span></a>
                                    <ul>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/episodes"><span class="iconify" data-icon="mdi:movie-open-outline" data-inline="false"></span><span>Episodes</span></a></li>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/episodes/add"><span class="iconify" data-icon="mdi:movie-open-plus" data-inline="false"></span><span>Add Episodes</span></a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu"><a href="###" class="flex items-center px-6 py-4"><span class="iconify" data-icon="bi:collection" data-inline="false"></span></span><span>Collections</span></a>
                                    <ul>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/collections"><span class="iconify" data-icon="bi:collection" data-inline="false"></span><span>Collections</span></a></li>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/collections/add"><span class="iconify" data-icon="fluent:collections-add-24-filled" data-inline="false"></span><span>Add Collections</span></a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu"><a href="#" class="flex items-center px-6 py-4"><span class="iconify" data-icon="iconoir:multiple-pages" data-inline="false"></span><span>Pages</span></a>
                                    <ul>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/pages"><span class="iconify" data-icon="iconoir:multiple-pages" data-inline="false"></span><span>Pages</span></a></li>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/pages/add"><span class="iconify" data-icon="foundation:page-add" data-inline="false"></span><span>Create Page</span></a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu"><a href="#" class="flex items-center px-6 py-4"><span class="iconify" data-icon="raphael:users" data-inline="false"></span><span>Persons</span></a>
                                    <ul>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/persons"><span class="iconify" data-icon="raphael:users" data-inline="false"></span><span>Persons</span></a></li>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/persons/add"><span class="iconify" data-icon="el:user" data-inline="false"></span><span>Add Person</span></a></li>
                                    </ul>
                                </li>
                                {{-- Platformlar Menü Öğesi Başlangıcı --}}
                                <li class="has-submenu {{ request()->routeIs('admin.platforms.*') ? 'active-submenu' : '' }}">
                                    <a href="#" class="flex items-center px-6 py-4">
                                        <span class="iconify" data-icon="eos-icons:platform" data-inline="false"></span>
                                        <span>Platformlar</span>
                                    </a>
                                    <ul>
                                        <li>
                                            <a class="flex items-center px-6 py-4 {{ request()->routeIs('admin.platforms.index') ? 'bg-gray-700' : '' }}" href="{{ route('admin.platforms.index') }}">
                                                <span class="iconify" data-icon="eos-icons:platform-list" data-inline="false"></span>
                                                <span>Tüm Platformlar</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="flex items-center px-6 py-4 {{ request()->routeIs('admin.platforms.create') ? 'bg-gray-700' : '' }}" href="{{ route('admin.platforms.create') }}">
                                                <span class="iconify" data-icon="eos-icons:add-circle-outlined" data-inline="false"></span>
                                                <span>Yeni Platform Ekle</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                {{-- Platformlar Menü Öğesi Sonu --}}
                                <li class="has-submenu"><a href="/admin/genres"class="flex items-center px-6 py-4"><span class="iconify" data-icon="mdi:movie-open-outline" data-inline="false"></span><span>Genres</span></a></li>
                                <li class="has-submenu"><a href="/admin/comments"class="flex items-center px-6 py-4"><span class="iconify" data-icon="akar-icons:comment" data-inline="false"></span><span>Comments</span></a></li>
                                <li class="has-submenu"><a href="/admin/reports" class="flex items-center px-6 py-4"><span class="iconify" data-icon="ic:round-report-gmailerrorred" data-inline="false"></span><span>Reports</span></a></li>
                                <li class="has-submenu"><a href="#" class="flex items-center px-6 py-4"><span class="iconify" data-icon="clarity:users-solid" data-inline="false"></span><span>Users</span></a>
                                    <ul>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/users"><span class="iconify" data-icon="clarity:users-solid" data-inline="false"></span><span>Users</span></a></li>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/users/add"><span class="iconify" data-icon="il:add-user" data-inline="false"></span><span>Create User</span></a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu"><a href="#" class="flex items-center px-6 py-4"><span class="iconify" data-icon="ant-design:setting-outlined" data-inline="false"></span><span>Settings</span></a>
                                    <ul>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/settings"><span class="iconify" data-icon="ant-design:setting-outlined" data-inline="false"></span><span>General</span></a></li>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/settings/search-engine"><span class="iconify" data-icon="icon-park-outline:seo-folder" data-inline="false"></span><span>Search Engine</span></a></li>
                                        <li><a class="flex items-center px-6 py-4" href="/admin/settings/advertisements"><span class="iconify" data-icon="ri:advertisement-line" data-inline="false"></span><span>Advertisements</span></a></li>
                                    </ul>
                                </li>
                                <li class="has-submenu"><a href="/admin/sitemaps" class="flex items-center px-6 py-4"><span class="iconify" data-icon="jam:sitemap-f" data-inline="false"></span><span>Sitemaps</span></a></li>
                                {{-- Duyurular Menü Öğesi Başlangıcı --}}
                                <li>
                                    <a href="{{ route('admin.announcements.index') }}" class="flex items-center px-6 py-4 {{ request()->routeIs('admin.announcements.*') ? 'bg-gray-700' : '' }}">
                                        <span class="iconify" data-icon="ic:round-campaign" data-inline="false"></span>
                                        <span class="ml-2">Duyurular</span>
                                    </a>
                                </li>
                                {{-- Duyurular Menü Öğesi Sonu --}}
                            </ul>
                        </div>
                    </nav>
                </aside>
                <div class="flex-1 w-full min-h-screen">
                    <header class="flex justify-between items-center p-4 text-semibold text-gray-100 bg-gray-900">
                        <div class="flex items-center">
                            <button class="p-1 mr-4" @click="sidebarOpen = !sidebarOpen">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <a class="bg-gray-700 text-white px-3 py-2 hover:bg-gray-600 duration-150 text-center tracking-wider uppercase rounded" href="/" target="_blank">View Site</a>
                        </div>
                        <div>
                            <nav class="flex items-center mr-5 text-white space-x-4">
                                {{-- Login Register --}}
                                @guest
                                <div class="flex items-center space-x-2">
                                    <a class="px-5 py-1 bg-green-500 text-white hover:text-gray-200 rounded uppercase tracking-wider" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    <a class="px-5 py-1 bg-green-500 text-white hover:text-gray-200 rounded uppercase tracking-wider" href="{{ route('register') }}">Join</a>
                                </div>
                                {{-- Log Users Menu --}}
                                @else
                                    {{-- Account --}}
                                    <div class="relative" x-cloak x-data="{ account: false }" title="Account">
                                        <button @click="account = true" class="flex items-center text-white text-xl hover:text-gray-300 cursor-pointer h-9 w-9 rounded-full border border-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                                            <img src="{{ asset('/assets/users/') }}{{Auth::user()->profile_img}}" class="rounded-full ">
                                        </button>
                                        <ul x-show="account"
                                            @click.away="account = false"
                                            class="absolute font-normal bg-gray-900 shadow-sm overflow-hidden rounded w-52 border mt-2 py-4 px-2 right-0 z-20">
                                            <li class="text-white hover:text-yellow-400 p-2">
                                                <a class="flex items-center space-x-2" href="/&#64;{{Auth::user()->username}}"><span class="iconify" data-icon="bx:bxs-user-circle" data-inline="false" data-width="20" data-height="20"></span><span>Profile</span></a>
                                            </li>
                                            <li class="text-white hover:text-yellow-400 p-2">
                                                <a class="flex items-center space-x-2" href="/edit-profile"><span class="iconify" data-icon="fluent:settings-28-filled" data-inline="false" data-width="20" data-height="20"></span><span>Settings</span></a>
                                            </li>
                                            {{-- Logout --}}
                                            <li class="text-white hover:text-yellow-400 p-2 border-t-2 border-gray-400">
                                                <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();" class="flex items-center space-x-2">
                                                    <span class="iconify" data-icon="fluent:sign-out-20-filled" data-inline="false" data-width="20" data-height="20"></span><span>{{ __('Logout') }}</span>
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                                    {{ csrf_field() }}
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="relative mx-2 grid grid-row">
                                        <span class="text-xs">Hello,</span>
                                        <span class="text-sm"><a href="/&#64;{{Auth::user()->username}}">{{Auth::user()->username}}</a></span>
                                    </div>

                                @endguest
                            </nav>
                        </div>
                    </header>
                    <main class="p-6 bg-gray-200">
                        @yield('content')
                    </main>
                    <footer class="flex justify-between items-center p-4 text-semibold text-gray-100 bg-gray-900">
                        <div class="container bg-gray-900 py-6 mx-auto px-4">
                            <div class="mb-2">
                                <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100">
                                    <img src="{{ asset('/assets/image') }}/{{ $general->site_logo }}" class="w-52">
                                </a>
                            </div>
                            <div class="mb-2">
                                <span class="leading-6">
                                    {{ $general->site_description }}
                                </span>
                            </div>
                            <div><strong>{{ $general->site_copyright }}</strong></div>
                        </div>
                    </footer>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.js" ></script>
    @yield('js')
</body>
</html>
