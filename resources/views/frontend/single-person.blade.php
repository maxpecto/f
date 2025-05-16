@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/embed.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/icon.min.css" />
@endsection

@section('content')
@include('frontend.layouts.alpa')

<div class="container mx-auto bg-black flex">
    <section class="flex xl:flex-nowrap flex-wrap w-full xl:space-x-4">
        {{-- Main Contain --}}
        <div class="space-y-8 xl:w-9/12 w-full py-10">
            {{-- Person Details--}}
            <div class="w-full flex lg:px-10 px-0 text-white lg:space-x-6 space-x-0">
                <div class="w-1/5 lg:block hidden">
                    <div class="w-full">
                        <img alt="{{ $persons_data->name }}" title="{{ $persons_data->name }}" class="w-full rounded-t" src="/assets/persons/{{ $persons_data->profile_path }}">
                    </div>
                </div>
                <div class="lg:w-4/5 w-full px-10 lg:px-0">
                    <div class="w-full">
                        <div class="flex justify-between items-center pb-1 mb-4 ">
                            <h1 class="text-4xl text-yellow-500 ">{{ $persons_data->name }}</h1>
                        </div>
                        <div class="mb-1 flex mb-4 flex-col space-y-3 text-sm tracking-widest">
                            <div class="flex items-center space-x-2">
                                <strong class="text-yellow-500">{{ __("Known For") }}</strong><p> {{ $persons_data->known_for_department }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <strong class="text-yellow-500">{{ __("IMDB ID") }}</strong>
                                <p>
                                    @if(!empty($persons_data->imdb_id))
                                        {{ $persons_data->imdb_id }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <strong class="text-yellow-500">{{ __("Gender") }}</strong>
                                <p class="flex items-center space-x-1">
                                    @if($persons_data->gender == 2)
                                        <span class="iconify" data-icon="el:male" data-inline="false"></span><span>{{ __("Male") }}</span>
                                    @elseif($persons_data->gender == 1)
                                        <span class="iconify" data-icon="el:female" data-inline="false"></span><span>{{ __("Female") }}</span>
                                    @else
                                        <span class="iconify" data-icon="fa-solid:transgender" data-inline="false"></span><span>{{ __("Unknown") }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <strong class="text-yellow-500">{{ __("Birthday") }}</strong>
                                <p>
                                    @if(!empty($persons_data->birthday))
                                        {{ $persons_data->birthday }} ({{ $age }} {{ __("years")}})
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <strong class="text-yellow-500">{{ __("Place of Birth")}}</strong>
                                <p>
                                    @if(!empty($persons_data->place_of_birth))
                                        {{ $persons_data->place_of_birth }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <strong class="text-yellow-500">{{ __("Deathday")}}</strong>
                                <p>
                                    @if(!empty($persons_data->deathday))
                                        {{ $persons_data->deathday }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <strong class="text-yellow-500">{{ __("Homepage")}}</strong>
                                <p>
                                    @if(!empty($persons_data->homepage))
                                        <a href="{{ $persons_data->homepage }}" target="_blank">{{ $persons_data->homepage }}</a>
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <strong class="text-yellow-500">{{ __("Popularity")}}</strong>
                                <p class="flex items-center space-x-1">
                                    @if(!empty($persons_data->popularity))
                                        <span class="iconify" data-icon="akar-icons:star" data-inline="false"></span> <span>{{ $persons_data->popularity }}</span>
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Series Overviews / Posters / Backdrops--}}
            <div class="w-full px-10 text-white">
                <div class="mb-4">
                    <div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400">
                        <div class="flex items-center space-x-1 ">
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl font-medium">{{ __("Biography")}}</a>
                        </div>
                    </div>
                    <p class="leading-6 text-sm">{!! nl2br(e($persons_data->biography)) !!} </p>
                </div>
                <div class="mb-4" id="profiles-carousel">
                    <div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400">
                        <div class="flex items-center space-x-1 ">
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl font-medium">{{ __("Profiles")}}</a>
                        </div>
                        <div class="right menu flex space-x-2 items-center">
                            <span class="item mr-1 cursor-pointer"><span class="iconify" data-icon="bx:bxs-left-arrow" data-inline="false"></span></span>
                            <span class="item mr-2 cursor-pointer"><span class="iconify" data-icon="bx:bxs-right-arrow" data-inline="false"></span></span>
                        </div>
                    </div>
                    <div class="bg-gray-900">
                        <div class="carousel-items parent-container">
                            @if(isset($tmdbdata['profiles']) && count($tmdbdata['profiles']) > 0)
                            @foreach($tmdbdata['profiles'] as $profiles)
                                <a href="https://image.tmdb.org/t/p/original{{ $profiles['file_path'] }}" class="items-center object-fill"><img class="p-2" src="https://image.tmdb.org/t/p/w342{{ $profiles['file_path'] }}"/></a>
                            @endforeach
                            @else
                                {{-- Optionally display a message if no profiles are available --}}
                                {{-- <p class="text-white p-4">Profil resmi bulunamadı.</p> --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full px-10">
                <div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400">
                    <div class="flex items-center space-x-1">
                        <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl  font-medium">{{ __("Known For Movies / Series")}}</a>
                    </div>
                </div>
                <div class="w-full grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
                    @if(count($persons_data->items) >= 1)
                        @foreach($persons_data->items as $items)
                            @if($items->type == 'movies')
                                <div class="relative px-2 mb-2">
                                    <div class="slide ">
                                        <div class="card-wrapper">
                                            <a href="/movie/{{ $items->slug }}" title="{{ $items->title }}" >
                                                <div class="card inline-top loaded portrait-card">
                                                    <div class="card-content-wrap">
                                                        <div class="card-image-content">
                                                            <div class="image-card base-card-image">
                                                                <img alt="{{ $items->title }}" title="{{ $items->title }}" class="original-image" src="/assets/movies/poster/{{ $items->poster }}">
                                                            </div>
                                                            <div>
                                                                <div class="card-overlay show-icon">
                                                                    <span class="absolute left-0 bottom-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mb-1 uppercase text-xs">{{ __("Movies") }}</span>
                                                                    <div class="absolute right-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 mr-1 mt-1 uppercase text-xs flex items-center rounded">
                                                                        <span class="iconify mr-1" data-icon="akar-icons:star" data-inline="false"></span>{{ $items->rating }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-details text-white w-40" style="overflow: hidden;text-overflow: ellipsis;">
                                                            <h3 class="text-overflow card-header">{{ $items->title }}</h3>
                                                            <div class="text-overflow card-subheader">
                                                                @foreach ($items->genres as $singleGenre)
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
                            @else
                                <div class="relative px-2 mb-2">
                                    <div class="slide ">
                                        <div class="card-wrapper">
                                            <a href="/series/{{ $items->slug }}" title="{{ $items->title }}" >
                                                <div class="card inline-top loaded portrait-card">
                                                    <div class="card-content-wrap">
                                                        <div class="card-image-content">
                                                            <div class="image-card base-card-image">
                                                                <img alt="{{ $items->title }}" title="{{ $items->title }}" class="original-image" src="/assets/series/poster/{{ $items->poster }}">
                                                            </div>
                                                            <div>
                                                                <div class="card-overlay show-icon">
                                                                    <span class="absolute left-0 bottom-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mb-1 uppercase text-xs">{{ __("Series") }}</span>
                                                                    <div class="absolute right-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 mr-1 mt-1 uppercase text-xs flex items-center rounded">
                                                                        <span class="iconify mr-1" data-icon="akar-icons:star" data-inline="false"></span>{{ $items->rating }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-details text-white w-40" style="overflow: hidden;text-overflow: ellipsis;">
                                                            <h3 class="text-overflow card-header">{{ $items->title }}</h3>
                                                            <div class="text-overflow card-subheader">
                                                                @foreach ($items->genres as $singleGenre)
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
                            @endif
                        @endforeach
                    @else
                        <div class="flex items-center text-yellow-400"><span>{{ __("There is no items!") }}</span></div>
                    @endif
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
<script>
    $(function() {
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

        $('#profiles-carousel .carousel-items').slick({
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

        $('#profiles-carousel .menu span:first').on('click', function() {
            $('#profiles-carousel .carousel-items').slick('slickPrev');
        });

        $('#profiles-carousel .menu span:last').on('click', function() {
            $('#profiles-carousel .carousel-items').slick('slickNext');
        });
    })
</script>
@endpush
