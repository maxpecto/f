<a href="{{ url($latest->series->id) }}/{{$latest->series->slug}}/season-{{ $latest->season_id }}/episode-{{ $latest->episode_id}}" title="{{ $latest->series->title }} (S{{ $latest->season_id }}E{{ $latest->episode_id }})" >
    <div class="p-2">
        <div class="flex items-center justify-between p-2 bg-gray-800 space-x-2">
            <div class="w-3/12">
                <img alt="{{ $latest->series->title }} (S{{ $latest->season_id }}E{{ $latest->episode_id }})" title="{{ $latest->series->title }} (S{{ $latest->season_id }}E{{ $latest->episode_id }})" class="original-image" src="/assets/series/poster/{{ $latest->series->poster }}">
            </div>
            <div class="w-9/12 space-x-1 truncate text-white"> 
                <span class="text-sm">
                    {{ $latest->series->title }}
                </span>
                <span class="text-sm">
                    (S{{ $latest->season_id }}E{{ $latest->episode_id }})
                </span>
            </div>
        </div>
    </div>
</a>