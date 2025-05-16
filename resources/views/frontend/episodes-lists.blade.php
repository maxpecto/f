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
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl  font-medium">{{ __("Latest Episodes") }}</a>
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

                    <div class="w-full grid xl:grid-cols-5 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
                        @if(count($episodes) >= 1)
                            @foreach($episodes as $latest)
                                <div class="relative px-2 mb-2">
                                    <div class="slide ">
                                        <div class="card-wrapper">
                                            <a href="{{ url($latest->series->id) }}/{{$latest->series->slug}}/season-{{ $latest->season_id }}/episode-{{ $latest->episode_id}}" title="{{ $latest->series->title }} (S{{ $latest->season_id }}E{{ $latest->episode_id }})" >
                                                <div class="card inline-top loaded portrait-card">
                                                    <div class="card-content-wrap">
                                                        <div class="card-image-content">
                                                            <div class="image-card base-card-image">
                                                                <img alt="{{ $latest->series->title }} (S{{ $latest->season_id }}E{{ $latest->episode_id }})" title="{{ $latest->series->title }} (S{{ $latest->season_id }}E{{ $latest->episode_id }})" class="original-image" src="{{ asset('assets/series/backdrop/' . $latest->backdrop) }}">
                                                            </div>
                                                            <div>
                                                                <div class="card-overlay show-icon"></div>
                                                            </div>
                                                        </div>
                                                        <div class="card-details text-white w-44" style="overflow: hidden;text-overflow: ellipsis;">
                                                            <h3 class="text-overflow card-header">{{ $latest->series->title }} (S{{ $latest->season_id }}E{{ $latest->episode_id }})</h3>
                                                            <div class="text-overflow card-subheader">
                                                                @foreach ($latest->series->genres as $singleGenre)
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
                            <div class="flex items-center text-yellow-400"><span>{{ __("There is no latest episodes!") }}</span></div>
                        @endif
                    </div>
                    {{-- Pagination --}}
                    <div class="flex mt-5">
                        {{ $episodes->links('frontend.layouts.pagination')  }}
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
