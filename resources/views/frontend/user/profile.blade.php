@extends('layouts.app')

@section('content')
<main class="container mx-auto bg-gray-900">
    <div class="flex">
        <div class="flex w-full sm:px-6">
            <section class="w-full flex flex-wrap top-1/5">
                <div class="w-full p-10 mb-5 space-y-10">
                    <header class="w-full block lg:flex text-white lg:space-x-10">
                        <div class="w-36 grid justify-items-center relative mx-auto lg:mb-0 mb-5 space-y-2">
                            <img class="w-36 rounded-full" src="{{ asset('/assets/users/') }}{{ $users->profile_img}}">
                            @if($users->verify_Badge == 1)
                            {{-- Verified Badge --}}
                            <div class="absolute right-0 top-0" title="Verified">
                                <svg class="_2Eg7j" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32"><path d="M29.8954 13.2925L29.8954 13.2925L29.9017 13.2987C31.3613 14.7317 31.3642 17.2192 29.9218 18.6838L29.9079 18.6979L29.9056 18.7004L29.9028 18.7032C29.8975 18.7083 29.889 18.7165 29.8794 18.7261L28.7426 19.8622C28.1085 20.4958 27.7449 21.3575 27.7449 22.2672V23.9136C27.7449 26.0173 26.0313 27.7315 23.9254 27.7315H22.2762C21.3695 27.7315 20.5071 28.092 19.8721 28.7265L18.7193 29.8786L18.7187 29.8792C17.2246 31.3748 14.8134 31.3677 13.3091 29.8933L12.1437 28.727L12.1432 28.7265C11.5082 28.092 10.6459 27.7315 9.73911 27.7315H8.08991C5.98405 27.7315 4.2704 26.0173 4.2704 23.9136V22.2672C4.2704 21.3546 3.90521 20.5005 3.28506 19.8587L3.27653 19.8498L3.26779 19.8412L2.11962 18.7098C2.11892 18.7091 2.11822 18.7084 2.11753 18.7077C0.635192 17.2254 0.623838 14.8017 2.10553 13.3085C2.10589 13.3081 2.10625 13.3078 2.10661 13.3074L3.27276 12.1405C3.90906 11.5046 4.2704 10.642 4.2704 9.72103V8.08896C4.2704 5.9862 5.98318 4.27435 8.08991 4.27435H9.73911C10.6493 4.27435 11.5106 3.90819 12.1432 3.27607L13.296 2.12402L13.2987 2.1213C14.7794 0.630261 17.2041 0.625524 18.7051 2.11139C18.7055 2.1118 18.7059 2.11221 18.7063 2.11262L19.8721 3.27607C20.5047 3.90819 21.3661 4.27435 22.2762 4.27435H23.9254C26.0321 4.27435 27.7449 5.9862 27.7449 8.08896V9.73863C27.7449 10.6459 28.109 11.5073 28.7426 12.1405L29.8954 13.2925ZM29.9125 18.6938L29.9124 18.6938L29.9125 18.6938Z" fill="#007fff" stroke="white" stroke-width="2"></path><path d="M14 20.9829L9.71716 16.7001L11.4 15.0172L14 17.6172L20.6 11.0172L22.2828 12.7001L14 20.9829Z" fill="white"></path></svg>
                            </div>
                            {{-- End Verified Badge --}}
                            @endif
                            @if(isset($users->location))
                                <div class="flex items-center text-sm text-white rounded space-x-1">
                                    <span class="iconify" data-icon="feather:map-pin" data-inline="false"></span> <span>{{ $users->location }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="align-center w-full lg:w-11/12 space-y-4">
                            <div class="block lg:flex items-center lg:space-y-0 space-y-4">
                                <h2 class="text-3xl lg:pr-2 pr-0 text-center lg:text-left">{{ $users->fname }} {{ $users->lname }}</h2>
                                @if(Auth::id() === $users->id)
                                <div class="w-min">
                                    <a class="flex items-center p-2 bg-yellow-500 rounded text-white shadow-2xl" href="/edit-profile"><span class="iconify mr-2" data-icon="akar-icons:edit" data-inline="false"></span> {{ __("Complete Your Profile") }}</a>
                                </div>
                                @endif
                            </div>
                            <div class="flex flex wrap align-center text-sm">
                                <p>
                                    {{ $users->description }}
                                </p>
                            </div>
                            <div class="sm:flex flex-wrap align-center text-sm sm:space-x-4 space-x-0 sm:space-y-0 space-y-2">
                                @if(isset($users->website))
                                <div class="flex items-center text-white rounded space-x-1 hover:text-yellow-500">
                                    <span class="iconify" data-icon="clarity:world-solid" data-inline="false"></span> <span> <a target="_blank" href="{{ $users->website }}">{{ $users->website }}</a></span>
                                </div>
                                @endif
                                @if(isset($users->instagram))
                                <div class="flex items-center text-white rounded space-x-1 hover:text-yellow-500">
                                    <span class="iconify" data-icon="brandico:instagram-filled" data-inline="false"></span> <span> <a target="_blank" href="{{ $users->instagram }}">{{ __("Instagram") }}</a></span>
                                </div>
                                @endif
                                @if(isset($users->twitter))
                                <div class="flex items-center text-white rounded space-x-1 hover:text-yellow-500">
                                    <span class="iconify" data-icon="fa-brands:twitter-square" data-inline="false"></span> <span> <a target="_blank" href="{{ $users->twitter }}">{{ __("Twitter") }}</a></span>
                                </div>
                                @endif
                            </div>
                            <div class="flex flex wrap align-center text-sm space-x-4">
                                <div class="bg-yellow-500 text-white rounded py-2 px-4">
                                    <div class="font-bold text-center">{{ $users->views }}</div>
                                    <div class="">{{ __("Profile Views") }}</div>
                                </div>
                            </div>
                        </div>
                    </header>

                    <div x-data="{
                            openTab: 1,
                            activeClasses: 'border-l border-t border-r border-yellow-500 rounded-t text-white bg-yellow-500',
                            inactiveClasses: 'border-l border-t border-r border-yellow-500 rounded-t text-white' }">
                        <ul class="flex border-b border-yellow-500 justify-between">
                            <div class="sm:flex flex-wrap">
                                <li @click="openTab = 1" :class="{ '-mb-px': openTab === 1 }" class="-mb-px mr-1">
                                    <a :class="openTab === 1 ? activeClasses : inactiveClasses" class="inline-block py-2 px-4 cursor-pointer tracking-widest text-base flex items-center">
                                        <span class="iconify mr-2" data-icon="ant-design:like-filled" data-inline="false"></span> {{ __("Liked Movies/Series") }}
                                    </a>
                                </li>
                                <li @click="openTab = 2" :class="{ '-mb-px': openTab === 2 }" class="-mb-px mr-1">
                                    <a :class="openTab === 2 ? activeClasses : inactiveClasses" class="inline-block py-2 px-4 cursor-pointer tracking-widest text-base flex items-center">
                                        <span class="iconify mr-2" data-icon="ant-design:like-filled" data-inline="false"></span> {{ __("Liked Episodes") }}
                                    </a>
                                </li>
                                <li @click="openTab = 3" :class="{ '-mb-px': openTab === 3 }" class="mr-1">
                                    <a :class="openTab === 3 ? activeClasses : inactiveClasses" class="inline-block py-2 px-4 cursor-pointer tracking-widest text-base flex items-center">
                                        <span class="iconify mr-2" data-icon="bi:bookmark-star-fill" data-inline="false"  ></span> {{ __("Watchlists Items") }}
                                    </a>
                                </li>
                            </div>
                        </ul>
                        <div class="w-full pt-4 ">
                            <div x-show="openTab === 1">
                                @if($users->hidden_items == 0 or $is_current_user == true)
                                    @if(count($likedItems) >= 1)
                                        <div class="w-full grid xl:grid-cols-6 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
                                            @foreach($likedItems as $latest)
                                                @if($latest->items->type == 'movies')
                                                    <div class="relative px-2 mb-2">
                                                        <div class="slide ">
                                                            <div class="card-wrapper">
                                                                <a href="/movie/{{ $latest->items->slug }}" title="{{ $latest->items->title }}" >
                                                                    <div class="card inline-top loaded portrait-card">
                                                                        <div class="card-content-wrap">
                                                                            <div class="card-image-content">
                                                                                <div class="image-card base-card-image">
                                                                                    <img alt="{{ $latest->items->title }}" title="{{ $latest->items->title }}" class="original-image" src="/assets/movies/poster/{{ $latest->items->poster }}">
                                                                                </div>
                                                                                <div>
                                                                                    <div class="card-overlay show-icon">
                                                                                        <span class="absolute left-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mt-1 uppercase text-xs">{{ __("Movies") }}</span>
                                                                                        <div class="absolute right-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 mr-1 mt-1 uppercase text-xs flex items-center rounded">
                                                                                            <span class="iconify mr-1" data-icon="akar-icons:star" data-inline="false"></span>{{ $latest->items->rating }}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="card-details text-white w-40" style="overflow: hidden;text-overflow: ellipsis;">
                                                                                <h3 class="text-overflow card-header">{{ $latest->items->title }}</h3>
                                                                                <div class="text-overflow card-subheader">
                                                                                    @foreach ($latest->items->genres as $singleGenre)
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
                                                            <a href="/series/{{ $latest->items->slug }}" title="{{ $latest->items->title }}" >
                                                                <div class="card inline-top loaded portrait-card">
                                                                    <div class="card-content-wrap">
                                                                        <div class="card-image-content">
                                                                            <div class="image-card base-card-image">
                                                                                <img alt="{{ $latest->items->title }}" title="{{ $latest->items->title }}" class="original-image" src="/assets/series/poster/{{ $latest->items->poster }}">
                                                                            </div>
                                                                            <div>
                                                                                <div class="card-overlay show-icon">
                                                                                    <span class="absolute left-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mt-1 uppercase text-xs">{{ __("Series") }}</span>
                                                                                    <div class="absolute right-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 mr-1 mt-1 uppercase text-xs flex items-center rounded">
                                                                                        <span class="iconify mr-1" data-icon="akar-icons:star" data-inline="false"></span>{{ $latest->items->rating }}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card-details text-white w-40" style="overflow: hidden;text-overflow: ellipsis;">
                                                                            <h3 class="text-overflow card-header">{{ $latest->items->title }}</h3>
                                                                            <div class="text-overflow card-subheader">
                                                                                @foreach ($latest->items->genres as $singleGenre)
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
                                        <div class="flex items-center text-yellow-400"><span>{{ __("There is no liked movies/series!") }}</span></div>
                                    @endif
                                @else
                                    <div class="flex items-center text-yellow-400"><span>{{ __("Hidden content! (User hide its from public view)") }}</span></div>
                                @endif
                            </div>
                            <div x-show="openTab === 2">
                                @if($users->hidden_items == 0 or $is_current_user == true)
                                    @if(count($likedEpisodes) >= 1)
                                        <div class="w-full grid xl:grid-cols-6 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
                                            @foreach($likedEpisodes as $latest)
                                                <div class="relative px-2 mb-2">
                                                    <div class="slide ">
                                                        <div class="card-wrapper">
                                                            <a href="{{ url($latest->episodes->series->id) }}/{{$latest->episodes->series->slug}}/season-{{ $latest->episodes->season_id }}/episode-{{ $latest->episodes->episode_id}}" title="{{ $latest->episodes->series->title }} (S{{ $latest->episodes->season_id }}E{{ $latest->episodes->episode_id }})" >
                                                                <div class="card inline-top loaded portrait-card">
                                                                    <div class="card-content-wrap">
                                                                        <div class="card-image-content">
                                                                            <div class="image-card base-card-image">
                                                                                <img alt="{{ $latest->episodes->series->title }} (S{{ $latest->episodes->season_id }}E{{ $latest->episodes->episode_id }})" title="{{ $latest->episodes->series->title }} (S{{ $latest->episodes->season_id }}E{{ $latest->episodes->episode_id }})" class="original-image" src="/assets/episodes/backdrop/{{ $latest->episodes->backdrop }}">
                                                                            </div>
                                                                            <div>
                                                                                <div class="card-overlay show-icon"></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card-details text-white w-44" style="overflow: hidden;text-overflow: ellipsis;">
                                                                            <h3 class="text-overflow card-header">{{ $latest->episodes->series->title }} (S{{ $latest->episodes->season_id }}E{{ $latest->episodes->episode_id }})</h3>
                                                                            <div class="text-overflow card-subheader">
                                                                                @foreach ($latest->episodes->series->genres as $singleGenre)
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
                                        </div>
                                    @else
                                        <div class="flex items-center text-yellow-400"><span>{{ __("There is no liked episodes!") }}</span></div>
                                    @endif
                                @else
                                    <div class="flex items-center text-yellow-400"><span>{{ __("Hidden content! (User hide its from public view)") }}</span></div>
                                @endif
                            </div>
                            <div x-show="openTab === 3">
                                @if($users->hidden_items == 0 or $is_current_user == true)
                                    @if(count($wathlistItems) >= 1)
                                        <div class="w-full grid xl:grid-cols-6 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1">
                                            @foreach($wathlistItems as $latest)
                                                @if($latest->items->type == 'movies')
                                                    <div class="relative px-2 mb-2">
                                                        <div class="slide ">
                                                            <div class="card-wrapper">
                                                                <a href="/movie/{{ $latest->items->slug }}" title="{{ $latest->items->title }}" >
                                                                    <div class="card inline-top loaded portrait-card">
                                                                        <div class="card-content-wrap">
                                                                            <div class="card-image-content">
                                                                                <div class="image-card base-card-image">
                                                                                    <img alt="{{ $latest->items->title }}" title="{{ $latest->items->title }}" class="original-image" src="/assets/movies/poster/{{ $latest->items->poster }}">
                                                                                </div>
                                                                                <div>
                                                                                    <div class="card-overlay show-icon">
                                                                                        <span class="absolute left-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mt-1 uppercase text-xs">{{ __("Movies") }}</span>
                                                                                        <div class="absolute right-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 mr-1 mt-1 uppercase text-xs flex items-center rounded">
                                                                                            <span class="iconify mr-1" data-icon="akar-icons:star" data-inline="false"></span>{{ $latest->items->rating }}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="card-details text-white w-40" style="overflow: hidden;text-overflow: ellipsis;">
                                                                                <h3 class="text-overflow card-header">{{ $latest->items->title }}</h3>
                                                                                <div class="text-overflow card-subheader">
                                                                                    @foreach ($latest->items->genres as $singleGenre)
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
                                                            <a href="/series/{{ $latest->items->slug }}" title="{{ $latest->items->title }}" >
                                                                <div class="card inline-top loaded portrait-card">
                                                                    <div class="card-content-wrap">
                                                                        <div class="card-image-content">
                                                                            <div class="image-card base-card-image">
                                                                                <img alt="{{ $latest->items->title }}" title="{{ $latest->items->title }}" class="original-image" src="/assets/series/poster/{{ $latest->items->poster }}">
                                                                            </div>
                                                                            <div>
                                                                                <div class="card-overlay show-icon">
                                                                                    <span class="absolute left-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 ml-1 mt-1 uppercase text-xs">{{ __("Series") }}</span>
                                                                                    <div class="absolute right-0 top-0 px-2 py-1 bg-gray-900 text-yellow-400 z-50 mr-1 mt-1 uppercase text-xs flex items-center rounded">
                                                                                        <span class="iconify mr-1" data-icon="akar-icons:star" data-inline="false"></span>{{ $latest->items->rating }}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card-details text-white w-40" style="overflow: hidden;text-overflow: ellipsis;">
                                                                            <h3 class="text-overflow card-header">{{ $latest->items->title }}</h3>
                                                                            <div class="text-overflow card-subheader">
                                                                                @foreach ($latest->items->genres as $singleGenre)
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
                                        <div class="flex items-center text-yellow-400"><span>{{ __("There is no watchlist items!") }}</span></div>
                                    @endif
                                @else
                                    <div class="flex items-center text-yellow-400"><span>{{ __("Hidden content! (User hide its from public view)") }}</span></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection
