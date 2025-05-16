{{-- resources/views/frontend/partials/_live_search_results.blade.php --}}
@forelse($results as $item)
    <a href="{{ $item->type === 'movies' ? route('single-movie', $item->slug) : route('single-series', $item->slug) }}" class="flex items-center p-2 hover:bg-gray-600 transition duration-150 ease-in-out border-b border-gray-650 last:border-b-0">
        @if($item->poster)
            <img src="/assets/{{ $item->type }}/poster/{{ $item->poster }}" alt="{{ $item->title }}" class="w-10 h-14 object-cover rounded mr-3 flex-shrink-0 bg-gray-500">
        @else
            {{-- Placeholder if no poster --}}
            <div class="w-10 h-14 bg-gray-500 rounded mr-3 flex items-center justify-center text-white text-xs flex-shrink-0">{{ __('No Pic') }}</div>
        @endif
        <div class="overflow-hidden">
            <div class="text-white truncate font-medium">{{ $item->title }}</div>
            <span class="text-xs text-gray-400">({{ $item->type === 'movies' ? __('Movie') : __('Series') }})</span>
        </div>
    </a>
@empty
    <div class="p-3 text-gray-400 text-center">{{ __('No results found.') }}</div>
@endforelse 