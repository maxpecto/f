@extends('layouts.app')

@section('title', $platform->name . ' - Platform İçerikleri') {{-- Dinamik başlık --}}

@section('content')

@include('frontend.layouts.alpa')

<div class="container mx-auto bg-gray-900 flex">
    <div class="w-full">
        <section class="flex xl:flex-nowrap flex-wrap w-full">
            {{-- Main Contain --}}
            <div class="space-y-8 xl:w-9/12 w-full p-6">
                <div>
                    <div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400">
                        <div class="flex items-center space-x-2">
                            @if($platform->logo_image_path)
                                <img src="{{ Storage::url($platform->logo_image_path) }}" alt="{{ $platform->name }}" class="h-10 sm:h-12 object-contain mr-2" style="max-width: 120px;">
                            @endif
                            <h1 class="item text-xl sm:text-2xl font-medium">{{ $platform->name }} {{ __('İçerikleri') }}</h1>
                        </div>
                    </div>

                    {{-- Reklamlar ve diğer kısımlar listings.blade.php'den buraya kopyalanabilir --}}
                    {{-- Şimdilik sadece içerik listeleme --}}

                    @if($items->isNotEmpty())
                    <div class="w-full grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-4">
                        @foreach($items as $item)
                            <div class="relative">
                                <div class="slide w-full"> {{-- slide class'ı slick carousel için olabilir, burada gerekmeyebilir --}}
                                    <div class="card-wrapper w-full">
                                        <a href="{{ route($item->type == 'movies' ? 'single-movie' : 'single-series', $item->slug) }}" title="{{ $item->title }}" >
                                            <div class="card inline-top loaded portrait-card rounded-lg bg-gray-800 shadow-lg overflow-hidden">
                                                <div class="card-content-wrap">
                                                    <div class="card-image-content relative">
                                                        <div class="image-card base-card-image aspect-w-2 aspect-h-3"> {{-- Aspect ratio için --}}
                                                            <img alt="{{ $item->title }}" title="{{ $item->title }}" class="original-image object-cover w-full h-full" 
                                                                 src="/assets/{{ $item->type == 'movies' ? 'movies' : 'series' }}/poster/{{ $item->poster }}"
                                                                 onerror="this.onerror=null;this.src='/assets/image/default_poster.jpg';"> {{-- Hatalı resim için fallback --}}
                                                        </div>
                                                        <div>
                                                            <div class="card-overlay show-icon">
                                                                <span class="absolute left-0 bottom-0 px-2 py-1 bg-gray-900 bg-opacity-75 text-yellow-400 z-10 ml-1 mb-1 uppercase text-xs rounded">{{ $item->type == 'movies' ? __('Movies') : __('Series') }}</span>
                                                                @if($item->rating)
                                                                <div class="absolute right-0 top-0 px-2 py-1 bg-gray-900 bg-opacity-75 text-yellow-400 z-10 mr-1 mt-1 uppercase text-xs flex items-center rounded">
                                                                    <span class="iconify mr-1" data-icon="akar-icons:star" data-inline="false"></span>{{ $item->rating }}
                                                                </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-details text-white p-3 w-full">
                                                        <h3 class="text-overflow card-header text-base font-semibold">{{ $item->title }}</h3>
                                                        <div class="text-overflow card-subheader text-xs text-gray-400">
                                                            @foreach ($item->genres->take(3) as $singleGenre) {{-- İlk 3 genre --}}
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
                        <div class="flex items-center text-yellow-400 text-center py-10">
                            <span class="iconify text-3xl mr-2" data-icon="mdi:alert-circle-outline"></span>
                            <span>{{ $platform->name }} {{ __('platformuna ait henüz içerik bulunmamaktadır.') }}</span>
                        </div>
                    @endif
                    {{-- Pagination --}}
                    <div class="flex mt-8 justify-center">
                        {{ $items->links('frontend.layouts.pagination') }}
                    </div>

                </div>
            </div>
            {{-- Sidebar (listings.blade.php'den alınabilir) --}}
            <div class="xl:w-3/12 w-full">
                <div class="w-full sm:p-6 space-y-8 px-10 xl:px-0">
                    @include('frontend.layouts.front-sidebar')
                </div>
            </div>
        </section>
    </div>
</div>

@endsection 