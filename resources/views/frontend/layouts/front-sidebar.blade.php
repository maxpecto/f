{{-- Genres --}}
@if(count($genres) > 0 )
<div class="space-y-4">
        <div class="ui text menu m-0 flex justify-between items-center text-yellow-400 border-b-2 border-yellow-400 pb-2">
            <div class="w-full flex items-center space-x-1 ">
                <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl  font-medium">{{ __("Genres") }}</a>
            </div>
        </div>
        <div class="w-full space-y-2">
            <div class="grid grid-cols-2 gap-2">
                @foreach($genres as $genre)
                <a class="border-2 border-gray-700 px-2 py-1 rounded text-center text-white text-sm hover:bg-gray-800 hover:text-yellow-500 transition duration-200 ease-out cursor-pointer" href="{{ url('genres') }}/@php echo strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $genre->name)));  @endphp"><span>{{$genre->name}}</span></a>
                                    @endforeach
                                </div>
        </div>
        </div>
    @endif

@if($ads->activate == 1)
    <!-- Ads Start -->
    @if(isset($ads->site_300x250_banner))
    <div class="2xl:flex xl:flex lg:hidden md:hidden sm:hidden hidden py-4 justify-center">
        {!!  base64_decode($ads->site_300x250_banner) !!}
    </div>
    @endif
<!-- Ads End -->
@endif

{{-- Recommended Movies --}}
<div class="space-y-4">
    @if(count($recommendeds) >= 1)
        <div class="ui text menu m-0 sm:flex flex-wrap sm:space-y-0 space-y-2 justify-between items-center text-yellow-400 border-b-2 border-yellow-400 pb-2">
            <div class="flex items-center space-x-1">
                <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl font-medium">{{ __("Recommended") }}</a>
            </div>
            <div class="right menu flex space-x-2 items-center">
                <span class="mr-1 cursor-pointer bg-gray-700 text-sm text-white px-2 py-1 rounded hover:bg-gray-800 hover:text-yellow-500 transition duration-200 ease-out cursor-pointer"><a href="/recommendeds">{{ __("View All") }}</a></span>
            </div>
        </div>
        <div class="w-full flex flex-wrap space-y-3">
            @foreach($recommendeds as $recommended)
                @if($recommended->type == 'movies')
                    <a href="/movie/{{ $recommended->slug }}" title="{{ $recommended->title }}" class="w-full">
                        <div class="w-full flex justify-between border-b-2 border-gray-800 hover:bg-gray-900 rounded transition delay-100 duration-150 ease-in-out">
                            <div class="w-full flex flex-col p-2 justify-center space-y-2 ">
                                <h3 class="overflow-hidden text-white leading-6">{{ $recommended->title }} ({{ date('Y',strtotime($recommended->release_date)) }})</h3>
                                <div class="overflow-hidden text-white text-xs">
                                    @foreach ($recommended->genres as $singleGenre)
                                        {{ $loop->first ? '' : ', ' }}
                                        {{ $singleGenre->name }}
                                    @endforeach
                                </div>
                                <div class="flex items-center justify-between text-white">
                                    <div class="flex space-x-1 text-sm items-center" >
                                        <span class="iconify" data-icon="akar-icons:star" data-inline="false"></span>
                                        <span>{{ $recommended->rating }}</span>
                                    </div>
                                    <span class="bg-gray-700 uppercase text-white px-2 py-1 ml-2 text-sm rounded">{{ __("Movies") }}<span>
                                </div>
                            </div>
                            <div class="p-1 sm:flex hidden">
                                <div class="w-20 h-full">
                                    @php
                                        $originalPath = $recommended->poster; // Veritabanında 'assets/movies/poster/film.jpg' gibi tam yol olduğunu varsayıyoruz
                                        $webpPath = Str::replaceLast(pathinfo($originalPath, PATHINFO_EXTENSION), 'webp', $originalPath);
                                    @endphp
                                    <picture>
                                        @if (Storage::disk('public')->exists($webpPath))
                                            <source srcset="{{ Storage::url($webpPath) }}" type="image/webp">
                                        @endif
                                        @if (Storage::disk('public')->exists($originalPath))
                                            <source srcset="{{ Storage::url($originalPath) }}" type="image/{{ pathinfo($originalPath, PATHINFO_EXTENSION) }}">
                                            <img loading="lazy" alt="{{ $recommended->title }}" title="{{ $recommended->title }}" class="" src="{{ Storage::url($originalPath) }}">
                                        @else
                                            <img loading="lazy" alt="{{ $recommended->title }}" title="{{ $recommended->title }}" class="" src="{{ asset('assets/frontend/images/default_poster.jpg') }}">
                                        @endif
                                    </picture>
                                </div>
                            </div>
                        </div>
                    </a>
                @else
                    <a href="/series/{{ $recommended->slug }}" title="{{ $recommended->title }}" class="w-full">
                        <div class="w-full flex justify-between border-b-2 border-gray-800 hover:bg-gray-900 rounded transition delay-100 duration-150 ease-in-out">
                            <div class="w-full flex flex-col p-2 justify-center space-y-2 ">
                                <h3 class="overflow-hidden text-white leading-6">{{ $recommended->title }} ({{ date('Y',strtotime($recommended->release_date)) }})</h3>
                                <div class="overflow-hidden text-white text-xs">
                                    @foreach ($recommended->genres as $singleGenre)
                                        {{ $loop->first ? '' : ', ' }}
                                        {{ $singleGenre->name }}
                                    @endforeach
                                </div>
                                <div class="flex items-center justify-between text-white">
                                    <div class="flex space-x-1 text-sm items-center" >
                                        <span class="iconify" data-icon="akar-icons:star" data-inline="false"></span>
                                        <span>{{ $recommended->rating }}</span>
                                    </div>
                                    <span class="bg-gray-700 uppercase text-white px-2 py-1 ml-2 text-sm rounded">{{ __("Series") }}<span>
                                </div>
                            </div>
                            <div class="p-1 sm:flex hidden">
                                <div class="w-20 h-full">
                                    @php
                                        // Dizi için de aynı mantık, $recommended->poster zaten doğru yolu içermeli
                                        $originalPath = $recommended->poster;
                                        $webpPath = Str::replaceLast(pathinfo($originalPath, PATHINFO_EXTENSION), 'webp', $originalPath);
                                    @endphp
                                    <picture>
                                        @if (Storage::disk('public')->exists($webpPath))
                                            <source srcset="{{ Storage::url($webpPath) }}" type="image/webp">
                                        @endif
                                        @if (Storage::disk('public')->exists($originalPath))
                                            <source srcset="{{ Storage::url($originalPath) }}" type="image/{{ pathinfo($originalPath, PATHINFO_EXTENSION) }}">
                                            <img loading="lazy" alt="{{ $recommended->title }}" title="{{ $recommended->title }}" class="" src="{{ Storage::url($originalPath) }}">
                                        @else
                                            <img loading="lazy" alt="{{ $recommended->title }}" title="{{ $recommended->title }}" class="" src="{{ asset('assets/frontend/images/default_poster.jpg') }}">
                                        @endif
                                    </picture>
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
    @endif
</div>

@if($ads->activate == 1)
    <!-- Ads Start -->
    @if(isset($ads->site_300x250_banner))
    <div class="2xl:flex xl:flex lg:hidden md:hidden sm:hidden hidden py-4 justify-center">
        {!!  base64_decode($ads->site_300x250_banner) !!}
    </div>
    @endif
<!-- Ads End -->
@endif

{{-- Randoms Movies --}}
<div class="space-y-4">
    @if(count($randoms) >= 1)
        <div class="ui text menu m-0 sm:flex flex-wrap sm:space-y-0 space-y-2 justify-between items-center text-yellow-400 border-b-2 border-yellow-400 pb-2">
            <div class="flex items-center space-x-1">
                <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl font-medium">{{ __("Randoms") }}</a>
            </div>
        </div>
        <div class="w-full flex flex-wrap space-y-3">
            @foreach($randoms as $random)
            @if($random->type == 'movies')
                <a href="/movie/{{ $random->slug }}" title="{{ $random->title }}" class="w-full">
                    <div class="w-full flex justify-between border-b-2 border-gray-800 hover:bg-gray-900 rounded transition delay-100 duration-150 ease-in-out">
                        <div class="w-full flex flex-col p-2 justify-center space-y-2 ">
                            <h3 class="overflow-hidden text-white leading-6">{{ $random->title }} ({{ date('Y',strtotime($random->release_date)) }})</h3>
                            <div class="overflow-hidden text-white text-xs">
                                @foreach ($random->genres as $singleGenre)
                                    {{ $loop->first ? '' : ', ' }}
                                    {{ $singleGenre->name }}
                                @endforeach
                            </div>
                            <div class="flex items-center justify-between text-white">
                                <div class="flex space-x-1 text-sm items-center" >
                                    <span class="iconify" data-icon="akar-icons:star" data-inline="false"></span>
                                    <span>{{ $random->rating }}</span>
                                </div>
                                <span class="bg-gray-700 uppercase text-white px-2 py-1 ml-2 text-sm rounded">{{ __("Movies") }}<span>
                            </div>
                        </div>
                        <div class="p-1 sm:flex hidden">
                            <div class="w-20 h-full">
                                @php
                                    $originalPath = $random->poster; // Veritabanında 'assets/movies/poster/film.jpg' gibi tam yol olduğunu varsayıyoruz
                                    $webpPath = Str::replaceLast(pathinfo($originalPath, PATHINFO_EXTENSION), 'webp', $originalPath);
                                @endphp
                                <picture>
                                    @if (Storage::disk('public')->exists($webpPath))
                                        <source srcset="{{ Storage::url($webpPath) }}" type="image/webp">
                                    @endif
                                    @if (Storage::disk('public')->exists($originalPath))
                                        <source srcset="{{ Storage::url($originalPath) }}" type="image/{{ pathinfo($originalPath, PATHINFO_EXTENSION) }}">
                                        <img loading="lazy" alt="{{ $random->title }}" title="{{ $random->title }}" class="" src="{{ Storage::url($originalPath) }}">
                                    @else
                                        <img loading="lazy" alt="{{ $random->title }}" title="{{ $random->title }}" class="" src="{{ asset('assets/frontend/images/default_poster.jpg') }}">
                                    @endif
                                </picture>
                            </div>
                        </div>
                    </div>
                </a>
            @else
                <a href="/series/{{ $random->slug }}" title="{{ $random->title }}" class="w-full">
                    <div class="w-full flex justify-between border-b-2 border-gray-800 hover:bg-gray-900 rounded transition delay-100 duration-150 ease-in-out">
                        <div class="w-full flex flex-col p-2 justify-center space-y-2 ">
                            <h3 class="overflow-hidden text-white leading-6">{{ $random->title }} ({{ date('Y',strtotime($random->release_date)) }})</h3>
                            <div class="overflow-hidden text-white text-xs">
                                @foreach ($random->genres as $singleGenre)
                                    {{ $loop->first ? '' : ', ' }}
                                    {{ $singleGenre->name }}
                                @endforeach
                            </div>
                            <div class="flex items-center justify-between text-white">
                                <div class="flex space-x-1 text-sm items-center" >
                                    <span class="iconify" data-icon="akar-icons:star" data-inline="false"></span>
                                    <span>{{ $random->rating }}</span>
                                </div>
                                <span class="bg-gray-700 uppercase text-white px-2 py-1 ml-2 text-sm rounded">{{ __("Series") }}<span>
                            </div>
                        </div>
                        <div class="p-1 sm:flex hidden">
                            <div class="w-20 h-full">
                                @php
                                    // Dizi için de aynı mantık, $random->poster zaten doğru yolu içermeli
                                    $originalPath = $random->poster;
                                    $webpPath = Str::replaceLast(pathinfo($originalPath, PATHINFO_EXTENSION), 'webp', $originalPath);
                                @endphp
                                <picture>
                                    @if (Storage::disk('public')->exists($webpPath))
                                        <source srcset="{{ Storage::url($webpPath) }}" type="image/webp">
                                    @endif
                                    @if (Storage::disk('public')->exists($originalPath))
                                        <source srcset="{{ Storage::url($originalPath) }}" type="image/{{ pathinfo($originalPath, PATHINFO_EXTENSION) }}">
                                        <img loading="lazy" alt="{{ $random->title }}" title="{{ $random->title }}" class="" src="{{ Storage::url($originalPath) }}">
                                    @else
                                        <img loading="lazy" alt="{{ $random->title }}" title="{{ $random->title }}" class="" src="{{ asset('assets/frontend/images/default_poster.jpg') }}">
                                    @endif
                                </picture>
                            </div>
                        </div>
                    </div>
                </a>
            @endif
            @endforeach
        </div>
    @endif
</div>

@if($ads->activate == 1)
    <!-- Ads Start -->
    @if(isset($ads->site_300x250_banner))
    <div class="2xl:flex xl:flex lg:hidden md:hidden sm:hidden hidden py-4 justify-center">
        {!!  base64_decode($ads->site_300x250_banner) !!}
    </div>
    @endif
<!-- Ads End -->
@endif

{{-- Years --}}
@if(count($years) > 0 )
    <div class="space-y-4 pb-10">
        <div class="ui text menu m-0 flex justify-between items-center text-yellow-400 border-b-2 border-yellow-400 pb-2">
            <div class="w-full flex items-center space-x-1 ">
                <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item text-xl  font-medium">{{ __("Years") }}</a>
            </div>
        </div>
        <div class="w-full space-y-2">
            <div class="grid grid-cols-2 gap-2">
                @foreach($years as $year)
                <a class="border-2 border-gray-700 px-2 py-1 rounded text-center text-white text-sm hover:bg-gray-800 hover:text-yellow-500 transition duration-200 ease-out cursor-pointer" href="{{ url('years') }}/@php echo strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $year->name)));  @endphp"><span>{{$year->name}}</span></a>
                @endforeach
            </div>
        </div>
    </div>
@endif
