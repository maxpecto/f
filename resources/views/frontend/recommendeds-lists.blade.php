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
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl font-medium">{{ __("Recommendeds") }}</a>
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

                    @if(count($recommendeds_lists) >= 1)
                    <div class="w-full grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
                        @foreach($recommendeds_lists as $latest)
                            @if($latest->type == 'movies')
                                <div class="relative px-2 mb-2">
                                    <div class="slide ">
                                        <div class="card-wrapper">
                                            <a href="/movie/{{ $latest->slug }}" title="{{ $latest->title }}" >
                                                <div class="card inline-top loaded portrait-card">
                                                    <div class="card-content-wrap">
                                                        <div class="card-image-content">
                                                            <div class="image-card base-card-image">
                                                                <img alt="{{ $latest->title }}" title="{{ $latest->title }}" class="original-image" src="/assets/movies/poster/{{ $latest->poster }}">
                                                            </div>
                                                            <div>
                                                                <div class="card-overlay show-icon">
                                                                    <div class="absolute left-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mt-1 uppercase text-xs flex items-center space-x-1 rounded"><span class="iconify" data-icon="ic:twotone-recommend" data-inline="false"></span><span>{{ __("Recommended") }}</span></div>
                                                                    <span class="absolute left-0 bottom-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mb-1 uppercase text-xs">{{ __("Movies") }}</span>
                                                                    <div class="absolute right-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 mr-1 mt-1 uppercase text-xs flex items-center rounded">
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
                            @else
                            <div class="relative px-2 mb-2">
                                <div class="slide ">
                                    <div class="card-wrapper">
                                        <a href="/series/{{ $latest->slug }}" title="{{ $latest->title }}" >
                                            <div class="card inline-top loaded portrait-card">
                                                <div class="card-content-wrap">
                                                    <div class="card-image-content">
                                                        <div class="image-card base-card-image">
                                                            <img alt="{{ $latest->title }}" title="{{ $latest->title }}" class="original-image" src="/assets/series/poster/{{ $latest->poster }}">
                                                        </div>
                                                        <div>
                                                            <div class="card-overlay show-icon">
                                                                <div class="absolute left-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mt-1 uppercase text-xs flex items-center space-x-1 rounded"><span class="iconify" data-icon="ic:twotone-recommend" data-inline="false"></span><span>{{ __("Recommended") }}</span></div>
                                                                <span class="absolute left-0 bottom-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mb-1 uppercase text-xs">{{ __("Series") }}</span>
                                                                <div class="absolute right-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 mr-1 mt-1 uppercase text-xs flex items-center rounded">
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
                            @endif
                        @endforeach
                    </div>
                    @else
                        <div class="flex items-center text-yellow-400"><span>{{ __("There is no recommendeds items!") }}</span></div>
                    @endif
                    {{-- Pagination --}}
                    <div class="flex mt-5">
                        {{ $recommendeds_lists->links('frontend.layouts.pagination')  }}
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


@endsection
