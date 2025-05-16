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
    #movie-players .image {
        float: right;
        position: relative;
        margin: 0;
        width: 20px;
    }
    #movie-players.bottom.attached.menu {
        overflow-y: hidden;
        overflow-x: auto;
        padding: 0.5rem 0.25rem;
        background: #1f2022;
        border-bottom: 0;
    }
    #movie-players.bottom.attached.menu a {
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
    #movie-players.bottom.attached.menu a.trailer {
        background: #ff4335;
        margin-left: auto;
        margin-right: 0.25rem;
    }
    #movie-players.bottom.attached.menu a:hover {
        -webkit-filter: brightness(1.2) !important;
        -moz-filter: brightness(1.2) !important;
        -o-filter: brightness(1.2) !important;
        -ms-filter: brightness(1.2) !important;
        filter: brightness(1.2) !important;
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
            <x-player :movies="$movies" :player="$player" />
            {{-- Details--}}
            <div class="w-full flex lg:px-10 px-0 text-white lg:space-x-6 space-x-0">
                <div class="w-1/5 lg:block hidden">
                    <div class="w-full">
                        <img alt="{{ $movies->title }}" title="{{ $movies->title }}" class="w-full rounded-t" src="/assets/movies/poster/{{ $movies->poster }}">
                    </div>
                    @if(isset($movies->trailer))
                    <div class="flex items-center w-full" id="movie-players">
                        <a href="javascript:void(0)" id="trailer" data-type="trailer" data-url="{{ $movies->trailer }}" class="trailer w-full bg-yellow-500 px-4 py-2 rounded-b text-white text-center hover:bg-yellow-400 hover:text-white">{{ __("Trailer") }}</a>
                    </div>
                    @endif
                </div>
                <div class="lg:w-4/5 w-full px-10 lg:px-0">
                    <div class="w-full flex sm:flex-nowrap flex-wrap sm:space-y-0 space-y-2">
                        <div class="sm:w-3/4 w-full">
                            <div class="flex justify-between items-center">
                                <h1 class="text-2xl text-yellow-500 mb-2">{{ $movies->title }} ({{ date('Y',strtotime($movies->release_date)) }})</h1>
                            </div>
                            <div class="mb-1 flex mb-4 sm:flex-nowrap flex-wrap">
                                @if(count($movies->genres) > 0)
                                    @php
                                        $num_of_items = count($movies->genres);
                                        $num_count = 0;
                                    @endphp
                                    @foreach ($movies->genres as $singleGenre)
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
                                <p class="leading-6 text-sm">{{ $movies->release_date }}</p>
                            </div>
                            <div class="mb-1 flex items-center space-x-4 tracking-wide pb-1">
                                <h1 class="text-yellow-500">{{ __("Rating") }}</h1>
                                <p class="leading-6 text-sm">{{ $movies->rating }}</p>
                            </div>
                            <div class="mb-1 flex items-center space-x-4 tracking-wide pb-1">
                                <h1 class="text-yellow-500">{{ __("Duration") }}</h1>
                                <p class="leading-6 text-sm">{{ $movies->duration }} {{ __("min") }}</p>
                            </div>
                            <div class="mb-1 flex items-center space-x-4 tracking-wide pb-1">
                                <h1 class="text-yellow-500">{{ __("Countries") }}</h1>
                                <p class="leading-6 text-sm flex flex-wrap items-center">
                                    @if(count($movies->countries) > 0)
                                        @php
                                            $num_of_items = count($movies->countries);
                                            $num_count = 0;
                                        @endphp
                                        @foreach ($movies->countries as $singleCountrie)
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
                                    @if(count($movies->keywords) > 0)
                                        @php
                                            $num_of_items = count($movies->keywords);
                                            $num_count = 0;
                                        @endphp
                                        @foreach ($movies->keywords as $singlekeywords)
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
                                    @if(count($movies->qualities) > 0)
                                        @php
                                            $num_of_items = count($movies->qualities);
                                            $num_count = 0;
                                        @endphp
                                        @foreach ($movies->qualities as $singlequalities)
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
                                <span class="tracking-widest uppercase">{{ $movies->views }} {{ __("Views") }}</span>
                            </div>
                            <div class="block border-2 rounded border-yellow-400 mb-2"></div>
                            <div class="flex mb-2 justify-end items-center space-x-6 text-base">
                                <x-like-buttons :items="$movies" :totalLikes="$totalLikes" :totalDislikes="$totalDislikes"/>
                            </div>
                            <div class="block mb-2 ">
                                <x-watchlists-buttons :items="$movies"/>
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
                    <p class="leading-6">{{ $movies->overviews }}</p>
                </div>
                @if(!empty($movies->actors))
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
                                @foreach($movies->actors as $actor)
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
                @endif
                @if(!empty($tmdbdata['posters']))
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
                                @foreach($tmdbdata['posters'] as $posters)
                                    <a href="https://image.tmdb.org/t/p/original{{ $posters['file_path'] }}" class="items-center object-fill"><img class="p-2" src="https://image.tmdb.org/t/p/w342{{ $posters['file_path'] }}"/></a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                @if(!empty($tmdbdata['backdrops']))
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
                                @foreach($tmdbdata['backdrops'] as $backdrops)
                                    <a href="https://image.tmdb.org/t/p/original{{ $backdrops['file_path'] }}"><img class="p-2" src="https://image.tmdb.org/t/p/w500{{ $backdrops['file_path'] }}"/></a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
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
				<div class="w-full ">
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

            {{-- Related --}}
            <div class="w-full px-10">
                <div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400">
                    <div class="flex items-center space-x-1">
                        <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl  font-medium">{{ __("Related Movies") }}</a>
                    </div>
                </div>
                <div class="w-full grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
                    @if(count($relatedmovies) >= 1)
                        @foreach($relatedmovies as $homerecommended)
                            <div class="relative w-52 mb-2 mx-2">
                                <div class="slide ">
                                    <div class="card-wrapper">
                                        <a href="/movie/{{ $homerecommended->slug }}" title="{{ $homerecommended->title }}" >
                                            <div class="card inline-top loaded portrait-card">
                                                <div class="card-content-wrap">
                                                    <div class="card-image-content">
                                                        <div class="image-card base-card-image">
                                                            <img alt="{{ $homerecommended->title }}" title="{{ $homerecommended->title }}" class="original-image" src="/assets/movies/poster/{{ $homerecommended->poster }}">
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
                        <div class="flex items-center text-yellow-400"><span>{{ __("There is no related movies!") }}</span></div>
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
                        <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl font-medium">{{ __("Comments") }}</a>
                    </div>
                </div>
                <div class="w-full">
                    @auth
                        <div class="flex space-x-4 mb-8">
                            <div class="w-20">
                                <img src="{{ asset('/assets/users/') }}{{ Auth::user()->profile_img}}" class="rounded-full ">
                            </div>
                            <div class="w-full">
                                {!! Form::open(array('url'=>'movie-comments','method'=>'POST')) !!}
                                <textarea class="rounded bg-gray-800 text-gray-200 p-4 w-full" type="text" name="comments" placeholder="{{ __("write comment") }} .."></textarea>
                                <input type="text" class="hidden" name="movie_id" value="{{ $movies->id }}">
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
                        @if(count($movies->comments) > 0)
                        <ul>
                            @foreach ($movies->comments->sortByDesc('id') as $singleComments)
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
                                                                <a href="{{ url('/movie-delete-comment/'.$singleComments->id) }}" class="focus:outline-none bg-red-800 rounded text-white px-2 py-1 hover:bg-red-900" id="delete" data-id="{{ $movies->id }}">
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

@endpush
