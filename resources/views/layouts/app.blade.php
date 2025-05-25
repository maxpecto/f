<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/assets/image/{{ $general->site_favicon }}" type="image/png">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    @if(Route::current()->getName() == 'home')
    <title>{{ $general->site_name }} - {{ $general->site_title }}</title>
    <meta name="description" content="{{ $general->site_description }}"/>
    <meta name="keywords" content="{{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'single-movie')
    <title>{{ __("Watch") }} {{ $movies->title }} ({{ date('Y', strtotime($movies->release_date))  }}) {{ __("Online") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ __("Watch") }} {{ $movies->title }} ({{ date('Y', strtotime($movies->release_date))  }}) {{ __("Online") }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ $general->site_name }}, {{ $general->site_keywords }},@foreach($movies->keywords as $keyword) {{$keyword->name }},@endforeach"/>
    @elseif(Route::current()->getName() == 'single-series')
    <title>{{ __("Watch") }} {{ $series->title }} ({{ date('Y', strtotime($series->release_date))  }}) {{ __("Online") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ __("Watch") }} {{ $series->title }} ({{ date('Y', strtotime($series->release_date))  }}) {{ __("Online") }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ $general->site_name }}, {{ $general->site_keywords }},@foreach($series->keywords as $keyword) {{$keyword->name }},@endforeach"/>
    @elseif(Route::current()->getName() == 'single-episode')
    <title>{{ __("Watch") }} {{ $series->title }} ({{ date('Y', strtotime($series->release_date))  }}) {{ __("Season") }} {{$episode->season_id}} {{ __("Episode") }} {{$episode->episode_id}} (S{{$episode->season_id}}E{{$episode->episode_id}}) {{ __("Online") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ __("Watch") }} {{ $series->title }} ({{ date('Y', strtotime($series->release_date))  }}) {{ __("Season") }} {{$episode->season_id}} {{ __("Episode") }} {{$episode->episode_id}} (S{{$episode->season_id}}E{{$episode->episode_id}}) {{ __("Online") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ $general->site_name }}, {{ $general->site_keywords }},@foreach($series->keywords as $keyword) {{$keyword->name }},@endforeach {{ $series->title }}, {{ __("Season") }} {{$episode->season_id}}, {{ __("Episode") }} {{$episode->episode_id}}, (S{{$episode->season_id}}E{{$episode->episode_id}})"/>
    @elseif(Route::current()->getName() == 'single-person')
    <title>{{ $persons_data->name }} ({{ __("Biography") }}) - {{ $general->site_name }}</title>
    <meta name="description" content="{{ $persons_data->name }} ({{ __("Biography") }}) - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ $persons_data->name }}, {{ __("Biography") }}, {{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'single-collection')
    <title>{{ $collections_items->name }} ({{ __("Collection") }}) - {{ $general->site_name }}</title>
    <meta name="description" content="{{ $collections_items->name }} ({{ __("Collection") }}) - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ $collections_items->name }}, {{ __("Collection") }}, {{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'single-page')
    <title>{{ $pages->title }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ $pages->title }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ $pages->title }}, {{ __("Page") }}, {{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'user-profile')
    <title>{{ $users->fname }} {{ $users->lname }} - {{ __("Profile") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ $users->fname }} {{ $users->lname }} - {{ __("Profile") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ $users->fname }} {{ $users->lname }}, {{ __("Profile") }}, {{ $users->name }}, {{ __("Biography") }}, {{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'edit-profile')
    <title>{{ $users->fname }} {{ $users->lname }} - {{ __("Edit Profile") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ $users->fname }} {{ $users->lname }} - {{ __("Edit Profile") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ $users->fname }} {{ $users->lname }}, {{ __("Edit Profile") }}, {{ $users->name }}, {{ __("Biography") }}, {{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'movies-lists')
    <title>{{ __("Latest Movies Lists") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ __("Latest Movies Lists") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ __("Latest Movies Lists") }}, {{ __("Movies") }}, {{ __("Latest") }}, {{ __("Movies Lists") }}{{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'series-lists')
    <title>{{ __("Latest Series Lists") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ __("Latest Series Lists") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ __("Latest Series Lists") }}, {{ __("Series") }}, {{ __("Latest") }}, {{ __("Series Lists") }}{{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'episodes-lists')
    <title>{{ __("Latest Episodes Lists") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ __("Latest Episodes Lists") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ __("Latest Episodes Lists") }}, {{ __("Episodes") }}, {{ __("Latest") }}, {{ __("Episodes lists") }} {{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'trendings-lists')
    <title>{{ __("Trendings Lists") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ __("Latest Trendings Lists") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ __("Latest Trendings Lists") }}, {{ __("Trendings") }}, {{ __("Latest") }}, {{ __("Trendings Lists") }}{{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'recommendeds-lists')
    <title>{{ __("Recommendeds Lists") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ __("Latest Recommendeds Lists") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ __("Latest Recommendeds Lists") }}, {{ __("Recommendeds") }}, {{ __("Latest") }}, {{ __("Recommendeds Lists") }}{{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'persons-lists')
    <title>{{ __("Persons Lists") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ __("Persons Lists") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ __("Persons Lists") }}, {{ __("Persons") }}, {{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'collections-lists')
    <title>{{ __("Collections Lists") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ __("Latest Collections Lists") }}- {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ __("Latest Collections Lists") }}, {{ __("Collections") }}, {{ __("Latest") }}, {{ __("Collections Lists") }}{{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'watchlists-lists')
    <title>{{ __("Watchlists") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ __("Watchlists") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ __("Watchlists") }}, {{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'start-with-lists')
    <title>{{ __("Start With") }} {{ $start_with }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ __("Start With") }} {{ $start_with }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ __("Start With") }} {{ $start_with }}, {{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'genres_lists')
    <title>{{ $heading_name }} {{ __("Genre Lists") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ $heading_name }} {{ __("Genre Lists") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ $heading_name }}, {{ __("Genres") }} , {{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'years_lists')
    <title>{{ $heading_name }} {{ __("Year Lists") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ $heading_name }} {{ __("Year Lists") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ $heading_name }}, {{ __("Years") }} , {{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'countries_lists')
    <title>{{ $heading_name }} {{ __("Country Lists") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ $heading_name }} {{ __("Country Lists") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ $heading_name }}, {{ __("Country") }} , {{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'qualities_lists')
    <title>{{ $heading_name }} {{ __("Quality Lists") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ $heading_name }} {{ __("Quality Lists") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ $heading_name }}, {{ __("Quality") }} , {{ $general->site_keywords }}"/>
    @elseif(Route::current()->getName() == 'search-lists')
    <title>{{ __("Search Lists") }} - {{ $general->site_name }}</title>
    <meta name="description" content="{{ __("Search Lists") }} - {{ $general->site_name }} - {{ $general->site_description }}"/>
    <meta name="keywords" content="{{ __("Search Lists") }},{{ __("Search") }}, {{ __("Lists") }} , {{ $general->site_keywords }}"/>
    @endif
    
    <meta name="author" content="{{ $general->site_author }}, {{ $general->site_email }}">
    <meta name="rating" content="general">

    <!-- Seo Verification -->
    <meta name="google-site-verification" content="{!! base64_decode($seosettings->site_google_verification_code) !!}" />
    <meta name="msvalidate.01" content="{!! base64_decode($seosettings->site_bing_verification_code) !!}" />
    <meta name="yandex-verification" content="{!! base64_decode($seosettings->site_yandex_verification_code) !!}"/>

    <!-- jQuery (CDN) - Diğer scriptlerden önce yüklenmeli -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <style>
        [x-cloak] { display: none }

        /* Navbar scroll effect */
        #main-header.navbar-scrolled {
            background-color: #1f2937; /* bg-gray-800 to bg-gray-900 (veya istediğiniz bir renk) */
            padding-top: 0.5rem; /* py-2 */
            padding-bottom: 0.5rem; /* py-2 */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* Hafif bir gölge */
        }
        #main-header.navbar-scrolled .sm\:w-32 {
            width: 6rem; /* sm:w-24 - Logoyu biraz küçült */
        }
        #main-header {
            transition: background-color 0.3s ease-in-out, padding 0.3s ease-in-out;
        }
        #main-header.navbar-scrolled .sm\:w-32 img {
            transition: width 0.3s ease-in-out; /* Logo için yumuşak geçiş */
        }

        .slide {
            display: inline;
            vertical-align: top;
        }
        .card-wrapper {
            display: inline;
        }
        body[data-theme=dark] .card, body[data-theme=dark] .card-header, body[data-theme=dark] .card-subheader {
            color: #dadde4;
        }
        .card.loaded {
            opacity: 1;
            transform: translateY(0);
        }
        .inline-top {
            display: inline-block;
            vertical-align: top;
        }
        .card {
            width: auto;
            position: relative;
            cursor: pointer;
            opacity: 0;
            transform: translateY(20px);
            transition: transform .2s,opacity .2s;
        }
        .card.loaded:hover, .card.playing {
            transition: transform .2s;
            transform: translateY(0);
        }
        .card .card-content-wrap {
            width: 100%;
            position: relative;
        }
        .card:hover .card-actions{
            opacity:1
        }
        .card-actions{
            z-index:3;
            position:absolute;
            opacity:0;
            height:100%;
            width:100%;
            top:0;
            left:0;
            transition:opacity .2s;
            pointer-events:none
        }
        .watchlist-button-card{
            display:inline-block;
            position:absolute;
            top:5px;
            right:5px;
            line-height:1;
            color:#fff;
            vertical-align:top;
            cursor:pointer;
            overflow:visible;
            pointer-events:all
        }
        .watchlist-button-card svg{
            width:24px;
            height:24px;
            vertical-align:top;
            fill:#fff
        }
        .watchlist-button-card:before{
            position:absolute;
            right:100%;
            opacity:0;
            transition:opacity .3s;
            padding:2px 5px;
            font-size:11px;
            content:attr(data-text);
            pointer-events:none;
            white-space:nowrap
        }
        .watchlist-button-card:hover:before{
            pointer-events:all;
            opacity:1
        }
        .watchlist-button-text{
            vertical-align:top;
            text-align:right;
            padding:2px;
            font-size:9px;
            opacity:0;
            pointer-events:none;
            display:inline-block;
            transition:opacity .3s
        }
        .card .card-image-content {
            position: relative;
            font-size: 0;
            overflow: hidden;
        }
        .card:hover .card-image-content {
            box-shadow: 0 4px 10px 0 rgb(34 34 34 / 40%);
        }
        .card .image-card {
            transition: transform .2s;
        }
        .card.playing .base-card-image, .card:hover .base-card-image {
            transform: scale(1.05,1.1);
        }
        .image-card {
            position: relative;
            width: 100%;
        }
        .image-card .hidden {
            visibility: hidden;
        }
        .image-card .original-image {
            left: 0;
            top: 0;
            height: 100%;
        }
        .image-card img {
            width: 100%;
        }

        /* Play Icon */
        .card .card-overlay {
            background: url('https://api.iconify.design/bi:play.svg?color=%23ffffff&width=60&height=60') no-repeat center center;
            opacity: 0;
            top: 0;
            left: 0;
            transition: opacity .2s;
            background-color: rgba(0,0,0,.6);
        }
        .card .card-overlay, .card .normal-image {
            position: absolute;
            width: 100%;
            height: 100%;
        }
        .card.playing .card-overlay.show-icon, .card:hover .card-overlay.show-icon {
            opacity: 1;
        }

        /* Persons Icon */
        .card .persons-overlay {
            background: url('https://api.iconify.design/bi:person.svg?color=%23ffffff&width=30&height=30') no-repeat center center;
            opacity: 0;
            top: 0;
            left: 0;
            transition: opacity .2s;
            background-color: rgba(0,0,0,.6);
        }
        .card .persons-overlay, .card .normal-image {
            position: absolute;
            width: 100%;
            height: 100%;
        }
        .card.playing .persons-overlay.show-icon, .card:hover .persons-overlay.show-icon {
            opacity: 1;
        }

        /* Collection Icon */
        .card .collections-overlay {
            background: url('https://api.iconify.design/bi:collection-play.svg?color=%23ffffff&width=30&height=30') no-repeat center center;
            opacity: 0;
            top: 0;
            left: 0;
            transition: opacity .2s;
            background-color: rgba(0,0,0,.6);
        }
        .card .collections-overlay, .card .normal-image {
            position: absolute;
            width: 100%;
            height: 100%;
        }
        .card.playing .collections-overlay.show-icon, .card:hover .collections-overlay.show-icon {
            opacity: 1;
        }

        .card-details{
            padding:10px 0
        }
        .text-overflow{
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis
        }
        .card-header{
            font-weight:600;
            font-size:14px;
            margin-bottom:3px;
            line-height:20px;
            letter-spacing:0;
            letter-spacing:.1px
        }
        .card-subheader{
            font-size:12px;
            line-height:16px
        }
    </style>
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css" />

    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <style>
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            background: transparent;
            background-image: url("data:image/svg+xml;utf8,<svg fill='white' height='18' viewBox='0 0 24 24' width='18' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/><path d='M0 0h24v24H0z' fill='none'/></svg>");
            background-repeat: no-repeat;
            background-position-x: 96%;
            background-position-y: 3px;
            color: rgba(255, 254, 254, 0.637);
        }
        .vjs-poster {
            background-size: cover !important;
        }
        #movie-players .active{
            background: #c27803;
        }
    </style>

    {{-- Player --}}
    <link href="https://vjs.zencdn.net/7.14.3/video-js.css" rel="stylesheet" />
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    <script src="https://vjs.zencdn.net/7.14.3/video.min.js"></script>
    {{-- Hotkey Plugins --}}
    <script src="//cdn.sc.gl/videojs-hotkeys/0.2/videojs.hotkeys.min.js"></script>
    <script src="//cdn.sc.gl/videojs-hotkeys/latest/videojs.hotkeys.min.js"></script>
    {{-- vpaid Plugins --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/videojs-vast-vpaid/2.0.2/videojs.vast.vpaid.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-vast-vpaid/2.0.2/videojs_5.vast.vpaid.min.js"></script>
    {{-- HLS DASH AND HTTP STREAM --}}
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.js"></script>
    {{-- Youtube Stream --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-youtube/2.6.1/Youtube.min.js" ></script>

    @if(!empty($seosettings->site_google_analytics))
    <!-- Google analytics -->
    {!! base64_decode($seosettings->site_google_analytics) !!}
    @endif

    @yield('head')
</head>
<body class="bg-gray-900 font-poppins" data-theme="dark">
    <div id="app" x-data="{ mobileMenuOpen: false, search: false, account: false }">
        <header id="main-header" class="bg-gray-800 sticky top-0 z-50 shadow-2xl">
            <div class="container bg-gray-900 sm:py-4 py-2 mx-auto flex items-center sm:px-4 px-2 w-full text-sm md:text-base" >
                {{-- Logo ve Sol Arama Butonu --}}
                <div class="flex items-center flex-shrink-0">
                    <div class="sm:mr-5 mr-1 sm:w-32 w-20">
                        <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100">
                            <img src="{{ asset('/assets/image') }}/{{ $general->site_logo }}" class="w-32">
                        </a>
                    </div>
                </div>

                {{-- Ana Navigasyon Menüsü (Sola Yaslı) --}}
                <nav class="hidden lg:flex items-center text-white sm:space-x-4 space-x-2 {{ (isset($platforms) && $platforms->isNotEmpty()) ? '' : 'ml-6' }} uppercase">
                        <a class="hover:text-yellow-400 {{ request()->is('/') ? 'text-yellow-400 border-b-2 border-yellow-400' : '' }}" href="/">{{ __('Home') }}</a>
                        <a class="hover:text-yellow-400 {{ request()->is('movies') || request()->is('movie/*') ? 'text-yellow-400 border-b-2 border-yellow-400' : '' }}" href="/movies">{{ __('Movies') }}</a>
                        <a class="hover:text-yellow-400 {{ request()->is('series') || request()->is('series/*') ? 'text-yellow-400 border-b-2 border-yellow-400' : '' }}" href="/series">{{ __('Series') }}</a>
                        <a class="hover:text-yellow-400 {{ request()->is('trendings') ? 'text-yellow-400 border-b-2 border-yellow-400' : '' }}" href="/trendings">{{ __('Trendings') }}</a>
                </nav>

                {{-- Sağ Taraftaki Menü Öğeleri (Collections, My Lists, Kullanıcı) --}}
                <div class="hidden lg:flex items-center text-white sm:space-x-4 space-x-2 ml-auto">
                    {{-- Collections --}}
                    <a href="/collections" class="flex items-center text-white hover:text-gray-300 focus:outline-none px-2 py-1 hover:bg-gray-700 rounded-md" title="{{ __('Collections') }}">
                        <span class="iconify text-xl" data-icon="bi:collection-play" data-inline="false"></span>
                        <span class="ml-1 xl:inline hidden">{{ __('Collections') }}</span>
                    </a>
                    {{-- Login Register / Log Users Menu --}}
                    @guest
                        <div class="flex items-center space-x-2 bg-gray-800 rounded p-1">
                            <a class="px-3 py-1 bg-gray-700 text-white hover:bg-yellow-400 tracking-widest font-medium transition duration-200 ease" href="{{ route('login') }}">{{ __('Login') }}</a>
                            <a class="px-3 py-1 bg-gray-700 text-white hover:bg-yellow-400 tracking-widest font-medium transition duration-200 ease" href="{{ route('register') }}">{{ __('Join') }}</a>
                        </div>
                    @else
                        {{-- Watchlists --}}
                        <a href="/watchlists" class="flex items-center text-white hover:text-gray-300 focus:outline-none px-2 py-1 hover:bg-gray-700 rounded-md" title="{{ __('Watchlists') }}">
                            <span class="iconify text-xl" data-icon="bi:bookmark-star-fill" data-inline="false"  ></span>
                            <span class="ml-1 xl:inline hidden">{{ __('My Lists') }}</span>
                        </a>
                        {{-- Account --}}
                        <div class="relative" x-cloak title="{{ __('Account') }}">
                            <button @click="account = !account" class="flex items-center text-white text-xl hover:text-gray-300 cursor-pointer h-9 w-9 rounded-full border border-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                                <img src="{{ asset('/assets/users/') }}{{Auth::user()->profile_img}}" class="rounded-full ">
                            </button>
                            <ul x-show="account"
                                @click.away="account = false"
                                class="absolute font-normal bg-gray-900 shadow-sm overflow-hidden rounded w-56 border mt-2 py-4 px-2 right-0 z-20">
                                @if(Auth::user()->role == 'administrators')
                                <li class="text-white hover:text-yellow-400 p-2">
                                    <a class="flex items-center space-x-2" href="/admin"><span class="iconify" data-icon="fa-solid:solar-panel" data-inline="false" data-width="20" data-height="20"></span><span>{{ __('Control Panel') }}</span></a>
                                </li>
                                @endif
                                <li class="text-white hover:text-yellow-400 p-2">
                                    <a class="flex items-center space-x-2" href="/&#64;{{Auth::user()->username}}"><span class="iconify" data-icon="bx:bxs-user-circle" data-inline="false" data-width="20" data-height="20"></span><span>{{ __('Profile') }}</span></a>
                                </li>
                                <li class="text-white hover:text-yellow-400 p-2">
                                    <a class="flex items-center space-x-2" href="/edit-profile"><span class="iconify" data-icon="fluent:settings-28-filled" data-inline="false" data-width="20" data-height="20"></span><span>{{ __('Settings') }}</span></a>
                                </li>
                                {{-- Language Switcher --}}
                                <li class="text-white p-2 border-t border-gray-700 mt-2 pt-3">
                                    <div class="flex items-center">
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1.1em" height="1.1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="m12 22l-1-3H4q-.825 0-1.412-.587Q2 17.825 2 17V4q0-.825.588-1.413Q3.175 2 4 2h6l.875 3H20q.875 0 1.438.562Q22 6.125 22 7v13q0 .825-.562 1.413Q20.875 22 20 22Zm-4.85-7.4q1.725 0 2.838-1.112Q11.1 12.375 11.1 10.6q0-.2-.012-.363q-.013-.162-.063-.337h-3.95v1.55H9.3q-.2.7-.763 1.087q-.562.388-1.362.388q-.975 0-1.675-.7q-.7-.7-.7-1.725q0-1.025.7-1.725q.7-.7 1.675-.7q.45 0 .85.162q.4.163.725.488L9.975 7.55Q9.45 7 8.713 6.7q-.738-.3-1.563-.3q-1.675 0-2.862 1.187Q3.1 8.775 3.1 10.5q0 1.725 1.188 2.912Q5.475 14.6 7.15 14.6Zm6.7.5l.55-.525q-.35-.425-.637-.825q-.288-.4-.563-.85Zm1.25-1.275q.7-.825 1.063-1.575q.362-.75.487-1.175h-3.975l.3 1.05h1q.2.375.475.813q.275.437.65.887ZM13 21h7q.45 0 .725-.288Q21 20.425 21 20V7q0-.45-.275-.725Q20.45 6 20 6h-8.825l1.175 4.05h1.975V9h1.025v1.05H19v1.025h-1.275q-.25.95-.75 1.85q-.5.9-1.175 1.675l2.725 2.675L17.8 18l-2.7-2.7l-.9.925L15 19Z"/></svg>
                                        </span>
                                        <select class="changeLang border-none focus:ring-0 focus:outline-none outline-none pl-2 pr-5 text-white bg-transparent w-full">
                                            <option class="bg-gray-800 text-white border-none" value="tr" {{ session()->get('locale') == 'tr' ? 'selected' : '' }}>TR</option>
                                        </select>
                                    </div>
                                </li>
                                {{-- Logout --}}
                                <li class="text-white hover:text-yellow-400 p-2 border-t border-gray-700 mt-2 pt-3">
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
                            <span class="text-xs">{{ __('Hello') }},</span>
                            <span class="text-sm"><a href="/&#64;{{Auth::user()->username}}">{{Auth::user()->username}}</a></span>
                        </div>
                    @endguest
                    {{-- Dil seçimi (eskiden Account menüsünün dışındaydı, şimdi sağdaki grubun bir parçası) --}}
                    <div class="xl:block hidden">
                        <div class="flex items-center">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="m12 22l-1-3H4q-.825 0-1.412-.587Q2 17.825 2 17V4q0-.825.588-1.413Q3.175 2 4 2h6l.875 3H20q.875 0 1.438.562Q22 6.125 22 7v13q0 .825-.562 1.413Q20.875 22 20 22Zm-4.85-7.4q1.725 0 2.838-1.112Q11.1 12.375 11.1 10.6q0-.2-.012-.363q-.013-.162-.063-.337h-3.95v1.55H9.3q-.2.7-.763 1.087q-.562.388-1.362.388q-.975 0-1.675-.7q-.7-.7-.7-1.725q0-1.025.7-1.725q.7-.7 1.675-.7q.45 0 .85.162q.4.163.725.488L9.975 7.55Q9.45 7 8.713 6.7q-.738-.3-1.563-.3q-1.675 0-2.862 1.187Q3.1 8.775 3.1 10.5q0 1.725 1.188 2.912Q5.475 14.6 7.15 14.6Zm6.7.5l.55-.525q-.35-.425-.637-.825q-.288-.4-.563-.85Zm1.25-1.275q.7-.825 1.063-1.575q.362-.75.487-1.175h-3.975l.3 1.05h1q.2.375.475.813q.275.437.65.887ZM13 21h7q.45 0 .725-.288Q21 20.425 21 20V7q0-.45-.275-.725Q20.45 6 20 6h-8.825l1.175 4.05h1.975V9h1.025v1.05H19v1.025h-1.275q-.25.95-.75 1.85q-.5.9-1.175 1.675l2.725 2.675L17.8 18l-2.7-2.7l-.9.925L15 19Z"/></svg>
                            </span>
                            <select class="changeLang border-none force-none outline-none pl-2 pr-5 text-white">
                                <option class="bg-gray-900 text-white border-none" value="tr" {{ session()->get('locale') == 'tr' ? 'selected' : '' }}>TR</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Mobil Menü (sağda kalmaya devam etmeli) --}}
                <div class="relative block lg:hidden flex items-center ml-auto">
                    {{-- Mobil menü içeriği burada... (Collections, Watchlist, Account, Menu Butonu) --}}
                    <a href="/collections" class="hidden sm:flex items-center text-white sm:text-xl text-sm hover:text-gray-300 cursor-pointer rounded-full bg-gray-800 sm:h-9 sm:w-9 h-6 w-6 justify-center hover:text-gray-300 hover:bg-gray-700 focus:outline-none mx-2" title="{{ __('Collections') }}">
                        <span class="iconify" data-icon="bi:collection-play" data-inline="false"></span>
                    </a>
                    @auth
                        {{-- Watchlists --}}
                        <a href="/watchlists" class="hidden sm:flex items-center text-white text-xl hover:text-gray-300 cursor-pointer rounded-full bg-gray-800 h-9 w-9 justify-center hover:text-gray-300 hover:bg-gray-700 focus:outline-none mx-2" title="{{ __('Watchlists') }}">
                            <span class="iconify" data-icon="bi:bookmark-star-fill" data-inline="false"  ></span>
                        </a>
                        {{-- Account --}}
                        <div x-cloak title="{{ __('Account') }}" class="relative">
                            <button @click="account = !account" class="flex items-center text-white text-xl hover:text-gray-300 cursor-pointer h-9 w-9 rounded-full border border-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                                <img src="{{ asset('/assets/users/') }}{{Auth::user()->profile_img}}" class="rounded-full">
                            </button>
                            <ul x-show="account"
                                @click.away="account = false"
                                class="absolute font-normal bg-gray-900 shadow-sm overflow-hidden rounded w-52 border mt-2 py-4 px-2 right-0 z-20">
                                @if(Auth::user()->role == 'administrators')
                                <li class="text-white hover:text-yellow-400 p-2">
                                    <a class="flex items-center space-x-2" href="/admin"><span class="iconify" data-icon="fa-solid:solar-panel" data-inline="false" data-width="20" data-height="20"></span><span>{{ __('Control Panel') }}</span></a>
                                </li>
                                @endif
                                <li class="text-white hover:text-yellow-400 p-2">
                                    <a class="flex items-center space-x-2" href="/&#64;{{Auth::user()->username}}"><span class="iconify" data-icon="bx:bxs-user-circle" data-inline="false" data-width="20" data-height="20"></span><span>{{ __('Profile') }}</span></a>
                                </li>
                                <li class="text-white hover:text-yellow-400 p-2">
                                    <a class="flex items-center space-x-2" href="/edit-profile"><span class="iconify" data-icon="fluent:settings-28-filled" data-inline="false" data-width="20" data-height="20"></span><span>{{ __('Settings') }}</span></a>
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
                    @endauth
                    {{-- Mobil Menü Butonu --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="ml-1 flex items-center text-white text-xl hover:text-gray-300 cursor-pointer mr-1 p-2 bg-gray-800 rounded">
                        <span x-show="!mobileMenuOpen" class="iconify" data-icon="heroicons-solid:menu" data-inline="false"></span>
                        <span x-show="mobileMenuOpen" class="iconify" data-icon="heroicons-solid:x" data-inline="false"></span>
                    </button>
                </div>
            </div>

            {{-- Mobil Navigasyon Menüsü --}}
            <nav x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false"
                 class="lg:hidden fixed top-0 left-0 w-full h-full bg-gray-900 bg-opacity-95 z-40 flex flex-col items-center justify-center space-y-6"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-x-full"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-x-0"
                 x-transition:leave-end="opacity-0 transform -translate-x-full"
                 style="display: none;">
                <a class="text-white hover:text-yellow-400 text-2xl {{ request()->is('/') ? 'text-yellow-400 border-b-2 border-yellow-400' : '' }}" href="/" @click="mobileMenuOpen = false">{{ __('Home') }}</a>
                <a class="text-white hover:text-yellow-400 text-2xl {{ request()->is('movies') || request()->is('movie/*') ? 'text-yellow-400 border-b-2 border-yellow-400' : '' }}" href="/movies" @click="mobileMenuOpen = false">{{ __('Movies') }}</a>
                <a class="text-white hover:text-yellow-400 text-2xl {{ request()->is('series') || request()->is('series/*') ? 'text-yellow-400 border-b-2 border-yellow-400' : '' }}" href="/series" @click="mobileMenuOpen = false">{{ __('Series') }}</a>
                <a class="text-white hover:text-yellow-400 text-2xl {{ request()->is('trendings') ? 'text-yellow-400 border-b-2 border-yellow-400' : '' }}" href="/trendings" @click="mobileMenuOpen = false">{{ __('Trendings') }}</a>
                {{-- Dil Seçimi Mobil Menü İçin --}}
                <div class="mt-6">
                    <select class="changeLangMobile bg-gray-800 text-white border border-gray-700 rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        <option value="tr" {{ session()->get('locale') == 'tr' ? 'selected' : '' }}>TR</option>
                        {{-- Diğer diller eklenebilir --}}
                    </select>
                </div>
            </nav>
        </header>

        {{-- Announcements Bar --}}
        @if(isset($activeAnnouncements) && $activeAnnouncements->isNotEmpty())
            <div class="w-full bg-gray-800 border-b border-gray-700 shadow-md">
                <div class="container mx-auto sm:px-4 px-2">
                    <div class="text-yellow-400 py-2 overflow-hidden whitespace-nowrap">
                        <div class="announcement-bar-text">
                            <span class="iconify" data-icon="ic:round-campaign" style="vertical-align: middle; margin-right: 10px; font-size: 1.2em;"></span>
                            @foreach($activeAnnouncements as $announcement)
                                @if($announcement->link_url)
                                    <a href="{{ $announcement->link_url }}" target="_blank" class="announcement-item hover:underline">
                                        {{ $announcement->content }}
                                    </a>
                                @else
                                    <span class="announcement-item">{{ $announcement->content }}</span>
                                @endif
                                @if(!$loop->last)
                                    <span class="announcement-separator" style="margin-left: 12px; margin-right: 12px;">&bull;</span>
                                @endif
                                    @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .announcement-bar-text {
                    display: inline-block;
                    padding-left: 100%; /* Start off-screen to the right */
                    animation: marquee 20s linear infinite;
                    white-space: nowrap; /* Ensure no wrapping inside the animated text itself */
                }
                .announcement-bar-text:hover {
                    animation-play-state: paused;
                }
                .announcement-item, .announcement-separator, .announcement-bar-text .iconify {
                    display: inline-block; /* Ensure all parts flow in a line */
                    vertical-align: middle; /* Align items nicely on the line */
                }
                @keyframes marquee {
                    0%   { transform: translateX(0); }
                    100% { transform: translateX(-100%); }
                }
            </style>
        @endif

        <main class="pb-10">
        {{-- YENİ ANA FLEX KAPSAYICI BAŞLANGICI --}}
        <div class="container mx-auto flex justify-between relative px-4 md:px-0">

            {{-- Sol Sidebar Reklam Alanı (STICKY) --}}
            <div class="hidden xl:block sticky top-24 z-40 flex-shrink-0 self-start" style="width: 160px; height: fit-content;"> {{-- Değiştirilen Classlar ve Style --}}
                @if(isset($ads) && $ads->activate && !empty($ads->site_left_sidebar))
                    {!! base64_decode($ads->site_left_sidebar) !!}
                @else
                    {{-- Sol test kodu veya boşluk bırakılabilir --}}
                @endif
            </div>

            {{-- Ana İçerik Alanı --}}
            {{-- Orijinal <main class="container mx-auto px-4 md:px-0"> satırını bununla değiştir --}}
            <main class="flex-grow min-w-0 px-4 bg-gray-900"> {{-- bg-gray-800 -> bg-gray-900 olarak değiştirildi --}}
        @yield('content')
            </main>

            {{-- Sağ Sidebar Reklam Alanı (STICKY) --}}
            <div class="hidden xl:block sticky top-24 z-40 flex-shrink-0 self-start" style="width: 160px; height: fit-content;"> {{-- Değiştirilen Classlar ve Style --}}
                 @if(isset($ads) && $ads->activate && !empty($ads->site_right_sidebar))
                    {!! base64_decode($ads->site_right_sidebar) !!}
                @else
                     {{-- Sağ test kodu veya boşluk bırakılabilir --}}
                 @endif
            </div>

        </div> {{-- YENİ ANA FLEX KAPSAYICI SONU --}}
        </main>

        <footer>
            @if(count($pages_lists) > 0 )
            <div class="container bg-gray-800 py-4 mx-auto px-4 md:flex flex-warp justify-end bg-gray-800 md:space-x-4 ">
                @foreach($pages_lists as $page)
                    <div class="text-sm text-white hover:text-yellow-400">
                        <a href="/page/{{ $page->slug }}">{{ $page->title }}</a>
                    </div>
                @endforeach
            </div>
            @endif

            <div class="container bg-gray-900 py-6 mx-auto px-4 border-t-2 border-gray-700">
                <div class="grid xl:grid-cols-4 lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1 grid-cols-1 gap-4">
                    <div class="py-5 px-2 text-white text-sm">
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
                    <div class="px-2">
                        <div class="mb-4">
                            <h3 class="text-base font-bold text-yellow-500 border-b border-gray-700 pb-1">{{ __('Genres') }}</h3>
                        </div>
                        <div class="grid sm:grid-cols-3 grid-cols-2 gap-2 items-center">
                            @if(count($genres) > 0 )
                                @foreach($genres as $genre)
                                <a class="border-2 border-gray-700 px-2 py-1 rounded text-center text-white text-sm hover:bg-gray-800 hover:text-yellow-500 transition duration-200 ease-out cursor-pointer" href="{{ url('/genres/') }}/@php echo strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $genre->name)));  @endphp" ><span>{{$genre->name}}</span></a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="px-2">
                        <div class="mb-4">
                            <h3 class="text-base font-bold text-yellow-500 border-b border-gray-700 pb-1">{{ __('Years') }}</h3>
                        </div>
                        <div class="grid sm:grid-cols-3 grid-cols-2 gap-2 items-center">
                            @if(count($years) > 0 )
                                @foreach($years as $year)
                                <a class="border-2 border-gray-700 px-2 py-1 rounded text-center text-white text-sm hover:bg-gray-800 hover:text-yellow-500 transition duration-200 ease-out cursor-pointer" href="{{ url('/years/') }}/@php echo strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $year->name)));  @endphp" ><span>{{$year->name}}</span></a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="px-2 space-y-10">
                        <div>
                            <div class="mb-2">
                                <h3 class="text-base font-bold text-yellow-500 border-b border-gray-700 pb-1">{{ __('Menus') }}</h3>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="text-sm text-white hover:text-yellow-400">
                                    <a href="/">{{ __('Home') }}</a>
                                </div>
                                <div class="text-sm text-white hover:text-yellow-400">
                                    <a href="/movies">{{ __('Movies') }}</a>
                                </div>
                                <div class="text-sm text-white hover:text-yellow-400">
                                    <a href="/series">{{ __('Series') }}</a>
                                </div>
                                <div class="text-sm text-white hover:text-yellow-400">
                                    <a href="/trendings">{{ __('Trendings') }}</a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2">
                                <h3 class="text-base font-bold text-yellow-500 border-b border-gray-700 pb-1">{{ __('Discovers') }}</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                @auth
                                <div class="text-sm text-white hover:text-yellow-400">
                                    <a href="/watchlists">{{ __('Watchlists') }}</a>
                                </div>
                                @endauth
                                <div class="text-sm text-white hover:text-yellow-400">
                                    <a href="/collections">{{ __('Collections') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-5 gap-3 text-3xl mt-4 p-4 bg-gray-800 rounded text-gray-200">
                            @if(!empty($general->site_facebook))
                                <a class="hover:text-yellow-500" target="_blank" href="{{ $general->site_facebook }}"><span class="iconify" data-icon="ant-design:facebook-filled"></span></a>
                            @endif
                            @if(!empty($general->site_twitter))
                                <a class="hover:text-yellow-500" target="_blank" href="{{ $general->site_twitter }}"><span class="iconify" data-icon="fa-brands:twitter-square"></span></a>
                            @endif
                            @if(!empty($general->site_youtube))
                                <a class="hover:text-yellow-500" target="_blank" href="{{ $general->site_youtube }}"><span class="iconify" data-icon="bi:youtube"></span></a>
                            @endif
                            @if(!empty($general->site_linkedin))
                                <a class="hover:text-yellow-500" target="_blank" href="{{ $general->site_linkedin }}"><span class="iconify" data-icon="bi:linkedin"></span></a>
                            @endif
                            @if(!empty($general->site_pinterest))
                                <a class="hover:text-yellow-500" target="_blank" href="{{ $general->site_pinterest }}"><span class="iconify" data-icon="fa:pinterest-square"></span></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.js" ></script>
    @stack('js')
    <script>
        var url = "{{ route('changeLang') }}";

        $(".changeLang, .changeLangMobile").change(function(){
            window.location.href = url + "?lang="+ $(this).val();
        });

    </script>
    {{-- Other Ads --}}
    @if($ads->activate == 1)
        @if(!empty($ads->site_popunder))
            <!-- Ads : Popup -->
            {!! base64_decode($ads->site_popunder) !!}
        @endif
        @if(!empty($ads->site_sticky_banner))
            <!-- Ads : Sticky Banner -->
            {!! base64_decode($ads->site_sticky_banner) !!}
        @endif
        @if(!empty($ads->site_push_notifications))
            <!-- Ads : Push notifications -->
            {!! base64_decode($ads->site_push_notifications) !!}
        @endif
        @if(!empty($ads->site_desktop_fullpage_interstitial))
            <!-- Ads : Desktop Fullpage Interstitial -->
            {!! base64_decode($ads->site_desktop_fullpage_interstitial) !!}
        @endif
    @endif
</body>
</html>
