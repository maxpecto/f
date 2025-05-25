{{-- resources/views/frontend/partials/_live_search_results.blade.php --}}
@forelse($results as $item)
    <a href="{{ $item->type === 'movies' ? route('single-movie', $item->slug) : route('single-series', $item->slug) }}" class="flex items-center p-2 hover:bg-gray-600 transition duration-150 ease-in-out border-b border-gray-650 last:border-b-0">
        @php
            $originalPath = $item->poster; // Veritabanında 'assets/movies/poster/film.jpg' gibi tam yol olduğunu varsayıyoruz
            $webpPath = Str::replaceLast(pathinfo($originalPath, PATHINFO_EXTENSION), 'webp', $originalPath);
            $defaultPoster = asset('assets/frontend/images/default_poster.jpg');
        @endphp
        
        @if($item->poster)
            <picture class="w-10 h-14 object-cover rounded mr-3 flex-shrink-0 bg-gray-500">
                @if (Storage::disk('public')->exists($webpPath))
                    <source srcset="{{ Storage::url($webpPath) }}" type="image/webp">
                @endif
                @if (Storage::disk('public')->exists($originalPath))
                    <source srcset="{{ Storage::url($originalPath) }}" type="image/{{ pathinfo($originalPath, PATHINFO_EXTENSION) }}">
                    <img src="{{ Storage::url($originalPath) }}" alt="{{ $item->title }}" class="w-10 h-14 object-cover rounded">
                @else
                    <img src="{{ $defaultPoster }}" alt="{{ $item->title }}" class="w-10 h-14 object-cover rounded">
                @endif
            </picture>
        @else
            <img src="{{ $defaultPoster }}" alt="{{ $item->title }}" class="w-10 h-14 object-cover rounded mr-3 flex-shrink-0 bg-gray-500">
        @endif
        <div class="overflow-hidden">
            <div class="text-white truncate font-medium">{{ $item->title }}</div>
            <span class="text-xs text-gray-400">({{ $item->type === 'movies' ? __('Movie') : __('Series') }})</span>
        </div>
    </a>
@empty
    <div class="p-3 text-gray-400 text-center">{{ __('No results found.') }}</div>
@endforelse 