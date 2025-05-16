@if ($paginator->hasPages())
    <nav aria-label="...">
        <ul class="flex justify-start flex-wrap">
            @if ($paginator->onFirstPage())
                <li class="px-4 py-2 bg-gray-800 text-white uppercase disabled rounded-l mx-1 hidden cursor-not-allowed"><a class="page-link">{{ __("Previous")}}</a></li>
            @else
                <li class="px-4 py-2 bg-gray-900 text-white uppercase rounded-l mx-1"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">{{ __("Previous")}}</a></li>
            @endif

            @foreach ($elements as $element)

                @if (is_string($element))
                    <li class="px-4 py-2 bg-gray-800 text-white uppercase rounded mx-1 hidden cursor-not-allowed"><a class="page-link">{{ $element }}</a></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="px-4 py-2 bg-yellow-500 text-white uppercase rounded mx-1"><a class="page-link">{{ $page }}</a></li>
                        @else
                            <li class="px-4 py-2 bg-gray-900 text-white uppercase rounded mx-1"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="px-4 py-2 bg-gray-900 text-white uppercase mx-1 rounded-r"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __("Next")}}</a></li>
            @else
                <li class="px-4 py-2 bg-gray-800 text-white uppercase mx-1 rounded-r hidden cursor-not-allowed"><a class="page-link">{{ __("Next")}}</a></li>
            @endif
        </ul>
    </nav>
@endif
