@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/embed.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/icon.min.css" />
<style>
    #player-wrapper {
        position: relative;
    }
    #player-wrapper:after, #player-wrapper:before {
        bottom: 15px;
        left: 10px;
        width: 50%;
        height: 10%;
        max-width: 300px;
        max-height: 100px;
        -webkit-box-shadow: 0 15px 10px rgba(0, 0, 0, 0.5);
        -moz-box-shadow: 0 15px 10px rgba(0, 0, 0, 0.5);
        box-shadow: 0 15px 10px rgba(0, 0, 0, 0.5);
        -webkit-transform: skew(-15deg) rotate(-6deg);
        -moz-transform: skew(-15deg) rotate(-6deg);
        -ms-transform: skew(-15deg) rotate(-6deg);
        -o-transform: skew(-15deg) rotate(-6deg);
        transform: skew(-15deg) rotate(-6deg);
        content: "";
        position: absolute;
        z-index: -1;
    }
    #player-wrapper:after {
        right: 10px;
        left: auto;
        -webkit-transform: skew(15deg) rotate(6deg);
        -moz-transform: skew(15deg) rotate(6deg);
        -ms-transform: skew(15deg) rotate(6deg);
        -o-transform: skew(15deg) rotate(6deg);
        transform: skew(15deg) rotate(6deg);
    }
    #series-players .image {
        float: right;
        position: relative;
        margin: 0;
        width: 20px;
    }
    #series-players.bottom.attached.menu {
        overflow-y: hidden;
        overflow-x: auto;
        padding: 0.5rem 0.25rem;
        background: #1f2022;
        border-bottom: 0;
    }
    #series-players.bottom.attached.menu a {
        padding: 0.5rem 1rem;
        margin-right: 0;
        background: #008bff;
        color: #fff;
        -webkit-border-radius: 0.25rem;
        -moz-border-radius: 0.25rem;
        -o-border-radius: 0.25rem;
        -ms-border-radius: 0.25rem;
        border-radius: 0.25rem;
    }
    #series-players.bottom.attached.menu a.trailer {
        background: #ff4335;
        margin-left: auto;
        margin-right: 0.25rem;
    }
    #series-players.bottom.attached.menu a:hover {
        -webkit-filter: brightness(1.2) !important;
        -moz-filter: brightness(1.2) !important;
        -o-filter: brightness(1.2) !important;
        -ms-filter: brightness(1.2) !important;
        filter: brightness(1.2) !important;
    }
    /* #preroll-player-container ve #preroll-video-element için stiller kaldırıldı, inline olarak tanımlanacaklar */

    .skip-button {
        position: absolute;
        bottom: 20px;
        right: 20px;
        padding: 10px 20px;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        border: 1px solid white;
        border-radius: 5px;
        cursor: pointer;
        z-index: 10000; /* Video üzerinde olması için */
    }
    .skip-button:hover {
        background-color: rgba(0, 0, 0, 0.9);
    }
</style>

@endsection

@section('content')
@include('frontend.layouts.alpa')

<div class="container mx-auto bg-black flex">
    <section class="flex xl:flex-nowrap flex-wrap w-full xl:space-x-4">
        {{-- Main Contain --}}
        <div class="space-y-8 xl:w-9/12 w-full pb-5">
            {{-- Player  --}}
            {{-- Ön Yükleme Video Oynatıcısı --}}
            @if(isset($activePreRollVideo) && $activePreRollVideo)
                <div id="preroll-player-container" style="position: relative; background-color: black; overflow: hidden; z-index: 100; width: 100%; aspect-ratio: 16/9; /* Aspect ratio için */">
                    <video id="preroll-video-element" style="width: 100%; height: 100%; display: block;" playsinline webkit-playsinline preload="metadata"></video>
                    <button id="custom-preroll-play-button" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10008; padding: 12px 25px; font-size: 18px; cursor: pointer; background-color: rgba(0,0,0,0.6); color: white; border: 1.5px solid white; border-radius: 8px; display: none;">OYNAT</button>
                    {{-- Atlama Butonu ve tıklama katmanı JS ile yönetilecek --}}
                </div>
            @endif

            {{-- Ana Video Oynatıcısı (Ön yükleme varsa başlangıçta gizli) --}}
            <div id="main-player-container" @if(isset($activePreRollVideo) && $activePreRollVideo) style="display: none;" @endif>
                <x-series-player :series="$series" :uniqueSeason="$uniqueSeason" :allepisodes="$allepisodes" :firstEpisodeToShow="$firstEpisodeToShow"/>
            </div>

            {{-- Details--}}
            <div class="w-full flex lg:px-10 px-0 text-white lg:space-x-6 space-x-0">
                <div class="w-1/5 lg:block hidden">
                    <div class="w-full">
                        @php
                            $originalPath = $series->poster; // Veritabanındaki yol (örn: assets/series/poster/dizi.jpg)
                            $webpPath = $originalPath ? Str::replaceLast(pathinfo($originalPath, PATHINFO_EXTENSION), 'webp', $originalPath) : null;
                            $defaultPoster = asset('assets/frontend/images/default_poster.jpg');
                        @endphp
                        @if ($originalPath)
                            <picture>
                                @if ($webpPath && Storage::disk('public')->exists($webpPath))
                                    <source srcset="{{ Storage::url($webpPath) }}" type="image/webp">
                                @endif
                                @if (Storage::disk('public')->exists($originalPath))
                                    <source srcset="{{ Storage::url($originalPath) }}" type="image/{{ pathinfo($originalPath, PATHINFO_EXTENSION) }}">
                                    <img alt="{{ $series->title }}" title="{{ $series->title }}" class="w-full rounded-t" src="{{ Storage::url($originalPath) }}">
                                @else
                                    <img alt="{{ $series->title }}" title="{{ $series->title }}" class="w-full rounded-t" src="{{ $defaultPoster }}">
                                @endif
                            </picture>
                        @else
                            <img alt="{{ $series->title }}" title="{{ $series->title }}" class="w-full rounded-t" src="{{ $defaultPoster }}">
                        @endif
                    </div>
                </div>
                <div class="lg:w-4/5 w-full px-10 lg:px-0">
                    <div class="w-full flex sm:flex-nowrap flex-wrap sm:space-y-0 space-y-2">
                        <div class="sm:w-3/4 w-full">
                            <div class="flex justify-between items-center">
                                <h1 class="text-2xl text-yellow-500 mb-2">{{ $series->title }} ({{ date('Y',strtotime($series->release_date)) }})</h1>
                            </div>
                            <div class="mb-1 flex mb-4 sm:flex-nowrap flex-wrap">
                                @if(count($series->genres) > 0)
                                    @php
                                        $num_of_items = count($series->genres);
                                        $num_count = 0;
                                    @endphp
                                    @foreach ($series->genres as $singleGenre)
                                        <span class="text-sm">
                                            <a href="{{ url('/genres/') }}/@php echo strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $singleGenre->name)));  @endphp" >{{ $singleGenre->name }}</a>
                                        </span>
                                        @php
                                            $num_count = $num_count + 1;
                                            if ($num_count < $num_of_items) {
                                                echo '<span class="iconify" data-icon="octicon:dot-24" data-inline="false" data-width="20" data-height="20"></span>';
                                            }
                                        @endphp
                                    @endforeach
                                @endif
                            </div>
                            <div class="mb-1 flex items-center space-x-4 tracking-wide pb-1">
                                <h1 class="text-yellow-500">{{ __("Release Date") }}</h1>
                                <p class="leading-6 text-sm">{{ $series->release_date }}</p>
                            </div>
                            <div class="mb-1 flex items-center space-x-4 tracking-wide pb-1">
                                <h1 class="text-yellow-500">{{ __("Rating") }}</h1>
                                <p class="leading-6 text-sm">{{ $series->rating }}</p>
                            </div>
                            <div class="mb-1 flex items-center space-x-4 tracking-wide pb-1">
                                <h1 class="text-yellow-500">{{ __("Duration") }}</h1>
                                <p class="leading-6 text-sm">{{ $series->duration }} {{ __("min") }}</p>
                            </div>
                            <div class="mb-1 flex items-center space-x-4 tracking-wide pb-1">
                                <h1 class="text-yellow-500">{{ __("Countries") }}</h1>
                                <p class="leading-6 text-sm flex flex-wrap items-center">
                                    @if(count($series->countries) > 0)
                                        @php
                                            $num_of_items = count($series->countries);
                                            $num_count = 0;
                                        @endphp
                                        @foreach ($series->countries as $singleCountrie)
                                            <span>
                                                <a href="{{ url('/countries/') }}/@php echo strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $singleCountrie->name)));  @endphp" >{{ $singleCountrie->name }}</a>
                                            </span>
                                            @php
                                                $num_count = $num_count + 1;
                                                if ($num_count < $num_of_items) {
                                                    echo '<span class="iconify text-yellow-400" data-icon="codicon:dash" data-inline="false" data-width="20" data-height="20"></span>';
                                                }
                                            @endphp
                                        @endforeach
                                    @endif
                                </p>
                            </div>
                            <div class="mb-1 flex items-center space-x-4 tracking-wide pb-1">
                                <h1 class="text-yellow-500">{{ __("Keywords") }}</h1>
                                <p class="leading-6 text-sm flex flex-wrap items-center">
                                    @if(count($series->keywords) > 0)
                                        @php
                                            $num_of_items = count($series->keywords);
                                            $num_count = 0;
                                        @endphp
                                        @foreach ($series->keywords as $singlekeywords)
                                            <span>
                                                {{ $singlekeywords->name }}
                                            </span>
                                            @php
                                                $num_count = $num_count + 1;
                                                if ($num_count < $num_of_items) {
                                                    echo '<span class="iconify text-yellow-400" data-icon="codicon:dash" data-inline="false" data-width="20" data-height="20"></span>';
                                                }
                                            @endphp
                                        @endforeach
                                    @endif
                                </p>
                            </div>
                            <div class="mb-1 flex items-center space-x-4 tracking-wide pb-1">
                                <h1 class="text-yellow-500">{{ __("Qualities") }}</h1>
                                <p class="leading-6 text-sm flex flex-wrap items-center">
                                    @if(count($series->qualities) > 0)
                                        @php
                                            $num_of_items = count($series->qualities);
                                            $num_count = 0;
                                        @endphp
                                        @foreach ($series->qualities as $singlequalities)
                                            <span>
                                                <a href="{{ url('/qualities/') }}/@php echo strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $singlequalities->name)));  @endphp" >{{ $singlequalities->name }}</a>
                                            </span>
                                            @php
                                                $num_count = $num_count + 1;
                                                if ($num_count < $num_of_items) {
                                                    echo '<span class="iconify text-yellow-400" data-icon="codicon:dash" data-inline="false" data-width="20" data-height="20"></span>';
                                                }
                                            @endphp
                                        @endforeach
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="sm:w-1/4 w-full">
                            <div class="flex mb-2 justify-end items-center text-sm">
                                <span>{{ $series->views }} {{ __("Views") }}</span>
                            </div>
                            <div class="block border-2 rounded border-yellow-400 mb-2">
                            </div>
                            <div class="flex mb-2 justify-end items-center space-x-6 text-base">
                                <x-like-buttons :items="$series" :totalLikes="$totalLikes" :totalDislikes="$totalDislikes"/>
                            </div>
                            <div class="block mb-2 ">
                                <x-watchlists-buttons :items="$series"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ads Start -->
            @if($ads->activate == 1)
                @if(isset($ads->site_728x90_banner))
                <div class=" 2xl:flex xl:flex lg:hidden md:hidden sm:hidden hidden py-4 justify-center">
                    {!! base64_decode($ads->site_728x90_banner) !!}
                </div>
                @endif
                @if(isset($ads->site_468x60_banner))
                <div class="flex 2xl:hidden xl:hidden lg:flex md:flex sm:hidden hidden py-4 justify-center">
                    {!!  base64_decode($ads->site_468x60_banner) !!}
                </div>
                @endif
                @if(isset($ads->site_320x100_banner))
                <div class="flex 2xl:hidden xl:hidden lg:hidden md:hidden sm:flex  py-4 justify-center">
                    {!!  base64_decode($ads->site_320x100_banner) !!}
                </div>
                @endif
            @endif
            <!-- Ads End -->

            {{-- Overviews / Posters / Backdrops--}}
            <div class="w-full px-10 text-white">
                <div class="mb-4">
                    <div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400">
                        <div class="flex items-center space-x-1 ">
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl font-medium">{{ __("Overviews") }}</a>
                        </div>
                    </div>
                    <p class="leading-6">{{ $series->overviews }}</p>
                </div>
                <div class="mb-4" id="casts-carousel">
                    <div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400">
                        <div class="flex items-center space-x-1 ">
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl font-medium">{{ __("Casts") }}</a>
                        </div>
                        <div class="right menu flex space-x-2 items-center">
                            <span class="item mr-1 cursor-pointer"><span class="iconify" data-icon="bx:bxs-left-arrow" data-inline="false"></span></span>
                            <span class="item mr-2 cursor-pointer"><span class="iconify" data-icon="bx:bxs-right-arrow" data-inline="false"></span></span>
                        </div>
                    </div>
                    <div class="bg-gray-900 py-5">
                        <div class="carousel-items p-2">
                            @foreach($series->actors as $actor)
                                <a href="/person/{{ $actor->id }}">
                                    <div class="justify-center text-center">
                                        <div class="inline-block justify-center text-center">
                                            <img class="rounded-full h-24 w-24 object-cover border-2 border-yellow-500" src="/assets/persons/{{ $actor->profile_path }}" >
                                        </div>
                                        <div class="inline-grid justify-center text-center">
                                            <span class="text-sm">{{ $actor->name }}</span>
                                            <span class="text-xs text-yellow-400">{{ $actor->known_for_department }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mb-4" id="poster-carousel">
                    <div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400">
                        <div class="flex items-center space-x-1 ">
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl font-medium">{{ __("Posters") }}</a>
                        </div>
                        <div class="right menu flex space-x-2 items-center">
                            <span class="item mr-1 cursor-pointer"><span class="iconify" data-icon="bx:bxs-left-arrow" data-inline="false"></span></span>
                            <span class="item mr-2 cursor-pointer"><span class="iconify" data-icon="bx:bxs-right-arrow" data-inline="false"></span></span>
                        </div>
                    </div>
                    <div class="bg-gray-900">
                        <div class="carousel-items parent-container">
                            @if(isset($tmdbdata['posters']) && count($tmdbdata['posters']) > 0)
                            @foreach($tmdbdata['posters'] as $posters)
                                <a href="https://image.tmdb.org/t/p/original{{ $posters['file_path'] }}" class="items-center object-fill"><img class="p-2" src="https://image.tmdb.org/t/p/w342{{ $posters['file_path'] }}"/></a>
                            @endforeach
                            @else
                                {{-- Optionally display a message if no posters are available --}}
                                {{-- <p class="text-white p-4">Poster bulunamadı.</p> --}}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mb-4" id="backdrop-carousel">
                    <div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400">
                        <div class="flex items-center space-x-1 ">
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl font-medium">{{ __("Backdrops") }}</a>
                        </div>
                        <div class="right menu flex space-x-2 items-center">
                            <span class="item mr-1 cursor-pointer"><span class="iconify" data-icon="bx:bxs-left-arrow" data-inline="false"></span></span>
                            <span class="item mr-2 cursor-pointer"><span class="iconify" data-icon="bx:bxs-right-arrow" data-inline="false"></span></span>
                        </div>
                    </div>
                    <div class="bg-gray-900">
                        <div class="carousel-items parent-container">
                            @if(isset($tmdbdata['backdrops']) && count($tmdbdata['backdrops']) > 0)
                            @foreach($tmdbdata['backdrops'] as $backdrops)
                                <a href="https://image.tmdb.org/t/p/original{{ $backdrops['file_path'] }}"><img class="p-2" src="https://image.tmdb.org/t/p/w500{{ $backdrops['file_path'] }}"/></a>
                            @endforeach
                            @else
                                {{-- Optionally display a message if no backdrops are available --}}
                                {{-- <p class="text-white p-4">Arka plan görseli bulunamadı.</p> --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ads Start -->
            @if($ads->activate == 1)
                @if(isset($ads->site_728x90_banner))
                <div class=" 2xl:flex xl:flex lg:hidden md:hidden sm:hidden hidden py-4 justify-center">
                    {!! base64_decode($ads->site_728x90_banner) !!}
                </div>
                @endif
                @if(isset($ads->site_468x60_banner))
                <div class="flex 2xl:hidden xl:hidden lg:flex md:flex sm:hidden hidden py-4 justify-center">
                    {!!  base64_decode($ads->site_468x60_banner) !!}
                </div>
                @endif
                @if(isset($ads->site_320x100_banner))
                <div class="flex 2xl:hidden xl:hidden lg:hidden md:hidden sm:flex  py-4 justify-center">
                    {!!  base64_decode($ads->site_320x100_banner) !!}
                </div>
                @endif
            @endif
            <!-- Ads End -->

            {{-- Download --}}
			<div class="w-full px-10">
				<div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400">
                    <div class="flex items-center space-x-1">
                        <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl  font-medium">{{ __("Zip Download") }}</a>
                    </div>
                </div>
				<div class="w-full">
                    @if(isset($download))
                        <table class="w-full flex flex-row flex-no-wrap overflow-hidden my-5">
                            <tbody class="flex-1 sm:flex-none w-full">
                                @foreach($download as $key => $value)
                                    <tr class="flex flex-col flex-no wrap sm:table-row mb-2 sm:mb-0 w-full">
                                        <td class="border-grey-light border hover:bg-gray-100 p-3 text-white hover:text-gray-900 whitespace-nowrap">{{ $key }}</td>
                                        <td class="border-grey-light border hover:bg-gray-100 p-3 "><a class="text-white hover:text-yellow-500 truncate" target="_blank" href="{{ $value }}" >{{ $value }}</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
					@else
						<div class=" bg-gray-600 p-3 text-white">{{ __("There is no download links!") }}</div>
					@endif
				</div>
			</div>

            <!-- Ads Start -->
            @if($ads->activate == 1)
                @if(isset($ads->site_728x90_banner))
                <div class=" 2xl:flex xl:flex lg:hidden md:hidden sm:hidden hidden py-4 justify-center">
                    {!! base64_decode($ads->site_728x90_banner) !!}
                </div>
                @endif
                @if(isset($ads->site_468x60_banner))
                <div class="flex 2xl:hidden xl:hidden lg:flex md:flex sm:hidden hidden py-4 justify-center">
                    {!!  base64_decode($ads->site_468x60_banner) !!}
                </div>
                @endif
                @if(isset($ads->site_320x100_banner))
                <div class="flex 2xl:hidden xl:hidden lg:hidden md:hidden sm:flex  py-4 justify-center">
                    {!!  base64_decode($ads->site_320x100_banner) !!}
                </div>
                @endif
            @endif
            <!-- Ads End -->

            {{-- Recommended --}}
            <div class="w-full px-10">
                <div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400">
                    <div class="flex items-center space-x-1">
                        <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl  font-medium">{{ __("Related Series") }}</a>
                    </div>
                </div>
                <div class="w-full grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
                    @if(count($relatedseries) >= 1)
                        @foreach($relatedseries as $homerecommended)
                            <div class="relative w-52 mb-2 mx-2">
                                <div class="slide ">
                                    <div class="card-wrapper">
                                        <a href="/series/{{ $homerecommended->slug }}" title="{{ $homerecommended->title }}" >
                                            <div class="card inline-top loaded portrait-card">
                                                <div class="card-content-wrap">
                                                    <div class="card-image-content">
                                                        <div class="image-card base-card-image">
                                                            @php
                                                                $relatedOriginalPath = $homerecommended->poster;
                                                                $relatedWebpPath = $relatedOriginalPath ? Str::replaceLast(pathinfo($relatedOriginalPath, PATHINFO_EXTENSION), 'webp', $relatedOriginalPath) : null;
                                                                $defaultPoster = asset('assets/frontend/images/default_poster.jpg');
                                                            @endphp
                                                            @if ($relatedOriginalPath)
                                                                <picture>
                                                                    @if ($relatedWebpPath && Storage::disk('public')->exists($relatedWebpPath))
                                                                        <source srcset="{{ Storage::url($relatedWebpPath) }}" type="image/webp">
                                                                    @endif
                                                                    @if (Storage::disk('public')->exists($relatedOriginalPath))
                                                                        <source srcset="{{ Storage::url($relatedOriginalPath) }}" type="image/{{ pathinfo($relatedOriginalPath, PATHINFO_EXTENSION) }}">
                                                                        <img alt="{{ $homerecommended->title }}" title="{{ $homerecommended->title }}" class="original-image" src="{{ Storage::url($relatedOriginalPath) }}">
                                                                    @else
                                                                        <img alt="{{ $homerecommended->title }}" title="{{ $homerecommended->title }}" class="original-image" src="{{ $defaultPoster }}">
                                                                    @endif
                                                                </picture>
                                                            @else
                                                                <img alt="{{ $homerecommended->title }}" title="{{ $homerecommended->title }}" class="original-image" src="{{ $defaultPoster }}">
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="card-overlay show-icon"></div>
                                                        </div>
                                                    </div>
                                                    <div class="card-details text-white w-48" style="overflow: hidden;text-overflow: ellipsis;">
                                                        <h3 class="text-overflow card-header">{{ $homerecommended->title }}</h3>
                                                        <div class="text-overflow card-subheader">
                                                            @foreach ($homerecommended->genres as $singleGenre)
                                                                {{ $loop->first ? '' : ', ' }}
                                                                {{ $singleGenre->name }}
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="flex items-center text-yellow-400"><span>{{ __("There is no related series!") }}</span></div>
                    @endif
                </div>
            </div>

            <!-- Ads Start -->
            @if($ads->activate == 1)
                @if(isset($ads->site_728x90_banner))
                <div class=" 2xl:flex xl:flex lg:hidden md:hidden sm:hidden hidden py-4 justify-center">
                    {!! base64_decode($ads->site_728x90_banner) !!}
                </div>
                @endif
                @if(isset($ads->site_468x60_banner))
                <div class="flex 2xl:hidden xl:hidden lg:flex md:flex sm:hidden hidden py-4 justify-center">
                    {!!  base64_decode($ads->site_468x60_banner) !!}
                </div>
                @endif
                @if(isset($ads->site_320x100_banner))
                <div class="flex 2xl:hidden xl:hidden lg:hidden md:hidden sm:flex  py-4 justify-center">
                    {!!  base64_decode($ads->site_320x100_banner) !!}
                </div>
                @endif
            @endif
            <!-- Ads End -->

            {{-- Comments --}}
            <div class="w-full px-10 pb-10" id="comment-form">
                <div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400">
                    <div class="flex items-center space-x-1">
                        <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl  font-medium">{{ __("Comments") }}</a>
                    </div>
                </div>
                <div class="w-full">
                    @auth
                        <div class="flex space-x-4 mb-8">
                            <div class="w-20">
                                <img src="{{ asset('/assets/users/') }}{{ Auth::user()->profile_img}}" class="rounded-full ">
                            </div>
                            <div class="w-full">
                                {!! Form::open(array('url'=>'series-comments','method'=>'POST')) !!}
                                <textarea class="rounded bg-gray-800 text-gray-200 p-4 w-full" type="text" name="comments" placeholder="{{ __("write comment") }} .."></textarea>
                                <input type="text" class="hidden" name="series_id" value="{{ $series->id }}">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" class="my-4" name="spoiler"> <span class="text-white text-sm">{{ __("Does this comment contain information, tips or details about the content ?") }}</span>
                                </div>
                                <div>
                                    <button type="submit" class="rounded px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white">{{ __("Write Comment") }}</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    @else
                        <div class="py-5  p-5 bg-gray-800">
                            <span class="text-white"><a href="/login" class="hover:text-yellow-500">{{ __("Login") }}</a> {{ __("or") }} <a href="/register" class="hover:text-yellow-500">{{ __("Join") }}</a> {{ __("to comment") }}</span>
                        </div>
                    @endauth

                    @if ($message = Session::get('success'))
                        <div class="my-3 mb-3 text-white bg-gray-900 p-5" >{{ $message }}</div>
                    @endif

                    <div>
                        @if(count($series->comments) > 0)
                        <ul>
                            @foreach ($series->comments->sortByDesc('id') as $singleComments)
                                @if($singleComments->approve == 1)
                                    <li class="my-4">
                                        <div class="flex space-x-4">
                                            <div class="w-20 " >
                                                <img src="{{ asset('/assets/users/') }}{{ getUsername($singleComments->users_id)->profile_img }}" class="w-15 mx-auto rounded-full border-2 border-yellow-500 border-transparent">
                                            </div>
                                            <div class="w-full bg-gray-700 px-4 py-3 rounded" id="comment_btn">
                                                @auth
                                                    @if($singleComments->users_id != Auth::user()->id)
                                                        @if($singleComments->spoiler == 1)
                                                            <button data-commentid="{{ $singleComments->id }}" id="comment_spoiler_{{ $singleComments->id }}" class="flex items-center text-xs text-white cursor-pointer text-center h-14"><span>{{ __("This comment contains spoilers. Click to read") }}<span></button>
                                                        @endif
                                                    @endif
                                                @endauth
                                                <div class="@auth @if($singleComments->users_id != Auth::user()->id)  @if($singleComments->spoiler == 1)hidden @endif @endif @endauth" id="comment_box_{{ $singleComments->id }}">
                                                    <div class="pb-1"><span class="text-white text-yellow-500 font-bold pr-2"><a href="/&#64;{{ getUsername($singleComments->users_id)->username }}">{{ getUsername($singleComments->users_id)->username }}</a></span> <span class="text-white text-xs">{{ $singleComments->created_at->diffForHumans() }}</span></div>
                                                    <span class="text-white leading-5 text-sm ">
                                                        {{$singleComments->comments}}
                                                    </span>
                                                    @auth
                                                        @if($singleComments->users_id == Auth::user()->id)
                                                            <div class="flex items-center text-sm mt-2 space-x-2">
                                                                <a href="{{ url('/series-delete-comment/'.$singleComments->id) }}" class="focus:outline-none bg-red-800 rounded text-white px-2 py-1 hover:bg-red-900" id="delete" data-id="{{ $series->id }}">
                                                                    {{ __("Delete Comment") }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        @else
                        <div class="p-6 bg-gray-700 rounded">
                        <p class="text-white">{{ __("There is no comment. Be the first to comment!") }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- Sidebar --}}
        <div class="xl:w-3/12 w-full">
            <div class="w-full sm:p-6 space-y-8 px-10">
                @include('frontend.layouts.front-sidebar')
            </div>
        </div>
    </section>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/embed.min.js"></script>
<script>
	$(function(){
		if($('.ui.embed').data('url').length){ // Restore automatic embed initialization
			$('.ui.embed').embed();
		}
        
        // Restore original click handler for all series players (including trailer)
		$('#series-players a').on('click', function(){
			$('#series-player .embed')
			.attr('data-url', $(this).data('url'))
			.embed();

            // Keep this part if it was original and needed for other player buttons
            // $('#series-players span').removeClass("bg-yellow-400"); 
		});

        $('.parent-container').magnificPopup({
            delegate: 'a', // child items selector, by clicking on it popup will open
            type: 'image',
            gallery: {
                enabled: true, // set to true to enable gallery
                preload: [0,2], // read about this option in next Lazy-loading section
                navigateByImgClick: true,
                arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>', // markup of an arrow button
                tPrev: 'Previous (Left arrow key)', // title for left button
                tNext: 'Next (Right arrow key)', // title for right button
                tCounter: '<span class="mfp-counter">%curr% of %total%</span>' // markup of counter
            }
        });

        //Reports
        var $reports = $('#report_id').selectize({
            create: false,
            sortField: {
                field: 'text',
                direction: 'desc'
            },
        });
        var report = $reports[0].selectize;

        //Comments
        $('#comment_btn button').on('click', function(event){
            event.preventDefault();
            $comment_id = $(this).data("commentid");
            console.log($comment_id);
            $("#comment_spoiler_"+$comment_id).addClass("hidden");
            $("#comment_box_"+$comment_id).removeClass("hidden");
        });
	})
</script>

<script>
    $(function() {
        $('#poster-carousel .carousel-items').slick({
            infinite: false,
            slidesToScroll: 5,
            slidesToShow: 5,
            dots: false,
            arrows: false,
            responsive: [
            {
                breakpoint: 1100,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 5
                }
            },
            {
                breakpoint: 1000,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }]
        });

        $('#poster-carousel .menu span:first').on('click', function() {
            $('#poster-carousel .carousel-items').slick('slickPrev');
        });

        $('#poster-carousel .menu span:last').on('click', function() {
            $('#poster-carousel .carousel-items').slick('slickNext');
        });

        $('#backdrop-carousel .carousel-items').slick({
            infinite: false,
            slidesToScroll: 3,
            slidesToShow: 3,
            dots: false,
            arrows: false,
            responsive: [
            {
                breakpoint: 1100,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 1000,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }]
        });

        $('#backdrop-carousel .menu span:first').on('click', function() {
            $('#backdrop-carousel .carousel-items').slick('slickPrev');
        });

        $('#backdrop-carousel .menu span:last').on('click', function() {
            $('#backdrop-carousel .carousel-items').slick('slickNext');
        });

        $('#casts-carousel .carousel-items').slick({
            infinite: false,
            slidesToScroll: 8,
            slidesToShow: 8,
            dots: false,
            arrows: false,
            responsive: [
            {
                breakpoint: 1100,
                settings: {
                    slidesToShow: 8,
                    slidesToScroll: 8
                }
            },
            {
                breakpoint: 1000,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 5
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            }]
        });

        $('#casts-carousel .menu span:first').on('click', function() {
            $('#casts-carousel .carousel-items').slick('slickPrev');
        });

        $('#casts-carousel .menu span:last').on('click', function() {
            $('#casts-carousel .carousel-items').slick('slickNext');
        });
    })
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const activePreRollVideo = @json($activePreRollVideo ?? null);
    const prerollPlayerContainer = document.getElementById('preroll-player-container');
    const prerollVideoElement = document.getElementById('preroll-video-element');
    const mainPlayerContainer = document.getElementById('main-player-container');
    const customPlayButton = document.getElementById('custom-preroll-play-button');
    let skipButton = null; // skipButton'ı burada tanımla
    let clickOverlay = null; // clickOverlay'ı burada tanımla

    console.log('Pre-roll data:', activePreRollVideo);

    if (activePreRollVideo && prerollVideoElement && mainPlayerContainer && customPlayButton) {
        mainPlayerContainer.style.display = 'none';
        prerollPlayerContainer.style.display = 'block';
        prerollVideoElement.src = activePreRollVideo.video_url;

        prerollVideoElement.addEventListener('canplay', function() {
            customPlayButton.style.display = 'block';
            console.log('Pre-roll video can play, play button shown.');
        });

        customPlayButton.addEventListener('click', function() {
            prerollVideoElement.play().then(() => {
                console.log('Pre-roll video playback started by user.');
                customPlayButton.style.display = 'none';
                if (clickOverlay) clickOverlay.style.display = 'block';

                // Geri sayımı video oynamaya BAŞLADIKTAN sonra başlat
                if (activePreRollVideo.skippable_after_seconds && activePreRollVideo.skippable_after_seconds > 0 && !skipButton) { // !skipButton kontrolü tekrar tekrar oluşturmayı engeller
                    skipButton = document.createElement('button');
                    skipButton.classList.add('skip-button');
                    skipButton.innerText = `Reklamı Atla (${activePreRollVideo.skippable_after_seconds}s)`;
                    skipButton.disabled = true;
                    skipButton.style.zIndex = '10010';
                    prerollPlayerContainer.appendChild(skipButton);

                    let countdown = activePreRollVideo.skippable_after_seconds;
                    const interval = setInterval(() => {
                        countdown--;
                        if (countdown > 0) {
                            skipButton.innerText = `Reklamı Atla (${countdown}s)`;
                        } else {
                            clearInterval(interval);
                            skipButton.innerText = 'Reklamı Atla';
                            skipButton.disabled = false;
                            skipButton.addEventListener('click', function(event) {
                                event.stopPropagation();
                                playMainVideo();
                            });
                        }
                    }, 1000);
                }
            }).catch(error => {
                console.error('Pre-roll video playback failed:', error);
                playMainVideo();
            });
        });

        if (activePreRollVideo.target_url) {
            clickOverlay = document.createElement('a');
            clickOverlay.id = 'preroll-click-overlay';
            clickOverlay.href = activePreRollVideo.target_url;
            clickOverlay.target = '_blank';
            clickOverlay.style.cssText = 'position:absolute; top:0; left:0; width:100%; height: calc(100% - 50px); z-index:10005; cursor:pointer; display:none;';
            prerollPlayerContainer.appendChild(clickOverlay);
        }

        prerollVideoElement.addEventListener('ended', playMainVideo);
        prerollVideoElement.addEventListener('error', function() {
            console.error('Pre-roll video error. Skipping to main content.');
            customPlayButton.style.display = 'none'; // Hata durumunda da butonu gizle
            playMainVideo();
        });

        // Geri sayım başlatma mantığı yukarıya, play().then() içine taşındı.
        // if (activePreRollVideo.skippable_after_seconds && activePreRollVideo.skippable_after_seconds > 0) { ... }

    } else {
        console.log('No active pre-roll video or player elements not found. Ensuring main player is visible.');
        if (prerollPlayerContainer) prerollPlayerContainer.style.display = 'none';
        if (mainPlayerContainer) mainPlayerContainer.style.display = 'block';
        const semanticEmbed = mainPlayerContainer ? mainPlayerContainer.querySelector('.ui.embed') : null;
        if (semanticEmbed && !semanticEmbed.classList.contains('active')){
             console.log('Attempting to initialize Semantic UI embed if not active.');
        }
    }

    function playMainVideo() {
        console.log('Switching to main video.');
        if (prerollPlayerContainer) {
            prerollPlayerContainer.style.display = 'none';
        }
        if (mainPlayerContainer) {
            mainPlayerContainer.style.display = 'block';
            const semanticEmbed = mainPlayerContainer.querySelector('.ui.embed');
            if (semanticEmbed) {
                console.log('Main player (Semantic UI embed) made visible. Attempting to reset and play.');
                if (window.jQuery) {
                    jQuery(semanticEmbed).embed('reset');
                    setTimeout(function() {
                        console.log('Attempting to play Semantic UI embed after reset.');
                        jQuery(semanticEmbed).embed('play');
                    }, 100);
                } else {
                    console.warn('jQuery not found, cannot programmatically play Semantic UI embed.');
                }
            } else {
                 console.log('Semantic UI embed component not found in main player.');
            }
        }
    }
});
</script>
