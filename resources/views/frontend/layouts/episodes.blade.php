<div class="flex-wrap space-y-1 max-h-96 overflow-auto pr-2">
    @foreach($allepisodes as $episode)
        <a class="flex w-full bg-gray-800 text-white rounded px-4 py-2 hover:bg-yellow-500 transition duration-200 ease-out" href="{{ url($episode->series->id) }}/{{ $episode->series->slug }}/season-{{ $episode->season_id }}/episode-{{ $episode->episode_id}}">
            <div class="w-24 py-1 px-2 tracking-widest">S{{ $episode->season_id}}E{{ $episode->episode_id}}</div>
            <div class="w-full py-1 px-2">{{ $episode->name}}</div>
        </a>
    @endforeach
</div>
