@extends('layouts.app')

@section('content')

@include('frontend.layouts.alpa')

<div class="container mx-auto bg-gray-900 flex">
    <div class="w-full">
        {{-- Trending Section --}}
        <section class="flex flex-col mt-5 px-6 bg-gray-900">
            <div id="top-episodes" class="my-4">
                <div class="flex items-center justify-between mb-2 px-2">
                    <div class="left">
                        <span class="item cursor-pointer text-white"><span class="iconify" data-icon="bx:bxs-left-arrow" data-inline="false"></span></span>
                    </div>
                    <span class="text-white uppercase">{{ __('Trending') }}</span>
                    <div class="right">
                        <span class="item cursor-pointer text-white"><span class="iconify" data-icon="bx:bxs-right-arrow" data-inline="false"></span></span>
                    </div>
                </div>
                <div>
                    <div class="episodes-items">
                        @foreach($hometrendings as $item)
                            <div class="relative w-full">
                                <div class="slide w-full" style="min-height: 300px;">
                                    <div class="card-wrapper w-full">
                                        <a href="/{{ $item->type == 'movies' ? 'movie' : 'series' }}/{{ $item->slug }}" title="{{ $item->title }}" >
                                            <div class="card inline-top loaded portrait-card rounded-lg">
                                                <div class="card-content-wrap" style="position: relative;">
                                                    <div class="card-image-content">
                                                        <div class="image-card base-card-image">
                                                            @php
                                                                $trendingPosterPath = $item->poster;
                                                                $trendingWebpPath = Str::replaceLast(pathinfo($trendingPosterPath, PATHINFO_EXTENSION), 'webp', $trendingPosterPath);
                                                            @endphp
                                                            <picture>
                                                                @if (Storage::disk('public')->exists($trendingWebpPath))
                                                                    <source srcset="{{ Storage::url($trendingWebpPath) }}" type="image/webp">
                                                                @endif
                                                                @if (Storage::disk('public')->exists($trendingPosterPath))
                                                                    <source srcset="{{ Storage::url($trendingPosterPath) }}" type="image/{{ pathinfo($trendingPosterPath, PATHINFO_EXTENSION) }}">
                                                                    <img {{ $loop->first ? 'fetchpriority=high' : 'loading=lazy' }} alt="{{ $item->title }}" title="{{ $item->title }}" class="original-image" src="{{ Storage::url($trendingPosterPath) }}">
                                                                @else
                                                                    <img src="{{ asset('assets/frontend/images/default_poster.jpg') }}" alt="{{ $item->title }}" class="image_slider_img original-image" loading="lazy">
                                                                @endif
                                                            </picture>
                                                        </div>
                                                        <div>
                                                            <div class="card-overlay show-icon">
                                                                <span class="absolute left-0 bottom-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mb-1 uppercase text-xs">{{ $item->type == 'movies' ? __('Movies') : __('Series') }}</span>
                                                                <div class="absolute right-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 mr-1 mt-1 uppercase text-xs flex items-center">
                                                                    <span class="iconify mr-1" data-icon="akar-icons:star" data-inline="false"></span>{{ $item->rating }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-details-bottom text-white">
                                                        <h3 class="card-header">{{ $item->title }}</h3>
                                                        <div class="card-subheader">
                                                            @foreach ($item->genres->take(3) as $singleGenre)  {{-- Show max 3 genres --}}
                                                                <span class="genre-pill">{{ $singleGenre->name }}</span>
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
                    </div>
                </div>
            </div>
            <style>
                /* Styling for the Trending/Episodes Carousel */
                #top-episodes .episodes-items .slick-slide {
                    transition: transform 0.4s ease-in-out, opacity 0.4s ease-in-out;
                    opacity: 0.7; /* Non-centered items are slightly faded */
                    transform: scale(0.9); /* Non-centered items are slightly smaller */
                    padding: 0 8px; /* Add some horizontal spacing for items */
                }

                #top-episodes .episodes-items .slick-center {
                    opacity: 1;
                    transform: scale(1.05); /* Enlarge the centered item slightly more */
                    z-index: 10;
                }

                #top-episodes .episodes-items .slick-center .portrait-card {
                    box-shadow: 0 0 25px 8px rgba(250, 204, 21, 0.45);
                    transition: box-shadow 0.4s ease-in-out;
                }

                /* Card Details Styling */
                .card-details-bottom {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    padding: 0.75rem;
                    background-image: linear-gradient(to top, rgba(0,0,0,0.9) 20%, rgba(0,0,0,0.7) 50%, rgba(0,0,0,0) 100%);
                    max-height: 50%;
                    overflow: hidden;
                    border-bottom-left-radius: 0.25rem;
                    border-bottom-right-radius: 0.25rem;
                }

                .card-details-bottom .card-header {
                    font-size: 0.9rem;
                    font-weight: 600;
                    margin-bottom: 0.35rem;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    color: #fff;
                }

                .card-details-bottom .card-subheader {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.3rem;
                }

                .genre-pill {
                    display: inline-block;
                    padding: 0.15rem 0.45rem;
                    font-size: 0.65rem;
                    color: #1a202c;
                    background-color: #facc15;
                    border-radius: 0.25rem;
                    line-height: 1.2;
                    font-weight: 500;
                }

                /* Styling for Carousel Arrows (within the header) */
                #top-episodes .left span.item,
                #top-episodes .right span.item {
                    background-color: rgba(40, 40, 40, 0.7);
                    border-radius: 50%;
                    padding: 8px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    transition: background-color 0.3s ease, transform 0.3s ease;
                    color: #fff;
                }

                #top-episodes .left span.item:hover,
                #top-episodes .right span.item:hover {
                    background-color: rgba(250, 204, 21, 0.8);
                    transform: scale(1.1);
                }

                #top-episodes .left span.item .iconify,
                #top-episodes .right span.item .iconify {
                    font-size: 20px;
                }

                #top-episodes .flex.items-center.justify-between {
                    margin-bottom: 1rem;
                }
            </style>
            <script>
                $(function() {
                    $('#top-episodes .episodes-items').slick({
                        infinite: true,
                        slidesToScroll: 1, // Scroll one at a time
                        slidesToShow: 5,   // Default for larger screens
                        dots: false,
                        arrows: false,     // Using custom external arrows
                        centerMode: true,
                        centerPadding: '40px',
                        autoplay: true,             // Enable autoplay
                        autoplaySpeed: 3000,        // Autoplay speed in milliseconds (e.g., 3 seconds)
                        pauseOnHover: true,         // Pause autoplay on hover
                        responsive: [
                        {
                            breakpoint: 1900,
                            settings: {
                                slidesToShow: 5,
                                slidesToScroll: 1,
                                centerMode: true,
                                centerPadding: '40px'
                            }
                        },
                        {
                            breakpoint: 1500,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1,
                                centerMode: true,
                                centerPadding: '30px'
                            }
                        },
                        {
                            breakpoint: 1100,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1,
                                centerMode: true,
                                centerPadding: '20px'
                            }
                        },
                        {
                            breakpoint: 1000,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                centerMode: true,
                                centerPadding: '80px'
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                centerMode: true,
                                centerPadding: '40px'
                            }
                        },
                        {
                            breakpoint: 600,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                centerMode: false,
                                centerPadding: '0px'
                            }
                        }]
                    });

                    $('#top-episodes .left span').on('click', function() {
                        $('#top-episodes .episodes-items').slick('slickPrev');
                    });

                    $('#top-episodes .right span').on('click', function() {
                        $('#top-episodes .episodes-items').slick('slickNext');
                    });
                })
            </script>
        </section>

        <section class="flex xl:flex-nowrap flex-wrap w-full py-10 ">
            {{-- Main Contain --}}
            <div class="space-y-8 xl:w-9/12 w-full px-6">
                {{-- Latest Movies --}}
                <div>
                    <div class="ui text menu m-0 sm:flex flex-wrap justify-between items-center pb-4 text-yellow-400 sm:space-y-0 space-y-1">
                        <div class="flex items-center space-x-1">
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item sm:text-xl text-sm font-medium">{{ __('Latest Movies') }}</a>
                        </div>
                        @if(count($latestmovies) >= 1)
                        <div class="right menu flex space-x-2 items-center">
                            <span class="mr-1 cursor-pointer bg-gray-700 text-sm text-white px-2 py-1 rounded hover:bg-gray-800 hover:text-yellow-500 transition duration-200 ease-out cursor-pointer"><a href="/movies">{{ __('View All') }}</a></span>
                        </div>
                        @endif
                    </div>
                    @if(count($latestmovies) >= 1)
                        <div class="w-full grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
                            @foreach($latestmovies as $latest)
                                <div class="relative px-2 mb-2">
                                    <div class="slide ">
                                        <div class="card-wrapper">
                                            <a href="/movie/{{ $latest->slug }}" title="{{ $latest->title }}" >
                                                <div class="card inline-top loaded portrait-card rounded-lg">
                                                    <div class="card-content-wrap" style="position: relative;">
                                                        <div class="card-image-content">
                                                            <div class="image-card base-card-image">
                                                                @php
                                                                    $latestMoviePosterPath = $latest->poster;
                                                                    $latestMovieWebpPath = Str::replaceLast(pathinfo($latestMoviePosterPath, PATHINFO_EXTENSION), 'webp', $latestMoviePosterPath);
                                                                @endphp
                                                                <picture>
                                                                    @if (Storage::disk('public')->exists($latestMovieWebpPath))
                                                                        <source srcset="{{ Storage::url($latestMovieWebpPath) }}" type="image/webp">
                                                                    @endif
                                                                    @if (Storage::disk('public')->exists($latestMoviePosterPath))
                                                                        <source srcset="{{ Storage::url($latestMoviePosterPath) }}" type="image/{{ pathinfo($latestMoviePosterPath, PATHINFO_EXTENSION) }}">
                                                                        <img {{ $loop->first ? 'fetchpriority=high' : 'loading=lazy' }} alt="{{ $latest->title }}" title="{{ $latest->title }}" class="original-image" src="{{ Storage::url($latestMoviePosterPath) }}">
                                                                    @else
                                                                        <img src="{{ asset('assets/frontend/images/default_poster.jpg') }}" alt="{{ $latest->title }}" class="image_slider_img original-image" loading="lazy">
                                                                    @endif
                                                                </picture>
                                                            </div>
                                                            <div>
                                                                <div class="card-overlay show-icon">
                                                                    <div class="absolute left-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mt-1 uppercase text-xs flex items-center space-x-1 rounded"><span class="iconify" data-icon="foundation:burst-new" data-inline="false"></span><span>{{ __('Latest') }}</span></div>
                                                                    <span class="absolute left-0 bottom-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mb-1 uppercase text-xs">{{ __('Movies') }}</span>
                                                                    <div class="absolute right-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 mr-1 mt-1 uppercase text-xs flex items-center">
                                                                        <span class="iconify mr-1" data-icon="akar-icons:star" data-inline="false"></span>{{ $latest->rating }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-details-bottom text-white">
                                                            <h3 class="card-header">{{ $latest->title }}</h3>
                                                            <div class="card-subheader">
                                                                @foreach ($latest->genres->take(3) as $singleGenre)
                                                                    <span class="genre-pill">{{ $singleGenre->name }}</span>
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
                        </div>
                        @else
                        <div class="flex items-center text-gray-400 p-5 bg-gray-900 rounded"><span>{{ __('There is no latest movies!') }}</span></div>
                    @endif
                </div>

                @if($ads->activate == 1)
                    <!-- Ads Start -->
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
                <!-- Ads End -->
                @endif

                {{-- Latest Series --}}
                <div>
                    <div class="ui text menu m-0 sm:flex flex-wrap justify-between items-center pb-4 text-yellow-400 sm:space-y-0 space-y-1">
                        <div class="flex items-center space-x-1">
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item sm:text-xl text-sm font-medium">{{ __('Latest Series') }}</a>
                        </div>
                        @if(count($latestseries) >= 1)
                        <div class="right menu flex space-x-2 items-center">
                            <span class="mr-1 cursor-pointer bg-gray-700 text-sm text-white px-2 py-1 rounded hover:bg-gray-800 hover:text-yellow-500 transition duration-200 ease-out cursor-pointer"><a href="/series">{{ __('View All') }}</a></span>
                        </div>
                        @endif
                    </div>
                    @if(count($latestseries) >= 1)
                    <div class="w-full grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
                        @foreach($latestseries as $latest)
                            <div class="relative px-2 mb-2">
                                <div class="slide ">
                                    <div class="card-wrapper">
                                        <a href="/series/{{ $latest->slug }}" title="{{ $latest->title }}" >
                                            <div class="card inline-top loaded portrait-card rounded-lg">
                                                <div class="card-content-wrap" style="position: relative;">
                                                    <div class="card-image-content">
                                                        <div class="image-card base-card-image">
                                                            @php
                                                                $latestSeriesPosterPath = $latest->poster;
                                                                $latestSeriesWebpPath = Str::replaceLast(pathinfo($latestSeriesPosterPath, PATHINFO_EXTENSION), 'webp', $latestSeriesPosterPath);
                                                            @endphp
                                                            <picture>
                                                                @if (Storage::disk('public')->exists($latestSeriesWebpPath))
                                                                    <source srcset="{{ Storage::url($latestSeriesWebpPath) }}" type="image/webp">
                                                                @endif
                                                                @if (Storage::disk('public')->exists($latestSeriesPosterPath))
                                                                    <source srcset="{{ Storage::url($latestSeriesPosterPath) }}" type="image/{{ pathinfo($latestSeriesPosterPath, PATHINFO_EXTENSION) }}">
                                                                    <img {{ $loop->first ? 'fetchpriority=high' : 'loading=lazy' }} alt="{{ $latest->title }}" title="{{ $latest->title }}" class="original-image" src="{{ Storage::url($latestSeriesPosterPath) }}">
                                                                @else
                                                                    <img src="{{ asset('assets/frontend/images/default_poster.jpg') }}" alt="{{ $latest->title }}" class="image_slider_img original-image" loading="lazy">
                                                                @endif
                                                            </picture>
                                                        </div>
                                                        <div>
                                                            <div class="card-overlay show-icon">
                                                                <div class="absolute left-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mt-1 uppercase text-xs flex items-center space-x-1 rounded"><span class="iconify" data-icon="foundation:burst-new" data-inline="false"></span><span>{{ __('Latest') }}</span></div>
                                                                <span class="absolute left-0 bottom-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mb-1 uppercase text-xs">{{ __('Series') }}</span>
                                                                <div class="absolute right-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 mr-1 mt-1 uppercase text-xs flex items-center">
                                                                    <span class="iconify mr-1" data-icon="akar-icons:star" data-inline="false"></span>{{ $latest->rating }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-details-bottom text-white">
                                                        <h3 class="card-header">{{ $latest->title }}</h3>
                                                        <div class="card-subheader">
                                                            @foreach ($latest->genres->take(3) as $singleGenre)
                                                                <span class="genre-pill">{{ $singleGenre->name }}</span>
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
                    </div>
                    @else
                    <div class="flex items-center text-gray-400 p-5 bg-gray-900 rounded"><span>{{ __('There is no latest series!') }}</span></div>
                    @endif
                </div>

                @if($ads->activate == 1)
                    <!-- Ads Start -->
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
                <!-- Ads End -->
                @endif

                {{-- Collections --}}
                <div>
                    <div class="ui text menu m-0 sm:flex flex-wrap justify-between items-center pb-4 text-yellow-400 sm:space-y-0 space-y-1">
                        <div class="flex items-center space-x-1">
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item sm:text-xl text-sm font-medium">{{ __('Collections') }}</a>
                        </div>
                        @if(isset($collections) && count($collections) > 0)
                        <div class="right menu flex space-x-2 items-center">
                            <span class="mr-1 cursor-pointer bg-gray-700 text-sm text-white px-2 py-1 rounded hover:bg-gray-800 hover:text-yellow-500 transition duration-200 ease-out cursor-pointer"><a href="/collections">{{ __('View All') }}</a></span>
                        </div>
                        @endif
                    </div>
                    @if(isset($collections) && count($collections) > 0)
                    <div class="w-full grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
                        @foreach($collections as $collection)
                            <div class="relative px-2 mb-2">
                                <div class="slide">
                                    <div class="card-wrapper">
                                        <a href="{{ url('/collection') }}/{{ $collection->id }}" title="{{ $collection->name }}">
                                            <div class="card inline-top loaded portrait-card rounded-lg" >
                                                <div class="card-content-wrap ">
                                                    <div class="card-image-content bg-gray-900 rounded-t-lg" >
                                                        <div class="base-card-image bg-gray-900" >
                                                            <div class="flex flex-wrap w-full h-full justify-start">
                                                                @php $check = 0; @endphp
                                                                @foreach ($collection->items as $singleItems)
                                                                    @if($check == 4)
                                                                        @break
                                                                    @endif
                                                                    @if($singleItems->type == 'movies')
                                                                        @php
                                                                            $posterPath = '/assets/movies/poster/' . $singleItems->poster;
                                                                            $filename = pathinfo($posterPath, PATHINFO_FILENAME);
                                                                            $dirname = pathinfo($posterPath, PATHINFO_DIRNAME);
                                                                            $webpPath = $dirname . '/' . $filename . '.webp';
                                                                        @endphp
                                                                        <picture>
                                                                            <source srcset="{{ asset($webpPath) }}" type="image/webp">
                                                                            <source srcset="{{ asset($posterPath) }}" type="{{ Str::endsWith($posterPath, '.png') ? 'image/png' : (Str::endsWith($posterPath, ['.jpg', '.jpeg']) ? 'image/jpeg' : (Str::endsWith($posterPath, '.gif') ? 'image/gif' : '')) }}">
                                                                            <img loading="lazy" alt="{{ $singleItems->title }}" title="{{ $singleItems->title }}" class="block w-2/4" src="{{ asset($posterPath) }}">
                                                                        </picture>
                                                                    @else
                                                                        @php
                                                                            $posterPath = '/assets/series/poster/' . $singleItems->poster;
                                                                            $filename = pathinfo($posterPath, PATHINFO_FILENAME);
                                                                            $dirname = pathinfo($posterPath, PATHINFO_DIRNAME);
                                                                            $webpPath = $dirname . '/' . $filename . '.webp';
                                                                        @endphp
                                                                        <picture>
                                                                            <source srcset="{{ asset($webpPath) }}" type="image/webp">
                                                                            <source srcset="{{ asset($posterPath) }}" type="{{ Str::endsWith($posterPath, '.png') ? 'image/png' : (Str::endsWith($posterPath, ['.jpg', '.jpeg']) ? 'image/jpeg' : (Str::endsWith($posterPath, '.gif') ? 'image/gif' : '')) }}">
                                                                            <img loading="lazy" alt="{{ $singleItems->title }}" title="{{ $singleItems->title }}" class="block w-2/4" src="{{ asset($posterPath) }}">
                                                                        </picture>
                                                                    @endif
                                                                    @php $check++; @endphp
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="collections-overlay show-icon"></div>
                                                        </div>
                                                    </div>
                                                    <div class="card-details text-white w-40" style="overflow: hidden;text-overflow: ellipsis;">
                                                        <h3 class="text-overflow card-header">{{ $collection->name }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @else
                        <div class="flex items-center text-gray-400 p-5 bg-gray-900 rounded"><span>{{ __("We couldn't find any collections!") }}</span></div>
                    @endif
                </div>

                {{-- Persons --}}
                <div>
                    <div class="ui text menu m-0 sm:flex flex-wrap justify-between items-center pb-4 text-yellow-400 sm:space-y-0 space-y-1">
                        <div class="flex items-center space-x-1">
                            <span class="iconify" data-icon="ic:baseline-groups" data-inline="false"></span> <a class="item sm:text-xl text-sm font-medium">{{ __('Popular Actors') }}</a>
                        </div>
                        @if(isset($persons) && count($persons) > 0)
                        <div class="right menu flex space-x-2 items-center">
                            <span class="mr-1 cursor-pointer bg-gray-700 text-sm text-white px-2 py-1 rounded hover:bg-gray-800 hover:text-yellow-500 transition duration-200 ease-out cursor-pointer"><a href="/persons">{{ __('View All') }}</a></span>
                        </div>
                        @endif
                    </div>
                    @if(isset($persons) && count($persons) > 0)
                    <div class="w-full grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-4">
                        @foreach($persons as $person)
                            <div class="relative text-center actor-card">
                                <a href="{{ url('/person/') }}/{{ $person->id }}" title="{{ $person->name }}" class="block group bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                                    <div class="relative inline-block mb-2 actor-image-wrapper pt-3">
                                        <img loading="lazy" alt="{{ $person->name }}" title="{{ $person->name }}" class="actor-profile-image" src="/assets/persons/{{ $person->profile_path }}">
                                    </div>
                                    <h3 class="text-sm font-semibold text-yellow-400 group-hover:text-yellow-300 transition-colors duration-200 truncate px-2" title="{{ $person->name }}">{{ $person->name }}</h3>
                                    <p class="text-xs text-gray-400 truncate px-2 pb-3">{{ $person->known_for_department }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @else
                        <div class="flex items-center text-gray-400 p-5 bg-gray-900 rounded"><span>{{ __("We couldn't find any persons!") }}</span></div>
                    @endif
                </div>

                @if($ads->activate == 1)
                    <!-- Ads Start -->
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
                <!-- Ads End -->
                @endif

            </div>
            {{-- Sidebar --}}
            <div class="xl:w-3/12 w-full">
                <div class="w-full sm:py-0 py-6 space-y-8 px-10">
                    @include('frontend.layouts.front-sidebar')
                </div>
            </div>
        </section>
    </div>
</div>

<style>
    /* Popular Actors Card Styling */
    .actor-card .actor-image-wrapper {
        width: 100px; /* Adjust size as needed */
        height: 100px; /* Adjust size as needed */
        margin-left: auto;
        margin-right: auto;
    }
    .actor-card .actor-profile-image {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid transparent; /* Initial transparent border */
        transition: transform 0.3s ease, border-color 0.3s ease;
    }
    .actor-card a.group:hover .actor-profile-image {
        transform: scale(1.05);
        border-color: #facc15; /* Yellow accent color from your palette */
    }

    /* Ensure image content within rounded cards also respects rounding for top corners if needed */
    .portrait-card .card-image-content {
        border-top-left-radius: 0.5rem; /* Corresponds to rounded-lg */
        border-top-right-radius: 0.5rem; /* Corresponds to rounded-lg */
        overflow: hidden; /* Crucial for the image to be clipped by the parent's border-radius */
    }
</style>

@endsection
