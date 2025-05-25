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
    #custom-preroll-play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10008; /* Diğer overlay'lerden uygun şekilde ayarlanmış */
        padding: 12px 25px;
        font-size: 18px; /* Boyut ayarlanabilir */
        cursor: pointer;
        background-color: rgba(0,0,0,0.6);
        color: white;
        border: 1.5px solid white;
        border-radius: 8px;
        display: none; /* JS ile gösterilecek */
        transition: opacity 0.3s ease; /* Yumuşak geçiş için */
    }
    #custom-preroll-play-button:hover {
        background-color: rgba(0,0,0,0.8);
    }
</style>

@endsection

@section('content')
@include('frontend.layouts.alpa')

<div class="container mx-auto bg-black flex">
    <section class="flex xl:flex-nowrap flex-wrap w-full xl:space-x-4">
        {{-- Main Contain --}}
        <div class="space-y-8 xl:w-9/12 w-full">

            {{-- Player  --}}
            {{-- Ön Yükleme Video Oynatıcısı Başlangıcı --}}
            @if(isset($activePreRollVideo) && $activePreRollVideo)
                <div id="preroll-player-container" 
                     data-preroll-video="{{ json_encode($activePreRollVideo->toArray(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) }}"
                     style="background-color: black; position: relative; width: 100%; margin: auto; aspect-ratio: 16/9; display: none;">
                    <video id="preroll-video-element" width="100%" height="100%" preload="auto" style="display: block; width: 100%; height: 100%;" playsinline></video>
                    <button id="custom-preroll-play-button">OYNAT</button>
                    <a id="preroll-skip-button" href="#" style="display:none; position:absolute; bottom:20px; right:20px; padding:8px 15px; background:rgba(0,0,0,0.7); color:white; text-decoration:none; z-index:10010; border-radius: 5px; font-size: 0.9em;">
                        Reklamı Atla
                    </a>
                    <a id="preroll-click-overlay" href="#" target="_blank" style="position:absolute; top:0; left:0; width:100%; height: calc(100% - 50px); z-index:10005; cursor:pointer; display:none;"></a>
                </div>
            @endif
            {{-- Ön Yükleme Video Oynatıcısı Sonu --}}

            {{-- Ana İçerik Oynatıcısı --}}
            <div id="main-player-container" >
            <x-episodes-player :episode="$episode" :player="$player" :allepisodesforseasons="$allepisodesforseasons"/>
            </div>

            {{-- Details--}}
            <div class="w-full flex lg:px-10 px-0 text-white lg:space-x-6 space-x-0">
                <div class="w-1/5 lg:block hidden">
                    <div class="w-full">
                        <img alt="{{ $series->title }} (S{{ $episode->season_id }}E{{ $episode->episode_id }})" title="{{ $series->title }} (S{{ $episode->season_id }}E{{ $episode->episode_id }})" class="w-full rounded-t" src="/assets/series/poster/{{ $series->poster }}">
                    </div>
                    <div class="flex items-center w-full" id="episode-players">
                        <a href="javascript:void(0)" id="trailer" data-type="trailer" data-url="{{ $series->trailer }}" class="trailer w-full bg-yellow-500 px-4 py-2 rounded-b text-white text-center hover:bg-yellow-400 hover:text-white">{{ __("Trailer") }}</a>
                    </div>
                </div>
                <div class="lg:w-4/5 w-full px-10 lg:px-0">
                    <div class="w-full flex sm:flex-nowrap flex-wrap sm:space-y-0 space-y-2">
                        <div class="sm:w-4/5 w-full">
                            <div class="flex justify-between items-center">
                                <h1 class="text-2xl text-yellow-500 mb-2"><a href="/series/{{ $series->slug }}">{{ $series->title }} (S{{ $episode->season_id }}E{{ $episode->episode_id }})</a></h1>
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
                                <h1 class="text-yellow-500">{{ __("Air Date") }}</h1>
                                <p class="leading-6 text-sm">{{ $episode->air_date }}</p>
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
                        <div class="w-1/5 sm:w-1/5 w-full">
                            <div class="flex mb-2 justify-end items-center text-sm">
                                <span>{{ $episode->views }} {{ __("Views") }}</span>
                            </div>
                            <div class="block border-2 rounded border-yellow-400 mb-2">
                            </div>
                            <div class="flex mb-2 justify-end items-center space-x-6 text-base">
                                {{-- <x-episode-like-buttons :items="$episode" :totalLikes="$totalLikes" :totalDislikes="$totalDislikes" :isLikedByCurrentUser="$is_liked_by_current_user" :isDislikedByCurrentUser="$is_disliked_by_current_user"/> --}}
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
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl font-medium">{{ __("Episode Overviews") }}</a>
                        </div>
                    </div>
                    <p class="leading-6">{{ $episode->description }}</p>
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
                        <div class="carousel-items parent-container">
                            @foreach($series->actors as $actor)
                                <a href="/person/{{ $actor->tmdb_id }}-@php echo strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $actor->name)));  @endphp">
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
                        <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl  font-medium">{{ __("Download") }}</a>
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
                                {!! Form::open(array('url'=>'episodes-comments','method'=>'POST')) !!}
                                <textarea class="rounded bg-gray-800 text-gray-200 p-4 w-full" type="text" name="comments" placeholder="{{ __("Comments") }}write comment .."></textarea>
                                <input type="text" class="hidden" name="episodes_id" value="{{ $episode->id }}">
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
                        @if(count($episode->comments) > 0)
                        <ul>
                            @foreach ($episode->comments->sortByDesc('id') as $singleComments)
                                @if($singleComments->approve == 1)
                                    <li class="my-4">
                                        <div class="flex space-x-4">
                                            <div class="w-20 " >
                                                @if ($singleComments->user)
                                                    <img src="{{ asset('/assets/users/') }}{{ $singleComments->user->profile_img }}" class="w-15 mx-auto rounded-full border-2 border-yellow-500 border-transparent">
                                                @else
                                                    <img src="{{ asset('/assets/users/default.png') }}" class="w-15 mx-auto rounded-full border-2 border-yellow-500 border-transparent">
                                                @endif
                                            </div>
                                            <div class="w-full bg-gray-700 px-4 py-3 rounded" id="comment_btn">
                                                @auth
                                                    @if($singleComments->users_id != Auth::user()->id)
                                                        @if($singleComments->spoiler == 1)
                                                        <div class="flex items-center justify-center ">
                                                            <button data-commentid="{{ $singleComments->id }}" id="comment_spoiler_{{ $singleComments->id }}" class="text-xs text-white cursor-pointer text-center h-14 focus:outline-none"><span>{{ __("This comment contains spoilers. Click to read") }}<span></button>
                                                        </div>
                                                        @endif
                                                    @endif
                                                @endauth
                                                <div class="@auth @if($singleComments->users_id != Auth::user()->id)  @if($singleComments->spoiler == 1)hidden @endif @endif @endauth" id="comment_box_{{ $singleComments->id }}">
                                                    <div class="pb-1">
                                                        @if ($singleComments->user)
                                                            <span class="text-white text-yellow-500 font-bold pr-2"><a href="/&#64;{{ $singleComments->user->username }}">{{ $singleComments->user->username }}</a></span>
                                                        @else
                                                            <span class="text-white text-yellow-500 font-bold pr-2">Kullanıcı Bulunamadı</span>
                                                        @endif
                                                        <span class="text-white text-xs">{{ $singleComments->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <span class="text-white leading-5 text-sm ">
                                                        {{$singleComments->comments}}
                                                    </span>
                                                    @auth
                                                        @if($singleComments->users_id == Auth::user()->id)
                                                            <div class="flex items-center text-sm mt-2 space-x-2">
                                                                <a href="{{ url('/episodes-delete-comment/'.$singleComments->id) }}" class="focus:outline-none bg-red-800 rounded text-white px-2 py-1 hover:bg-red-900" id="delete" data-id="{{ $episode->id }}">
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

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/embed.min.js"></script>
<script>
	$(function(){
        // Player (Orijinal player ile ilgili yorumlu kodlar varsa buraya eklenebilir, şimdilik boş bırakıyorum)
        // ...

        $('.parent-container').magnificPopup({
            delegate: 'a',
            type: 'image',
            gallery: {
                enabled: true,
                preload: [0,2],
                navigateByImgClick: true,
                arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
                tPrev: 'Previous (Left arrow key)',
                tNext: 'Next (Right arrow key)',
                tCounter: '<span class="mfp-counter">%curr% of %total%</span>'
            }
        });

        var $reports = $('#report_id').selectize({
            create: false,
            sortField: {
                field: 'text',
                direction: 'desc'
            },
        });

        $('#comment_btn button').on('click', function(event){
            event.preventDefault();
            $comment_id = $(this).data("commentid");
            console.log("Comment ID for spoiler: ", $comment_id);
            $("#comment_spoiler_"+$comment_id).addClass("hidden");
            $("#comment_box_"+$comment_id).removeClass("hidden");
        });

        // Slick Carousel Ayarları
        $('#poster-carousel .carousel-items').slick({
            infinite: false, slidesToScroll: 5, slidesToShow: 5, dots: false, arrows: false,
            responsive: [{breakpoint: 1100, settings: {slidesToShow: 5, slidesToScroll: 5}}, {breakpoint: 1000, settings: {slidesToShow: 3, slidesToScroll: 3}}, {breakpoint: 600, settings: {slidesToShow: 2, slidesToScroll: 2}}]
        });
        $('#poster-carousel .menu span:first').on('click', function() { $('#poster-carousel .carousel-items').slick('slickPrev'); });
        $('#poster-carousel .menu span:last').on('click', function() { $('#poster-carousel .carousel-items').slick('slickNext'); });

        $('#backdrop-carousel .carousel-items').slick({
            infinite: false, slidesToScroll: 3, slidesToShow: 3, dots: false, arrows: false,
            responsive: [{breakpoint: 1100, settings: {slidesToShow: 3, slidesToScroll: 3}}, {breakpoint: 1000, settings: {slidesToShow: 2, slidesToScroll: 2}}, {breakpoint: 600, settings: {slidesToShow: 1, slidesToScroll: 1}}]
        });
        $('#backdrop-carousel .menu span:first').on('click', function() { $('#backdrop-carousel .carousel-items').slick('slickPrev'); });
        $('#backdrop-carousel .menu span:last').on('click', function() { $('#backdrop-carousel .carousel-items').slick('slickNext'); });

        $('#casts-carousel .carousel-items').slick({
            infinite: false, slidesToScroll: 8, slidesToShow: 8, dots: false, arrows: false,
            responsive: [{breakpoint: 1100, settings: {slidesToShow: 8, slidesToScroll: 8}}, {breakpoint: 1000, settings: {slidesToShow: 5, slidesToScroll: 5}}, {breakpoint: 600, settings: {slidesToShow: 3, slidesToScroll: 3}}]
        });
        $('#casts-carousel .menu span:first').on('click', function() { $('#casts-carousel .carousel-items').slick('slickPrev'); });
        $('#casts-carousel .menu span:last').on('click', function() { $('#casts-carousel .carousel-items').slick('slickNext'); });
    }); // Orijinal $(function() sonu

    // ------------- PRE-ROLL JS BAŞLANGICI (BİRLEŞTİRİLMİŞ VE DATA ATTRIBUTE KULLANILACAK)-------------
    console.log('Pushed JS block started (single-episode)');
    document.addEventListener('DOMContentLoaded', function () {
        console.log('DOM Content Loaded - Pre-roll script start (single-episode)');
        const preRollPlayerContainer = document.getElementById('preroll-player-container');
        const mainPlayerContainer = document.getElementById('main-player-container');

        let isVideoStartedOnce = false; // Videonun ilk kez başlatılıp başlatılmadığını takip et

        function switchToMainContent() {
            const preRollVideoElement = document.getElementById('preroll-video-element');
            console.log("switchToMainContent çağrıldı (single-episode).");
            if (preRollVideoElement && preRollVideoElement.src && preRollVideoElement.src !== '' && !preRollVideoElement.paused) {
                preRollVideoElement.pause();
            }
            if(preRollPlayerContainer) preRollPlayerContainer.style.display = 'none';
            if(mainPlayerContainer) mainPlayerContainer.style.display = 'block';

            const videoJsPlayerEl = mainPlayerContainer ? mainPlayerContainer.querySelector('#playerVideojs') : null;
            const iframePlayerEl = mainPlayerContainer ? mainPlayerContainer.querySelector('#playerEmbeded') : null;

            if (videoJsPlayerEl && typeof videojs !== 'undefined' && videojs.getPlayer(videoJsPlayerEl.id)) {
                const mainVideoPlayer = videojs.getPlayer(videoJsPlayerEl.id);
                console.log("Ana Video.js oynatıcısı bulundu ve oynatılıyor (single-episode).");
                // mainVideoPlayer.play(); // Kullanıcı etkileşimi sonrası oynatılmalı
            } else if (iframePlayerEl && iframePlayerEl.querySelector('iframe')) {
                const iframe = iframePlayerEl.querySelector('iframe');
                const iframeSrc = iframe.getAttribute('src');
                console.log("Ana iframe oynatıcısı bulundu (single-episode):", iframeSrc);
            } else {
                console.log("Ana oynatıcı (Video.js veya iframe) main-player-container içinde bulunamadı (single-episode).");
            }
        }

        if (preRollPlayerContainer && preRollPlayerContainer.hasAttribute('data-preroll-video')) {
            const preRollVideoDataString = preRollPlayerContainer.getAttribute('data-preroll-video');
            let activePreRollVideo = null;
            try {
                if (preRollVideoDataString && preRollVideoDataString.trim() !== '') {
                    activePreRollVideo = JSON.parse(preRollVideoDataString);
                }
            } catch (e) {
                console.error("Pre-roll data parse error (single-episode):", e, preRollVideoDataString);
                switchToMainContent();
                return; 
            }

            console.log("activePreRollVideo (from data attribute - single-episode.blade.php):", activePreRollVideo);

            const preRollVideoElement = document.getElementById('preroll-video-element');
            const preRollSkipButton = document.getElementById('preroll-skip-button');
            const clickOverlay = document.getElementById('preroll-click-overlay');
            const customPlayButton = document.getElementById('custom-preroll-play-button');

            function playPreRollAndSetupOverlays() {
                if (preRollVideoElement.paused) {
                    preRollVideoElement.play().then(() => {
                        console.log("Pre-roll video başlatıldı (playPreRollAndSetupOverlays).");
                        isVideoStartedOnce = true;
                        if (customPlayButton) customPlayButton.style.display = 'none';
                        if (clickOverlay && activePreRollVideo.target_url) {
                            clickOverlay.href = activePreRollVideo.target_url;
                            clickOverlay.style.display = 'block';
                            console.log("Click overlay aktif edildi.");
                        }
                    }).catch(error => {
                        console.error("Pre-roll video başlatılamadı (playPreRollAndSetupOverlays):", error);
                        switchToMainContent(); // Oynatma hatasında ana içeriğe geç
                    });
                }
            }

            if (preRollVideoElement && activePreRollVideo && activePreRollVideo.video_url) {
                console.log("Pre-roll video_url (single-episode):", activePreRollVideo.video_url);
                if(mainPlayerContainer) mainPlayerContainer.style.display = 'none';
                preRollPlayerContainer.style.display = 'block'; 
                preRollVideoElement.src = activePreRollVideo.video_url;
                preRollVideoElement.controls = false;
                preRollVideoElement.muted = false;

                preRollVideoElement.oncanplay = function() {
                    console.log("Pre-roll oncanplay tetiklendi. OYNAT butonu gösteriliyor.");
                    if (customPlayButton && !isVideoStartedOnce) { // Eğer video henüz başlamadıysa butonu göster
                        customPlayButton.style.display = 'block';
                    }
                };

                // Video oynatılmaya başlandığında OYNAT butonunu gizle (her ihtimale karşı)
                preRollVideoElement.onplay = function() {
                    console.log("Pre-roll onplay event. OYNAT butonu gizleniyor.");
                    if (customPlayButton) customPlayButton.style.display = 'none';
                    // Eğer video oynuyorsa ve click overlay aktif olmalıysa (ama henüz değilse)
                    // Bu durum normalde playPreRollAndSetupOverlays içinde ele alınır
                    // Ancak bazı tarayıcı otomatik oynatma senaryolarında burası da yedek olabilir.
                    if (isVideoStartedOnce && clickOverlay && activePreRollVideo.target_url && clickOverlay.style.display === 'none') {
                        clickOverlay.href = activePreRollVideo.target_url;
                        clickOverlay.style.display = 'block';
                        console.log("Click overlay (onplay event'inden) aktif edildi.");
                    }
                };

                if (customPlayButton) {
                    customPlayButton.addEventListener('click', function(event) {
                        event.stopPropagation(); // Video elementine tıklama gitmesin
                        console.log("Özel OYNAT butonuna tıklandı.");
                        playPreRollAndSetupOverlays();
                    });
                }

                preRollVideoElement.addEventListener('click', function(event) {
                    if (!isVideoStartedOnce) {
                        // event.preventDefault(); // Zaten customPlayButton click'i bunu yapar veya playPreRoll direkt çalışır
                        // event.stopPropagation(); // Üsttekiyle aynı
                        console.log("Videoya tıklandı (başlamamışken). Oynatma deneniyor.");
                        playPreRollAndSetupOverlays();
                    } else {
                        console.log("Video oynuyor, tıklama overlay tarafından yönetilmeli.");
                    }
                });

                preRollVideoElement.onended = function() {
                    console.log("Pre-roll video bitti (single-episode).");
                    switchToMainContent();
                };

                preRollVideoElement.onerror = function(e) {
                    console.error("Reklam videosu yüklenirken hata oluştu (single-episode). Video src:", preRollVideoElement.src, "Error Event:", e);
                    switchToMainContent();
                };
                
                if (activePreRollVideo.skippable_after_seconds && parseInt(activePreRollVideo.skippable_after_seconds, 10) > 0) {
                    let skippableTime = parseInt(activePreRollVideo.skippable_after_seconds, 10);
                    if(preRollSkipButton) {
                        preRollSkipButton.innerText = `Reklamı Atla (${skippableTime}s)`;
                        preRollSkipButton.style.display = 'block'; // Başlangıçta görünür
                        preRollSkipButton.style.pointerEvents = 'none'; // Tıklanamaz yap
                        preRollSkipButton.style.opacity = '0.6'; // Biraz soluk göster
                    }

                    preRollVideoElement.ontimeupdate = function() {
                        const currentTime = Math.floor(preRollVideoElement.currentTime);
                        if (!preRollVideoElement.seeking && currentTime >= skippableTime) {
                            if(preRollSkipButton) {
                                preRollSkipButton.innerText = 'Reklamı Atla';
                                preRollSkipButton.style.pointerEvents = 'auto'; // Tıklanabilir yap
                                preRollSkipButton.style.opacity = '1'; // Normal görünüm
                                // Olay dinleyicisini burada kaldırmak iyi olabilir, bir kez aktif ettikten sonra gereksiz yere çalışmasın.
                                // preRollVideoElement.ontimeupdate = null; 
                            }
                        } else if (currentTime < skippableTime && preRollSkipButton) {
                             preRollSkipButton.innerText = `Reklamı Atla (${skippableTime - currentTime}s)`;
                        }
                    };
                } else if (preRollSkipButton) { 
                    preRollSkipButton.style.display = 'none'; // Atlanamazsa butonu tamamen gizle
                }

                // Bu kontrol yukarıdaki blokla birleştirilebilir veya ayrı kalabilir, şu anki haliyle de çalışır.
                if(preRollSkipButton && (!activePreRollVideo.skippable_after_seconds || parseInt(activePreRollVideo.skippable_after_seconds, 10) <= 0)){
                     preRollSkipButton.style.display = 'none'; // skippable_after_seconds yoksa veya 0 ise de gizle
                }

                if(preRollSkipButton) {
                    preRollSkipButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log("Reklamı atla tıklandı (single-episode).");
                        switchToMainContent();
                    });
                }

                // Şimdilik clickOverlay'i her zaman gizli tutalım ki video tıklamasıyla çakışmasın.
                if (clickOverlay) {
                    clickOverlay.style.display = 'none';
                }
            } else {
                console.log("Pre-roll koşulları sağlanamadı (video_url eksik veya activePreRollVideo null) veya video URL yok (data attribute). Ana içerik gösteriliyor (single-episode).");
                switchToMainContent();
            }
        } else {
            console.log("Pre-roll player container or data attribute not found. Showing main content (single-episode).");
            switchToMainContent();
        }
    });

    // Dil değiştirme script'i
    $(function(){
        var langChangeUrl = "https://filmavcisi1.com/lang/change";
        console.log('Language changer script setup and ready (single-episode)');
        $(".changeLangMobile").change(function(){
            console.log('Language changed to: ' + $(this).val() + ' via mobile changer');
            window.location.href = langChangeUrl + "?lang="+ $(this).val();
        });
    });
</script>
@endpush
