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
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl font-medium">{{ __("Collections") }}</a>
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

                    @if(count($collections_lists) >= 1)
                    <div class="w-full grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
                        @foreach($collections_lists as $collection)
                            <div class="relative px-2 mb-2">
                                <div class="slide">
                                    <div class="card-wrapper">
                                        <a href="{{ url('/collection') }}/{{ $collection->id }}" title="{{ $collection->name }}">
                                            <div class="card inline-top loaded portrait-card" >
                                                <div class="card-content-wrap ">
                                                    <div class="card-image-content bg-gray-900" >
                                                        <div class="base-card-image bg-gray-900" >
                                                            <div class="flex flex-wrap w-full h-full justify-start" >
                                                                @php $check = 0; @endphp
                                                                @foreach ($collection->items as $singleItems)
                                                                    @if($check == 4)
                                                                        @break
                                                                    @endif
                                                                    @if($singleItems->type == 'movies')
                                                                        <img alt="{{ $singleItems->title }}" title="{{ $singleItems->title }}" class="block w-2/4" src="/assets/movies/poster/{{ $singleItems->poster }}">
                                                                    @else
                                                                        <img alt="{{ $singleItems->title }}" title="{{ $singleItems->title }}" class="block w-2/4" src="/assets/series/poster/{{ $singleItems->poster }}">
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
                        <div class="flex items-center text-yellow-400"><span>{{ __("There is no collections!") }}</span></div>
                    @endif
                    {{-- Pagination --}}
                    <div class="flex mt-5">
                        {{ $collections_lists->links('frontend.layouts.pagination')  }}
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
