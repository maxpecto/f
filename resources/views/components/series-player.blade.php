@php
    $initialPlayerUrl = $series->trailer; // Varsayılan olarak fragman
    if (isset($firstEpisodeToShow) && $firstEpisodeToShow && isset($firstEpisodeToShow->player)) {
        $playerData = json_decode($firstEpisodeToShow->player, true);
        // Oynatıcı verisinin yapısını kontrol et ve ilk URL'yi al
        if ($playerData && isset($playerData['url']) && is_array($playerData['url']) && !empty($playerData['url'][0])) {
            $initialPlayerUrl = $playerData['url'][0];
        } elseif ($playerData && isset($playerData['url']) && is_string($playerData['url']) && !empty($playerData['url'])){
            // Bazen direkt string olarak gelebilir
            $initialPlayerUrl = $playerData['url'];
        }
    }
@endphp

<div id="player-wrapper">
    <div class="ui top attached borderless p-0" id="series-player">
        <div class="ui embed" data-url="{{ $initialPlayerUrl }}" data-placeholder="/assets/series/backdrop/{{ $series->backdrop }}" data-icon="play circle outline">
        </div>
    </div>

    {{-- Seasons And Episodes --}}
    <div class="w-full md:flex md:space-x-4 space-x-0 md:px-10 py-5 bg-gray-900 ">
        @if(count($uniqueSeason) > 0)
            <div class="md:w-1/5 w-full">
                <div class="space-y-2 md:flex-wrap flex max-h-96 overflow-auto md:mb-0 mb-5 items-center pr-2" id="series-players">
                    <a href="javascript:void(0)" data-series="{{ $series->id }}" class="allitem w-full border-r-0 md:border-r-4 border-yellow-400 text-yellow-400 text-white text-center md:text-left rounded px-4 py-2"><span class="py-1 px-2">{{ __("All Episodes") }}</span></a>
                    @foreach($uniqueSeason as $seasons)
                        <a href="javascript:void(0)" data-series="{{ $series->id }}" data-season="{{ $seasons->season_id }}" class="item w-full text-white text-center md:text-left rounded px-4 py-2 "><span class="py-1 px-2">{{ __("Season") }} {{ $seasons->season_id }}</span></a>
                    @endforeach
                </div>
            </div>
            <div class="md:w-4/5 w-full">
                <div id="season-episodes" class="w-full">
                    <div class="flex-wrap space-y-1 max-h-96 overflow-auto pr-2">
                        @foreach($allepisodes as $episode)
                            <a class="flex w-full bg-gray-800 text-white rounded px-4 py-2 hover:bg-yellow-500 hover:text-white transition duration-200 ease-out" href="{{ url($episode->series->id) }}/{{ $episode->series->slug }}/season-{{ $episode->season_id }}/episode-{{ $episode->episode_id}}">
                                <div class="w-24 py-1 px-2 tracking-widest">S{{ $episode->season_id}}E{{ $episode->episode_id}}</div>
                                <div class="w-full py-1 px-2">{{ $episode->name}}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="m-2 px-4 py-2 text-white">{{ __("There is no season set for this series!") }}</div>
        @endif
    </div>

    {{-- Report --}}
    <div class="flex justify-end items-center" >
        <div class="p-2">
            <!-- Update Modal Start -->
            <div x-cloak x-data="{ openReport: false }">
                <span class="leading-6"><button type="button" class="flex cursor-pointer items-center border-2 border-red-900 px-4 py-1 rounded text-white hover:bg-red-800 hover:text-white" @click="openReport = true"><span class="iconify" data-icon="ic:baseline-bug-report" data-inline="false" ></span><span class="ml-1">{{ __("Report") }}</span></button></span>
                <div class="fixed z-50  top-0 left-0 flex items-center justify-center w-full h-full" style="background-color: rgba(0,0,0,.5);" x-show="openReport">
                    <div class="h-auto p-4 mx-2 text-left bg-gray-900 rounded shadow-xl w-9/12 md:max-w-xl md:p-6 lg:p-8 md:mx-0" @click.away="openReport = false">
                            {!! Form::open(['route' => 'admin.reports-store','method'=>'post']) !!}
                            <div class="text-left">
                                <h3 class="text-lg font-medium leading-6 text-white uppercase border-1 border-b border-gray-600 w-max pb-4">
                                    {{ __("Report") }}
                                </h3>
                                <div class="mt-6">
                                    <p class="text-sm leading-5 text-gray-500">
                                        <select id="report_id" name="report_id" required="true">
                                            <option value="">{{ __("Report") }}</option>
                                            <option value="1">{{ __("The video not working") }}</option>
                                            <option value="2">{{ __("Subtitle error") }}</option>
                                            <option value="3">{{ __("Other") }}</option>
                                        </select>
                                    </p>
                                    <p class="text-sm leading-5 text-gray-500">
                                        <textarea type="text" name="report_desc" class="w-full rounded p-4 h-20 bg-gray-600 text-white leading-6" placeholder="{{ __("Could you please give some detail about the problem ?") }}"></textarea>
                                    </p>
                                    <input name="items_id" class="hidden" value="{{ $series->id }}">
                                    <input name="items_type" class="hidden" value="1">
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6">
                                <span class="flex w-full rounded-md shadow-sm space-x-4">
                                    <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-700 focus:outline-none">{{ __("Report") }}</button>
                                    <a @click="openReport = false" class="inline-flex justify-center w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-700 cursor-pointer focus:outline-none">
                                        {{ __("Close") }}
                                    </a>
                                </span>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div x-cloak x-data="{ openReport: true }">
                    <div class="fixed z-50  top-0 left-0 flex items-center justify-center w-full h-full" style="background-color: rgba(0,0,0,.5);" x-show="openReport">
                        <div class="h-auto p-4 mx-2 text-left bg-gray-900 rounded shadow-xl w-9/12 md:max-w-xl md:p-6 lg:p-8 md:mx-0" @click.away="openReport = false">
                            <div class="text-left">
                                <h3 class="text-lg font-medium leading-6 text-white uppercase border-1 border-b border-gray-600 w-max pb-4">
                                    {{ __("Message") }}
                                </h3>
                                <div class="mt-6">
                                    <p class="text-sm leading-5 text-gray-500">
                                        {{$errors->first()}}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6">
                                <span class="flex w-full rounded-md shadow-sm space-x-4">
                                    <a @click="openReport = false" class="inline-flex justify-center w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-700 cursor-pointer focus:outline-none">
                                        {{ __("Close") }}
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>

</div>

<script>
    $('#series-players span').on('click', function(event){
        event.preventDefault();
        $('#series-players span').removeClass("bg-yellow-400");
        $(this).addClass("bg-yellow-400");
    });

    $('#series-players .item').on('click', function(){
        $('#series-players .item').removeClass('border-r-0 md:border-r-4 border-yellow-400 text-yellow-400');
        $('#series-players .allitem').removeClass('border-r-0 md:border-r-4 border-yellow-400 text-yellow-400');
        var series_id    = $(this).data('series');
        var season_id    = $(this).data('season');
        var thisClass = $(this);

        $.ajax({
            type: "GET",
            url: '/get-season-episodes/series-'+series_id+'/season-'+season_id,
            success: function(data){
                $(thisClass).toggleClass('border-r-0 md:border-r-4 border-yellow-400 text-yellow-400');
                $data = $(data);
                $('#season-episodes').hide().html($data).fadeIn()
            }
        });
    });

    $('#series-players .allitem').on('click', function(){
        $('#series-players .item').removeClass('border-r-0 md:border-r-4 border-yellow-400 text-yellow-400');
        $('#series-players .allitem').removeClass('border-r-0 md:border-r-4 border-yellow-400 text-yellow-400');
        var series_id    = $(this).data('series');
        var thisClass = $(this);

        $.ajax({
            type: "GET",
            url: '/get-all-episodes/series-'+series_id,
            success: function(data){
                $(thisClass).toggleClass('border-r-0 md:border-r-4 border-yellow-400 text-yellow-400');
                $data = $(data);
                $('#season-episodes').hide().html($data).fadeIn()
            }
        });
    });
</script>

