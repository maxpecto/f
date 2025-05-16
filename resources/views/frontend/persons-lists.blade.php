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
                    <div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400 sm:flex-nowrap flex-wrap">
                        <div class="flex items-center space-x-1">
                            <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl  font-medium">{{ __("Persons") }}</a>
                        </div>
                        <div>
                            {!! Form::open(['url' => 'persons','method'=>'get','class'=>'flex items-center space-x-1']) !!}
                            <input name="search_person" class="px-4 py-2 bg-gray-800 rounded focus:outline-none w-full" placeholder="{{ __("Search Person") }}.." >
                            <button class="ml-4 flex items-center cursor-pointer p-3 bg-gray-800 rounded hover:bg-yellow-400 hover:text-gray-700 focus:outline-none">
                                <span class="iconify" data-icon="akar-icons:search" data-inline="false"></span>
                            </button>
                            {!! Form::close()  !!}
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
                        @if(count($persons_lists) > 0)
                            @foreach($persons_lists as $person)
                                <div class="relative px-2 mb-2 rounded-lg">
                                    <div class="slide">
                                        <div class="card-wrapper">
                                            <a href="{{ url('/person') }}/{{ $person->id }}" title="{{ $person->name }}">
                                                <div class="card inline-top loaded portrait-card" >
                                                    <div class="card-content-wrap ">
                                                        <div class="card-image-content">
                                                            <div class="image-card base-card-image">
                                                                <img alt="{{ $person->name }}" title="{{ $person->name }}" class="original-image" src="/assets/persons/{{ $person->profile_path }}">
                                                            </div>
                                                            <div>
                                                                <div class="persons-overlay show-icon">
                                                                    <div class="absolute left-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mt-1 uppercase text-xs flex items-center space-x-1 rounded">
                                                                        @if($person->gender == 2)
                                                                            <span class="iconify" data-icon="el:male" data-inline="false"></span><span>{{ __("Male") }}</span>
                                                                        @elseif($person->gender == 1)
                                                                            <span class="iconify" data-icon="el:female" data-inline="false"></span><span>{{ __("Female") }}</span>
                                                                        @else
                                                                            <span class="iconify" data-icon="fa-solid:transgender" data-inline="false"></span><span>{{ __("Unknown") }}</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="absolute right-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 mr-1 mt-1 uppercase text-xs flex items-center rounded space-x-1">
                                                                        <span class="iconify" data-icon="heroicons-solid:fire" data-inline="false"></span>
                                                                        <span>{{ $person->popularity }}</span>
                                                                    </div>
                                                                    @if(!empty($person->birthday))
                                                                    <div class="absolute right-1 bottom-1 px-2 py-1 bg-gray-900 text-yellow-400 z-50 mr-1 mt-1 uppercase text-xs flex items-center rounded space-x-1">
                                                                        <span class="iconify" data-icon="wpf:birthday" data-inline="false"></span>
                                                                        <span>@php echo date("d, M Y", strtotime($person->birthday));  @endphp</span>
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex w-full text-center">
                                                            <span class="w-full text-sm text-yellow-500 px-2 py-1 bg-gray-900 rounded-b">{{ $person->known_for_department }}</span>
                                                        </div>
                                                        <div class="card-details text-white flex flex-col mb-2" style="overflow: hidden; text-overflow: ellipsis;">
                                                            <h3 class="text-overflow card-header text-xs">{{ $person->name }}</h3>
                                                            <span class="text-xs text-white">{{ $person->place_of_birth }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center text-yellow-400"><span>{{ __("There is no persons!") }}</span></div>
                        @endif
                    </div>
                    {{-- Pagination --}}
                    <div class="flex mt-5">
                        {{ $persons_lists->links('frontend.layouts.pagination')  }}
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

