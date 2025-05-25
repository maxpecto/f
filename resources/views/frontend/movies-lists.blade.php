@extends('layouts.app')

@section('content')

@include('frontend.layouts.alpa')

<div class="container mx-auto bg-black flex">
    <div class="w-full">
        <section class="flex xl:flex-nowrap flex-wrap w-full">
            {{-- Main Contain --}}
            <div class="space-y-8 xl:w-9/12 w-full p-6">
                {{-- Latest Movies --}}
                <div>
                    <div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400">
                        <div class="flex items-center space-x-1">
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl font-medium">{{ __("Latest Movies") }}</a>
                        </div>
                        <div>
                            <button onclick="ShowFilter()" class="filter flex items-center space-x-1 focus:outline-none text-white">
                                <span class="iconify" data-icon="foundation:filter" data-inline="false"></span><span>{{ __("Filter") }}</span>
                            </button>
                        </div>
                    </div>
                    {{-- Filter --}}
                    <div id="myFilter" class="transition delay-150 duration-300 ease-in-out" style="display:none">
                        {!! Form::open(['url' => 'movies','method'=>'get','class'=>'w-full grid xl:grid-cols-6 lg:grid-cols-3 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 mb-4 text-xs']) !!}
                            <div class="flex flex-col w-max m-4">
                                <div class="flex text-white text-xs justify-start ">
                                    <span class="px-2 py-1">{{ __("Genres") }}</span>
                                </div>
                                <div class="block">
                                    <select class="px-2 py-1 rounded bg-gray-900 text-white w-36" name="genres">
                                        <option value="" selected>{{ __("Select Genres") }}..</option>
                                        @foreach($genres as $genre)
                                        <option value="{{ $genre->name }}">{{ $genre->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-col w-max m-4">
                                <div class="flex text-white text-xs justify-start ">
                                    <span class="px-2 py-1">{{ __("TMDB") }}</span>
                                </div>
                                <div class="block">
                                    <select class="px-2 py-1 rounded bg-gray-900 text-white w-36" name="rating">
                                        <option value="" selected>{{ __("Select Rating") }}..</option>
                                        <option value="4">4 {{ __("and over") }}</option>
                                        <option value="5">5 {{ __("and over") }}</option>
                                        <option value="6">6 {{ __("and over") }}</option>
                                        <option value="7">7 {{ __("and over") }}</option>
                                        <option value="8">8 {{ __("and over") }}</option>
                                        <option value="9">9 {{ __("and over") }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-col w-max m-4">
                                <div class="flex text-white text-xs justify-start ">
                                    <span class="px-2 py-1">{{ __("Quality") }}</span>
                                </div>
                                <div class="block">
                                    <div class="block">
                                        <select class="px-2 py-1 rounded bg-gray-900 text-white w-36" name="quality">
                                            <option value="" selected>{{ __("Select Quality") }}..</option>
                                            @foreach($homequality as $quality)
                                            <option value="{{ $quality->name }}">{{ $quality->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col w-max m-4">
                                <div class="flex text-white text-xs justify-start ">
                                    <span class="px-2 py-1">{{ __("Year") }}</span>
                                </div>
                                <div class="block">
                                    <select class="px-2 py-1 rounded bg-gray-900 text-white w-36" name="years">
                                        <option value="" selected>{{ __("Select Year") }}..</option>
                                        @foreach($years as $year)
                                        <option value="{{ $year->name }}">{{ $year->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-col w-max m-4">
                                <div class="flex text-white text-xs justify-start ">
                                    <span class="px-2 py-1">{{ __("Countries") }}</span>
                                </div>
                                <div class="block">
                                    <select class="px-2 py-1 rounded bg-gray-900 text-white w-36" name="countries">
                                        <option value="" selected>{{ __("Select Country") }}..</option>
                                        @foreach($homecountries as $country)
                                        <option value="{{ $country->code }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-col w-max m-4">
                                <div class="flex text-white text-xs justify-start ">
                                    <span class="px-2 py-1">{{ __("Sorting") }}</span>
                                </div>
                                <div class="block">
                                    <select class="px-2 py-1 rounded bg-gray-900 text-white w-36" name="sorting">
                                        <option value="" selected>{{ __("Select Sorting") }}..</option>
                                        <option value="ASC">{{ __("ASC (Added)") }}</option>
                                        <option value="DESC">{{ __("DESC (Added)") }}</option>
                                        <option value="ASC-1">{{ __("ASC (Alpa)") }}</option>
                                        <option value="DESC-1">{{ __("DESC (Alpa)") }}</option>
                                        <option value="views">{{ __("Views") }}</option>
                                        <option value="release">{{ __("Release") }}</option>
                                        <option value="rating">{{ __("Rating") }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex w-max m-4 items-center">
                                <div class="flex text-base justify-start">
                                    <button type="submit" class="bg-gray-900 text-yellow-400 hover:text-white hover:bg-gray-800 px-4 py-2 rounded">{{ __("Apply") }}</button>
                                </div>
                            </div>
                        {!! Form::close()  !!}
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

                    {{-- Lists Movies --}}
                    <div class="w-full grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
                        @if(count($movies) >= 1)
                            @foreach($movies as $latest)
                                {{-- DEBUG SON --}}
                                <div class="relative px-2 mb-2">
                                    <div class="slide ">
                                        <div class="card-wrapper">
                                            <a href="/movie/{{ $latest->slug }}" title="{{ $latest->title }}" >
                                                <div class="card inline-top loaded portrait-card">
                                                    <div class="card-content-wrap">
                                                        <div class="card-image-content">
                                                            <div class="image-card base-card-image relative" style="padding-bottom: 150%;">
                                                                @php
                                                                    $originalPath = $latest->poster; // Veritabanındaki yolun zaten 'assets/movies/poster/film.jpg' gibi olduğunu varsayıyoruz
                                                                    $webpPath = Str::replaceLast(pathinfo($originalPath, PATHINFO_EXTENSION), 'webp', $originalPath);
                                                                @endphp
                                                                <picture>
                                                                    @if (Storage::disk('public')->exists($webpPath))
                                                                        <source srcset="{{ Storage::url($webpPath) }}" type="image/webp">
                                                                    @endif
                                                                    @if (Storage::disk('public')->exists($originalPath))
                                                                        <source srcset="{{ Storage::url($originalPath) }}" type="image/{{ pathinfo($originalPath, PATHINFO_EXTENSION) }}">
                                                                        <img alt="{{ $latest->title }}" title="{{ $latest->title }}" class="original-image absolute inset-0 w-full h-full object-cover"
                                                                             src="{{ Storage::url($originalPath) }}"
                                                                             @if($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                                                                    @else
                                                                        <img alt="{{ $latest->title }}" title="{{ $latest->title }}" class="original-image absolute inset-0 w-full h-full object-cover"
                                                                             src="{{ asset('assets/frontend/images/default_poster.jpg') }}"
                                                                             @if($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                                                                    @endif
                                                                </picture>
                                                            </div>
                                                            <div>
                                                                <div class="card-overlay show-icon">
                                                                    <div class="absolute right-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 mr-1 mt-1 uppercase text-xs flex items-center">
                                                                        <span class="iconify mr-1" data-icon="akar-icons:star" data-inline="false"></span>{{ $latest->rating }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-details text-white w-40" style="overflow: hidden;text-overflow: ellipsis;">
                                                            <h3 class="text-overflow card-header">{{ $latest->title }}</h3>
                                                            <div class="text-overflow card-subheader">
                                                                @foreach ($latest->genres as $singleGenre)
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
                            <div class="flex items-center text-yellow-400"><span>{{ __("There is no latest movies!") }}</span></div>
                        @endif
                    </div>
                    {{-- Pagination --}}
                    <div class="flex mt-5">
                        {{ $movies->links('frontend.layouts.pagination')  }}
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
</div>
<script>
    function ShowFilter() {
        $('.filter').toggleClass('text-yellow-400');
        var x = document.getElementById("myFilter");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>

@endsection
