<div id="player-wrapper">
    {{-- Embbed  --}}
    <div class="ui top attached segment borderless p-0" id="player-embeded" @if($general->site_player == 'embeded') style="display: block;" @else style="display: none;" @endif>
        <div class="ui embed">
            <iframe id="playerEmbeded" src="" allowfullscreen allowtransparency allow="autoplay" poster="/assets/movies/backdrop/{{ $movies->backdrop }}"></iframe>
        </div>
        @if($general->site_player == 'embeded')
            <script>
                $(function(){
                    var url = document.getElementById("embeded").getAttribute("data-url");
                    var source = document.getElementById('playerEmbeded');
                    source.setAttribute('src', url);
                });
            </script>
        @endif
    </div>

    {{-- Direct/HLS --}}
    <div class="ui top attached segment borderless p-0" id="player-videojs" @if($general->site_player == 'hls' || $general->site_player == 'direct' || $general->site_player == 'youtube' || $general->site_player == 'trailer') style="display: block;" @else style="display: none;" @endif>
        <video id="playerVideojs" class="video-js vjs-big-play-centered ui embed w-full" controls poster="/assets/movies/backdrop/{{ $movies->backdrop }}" >
            <p class="vjs-no-js">
                {{ __("To view this video please enable JavaScript, and consider upgrading to a web browser that") }} <a href="https://videojs.com/html5-video-support/" target="_blank">{{ __("supports HTML5 video") }}</a>
            </p>
        </video>

        @if($general->site_player == 'hls' || $general->site_player == 'direct' || $general->site_player == 'youtube' || $general->site_player == 'trailer')
            <script>
                $(function(){
                    @if($general->site_player == 'direct')
                        var url = document.getElementById("direct").getAttribute("data-url");
                        var type = document.getElementById("direct").getAttribute("data-type");
                        var videotype = 'video/mp4';
                    @elseif($general->site_player == 'hls')
                        var url = document.getElementById("hls").getAttribute("data-url");
                        var type = document.getElementById("hls").getAttribute("data-type");
                        var videotype = 'application/x-mpegURL';
                    @elseif($general->site_player == 'youtube')
                        var url = document.getElementById("youtube").getAttribute("data-url");
                        var type = document.getElementById("youtube").getAttribute("data-type");
                        var videotype = 'video/youtube';
                    @elseif($general->site_player == 'trailer')
                        var url = document.getElementById("trailer").getAttribute("data-url");
                        var type = document.getElementById("trailer").getAttribute("data-type");
                        var videotype = 'video/youtube';
                    @endif

                    document.getElementById('player-videojs').style.display = 'block';
                    document.getElementById('player-embeded').style.display = 'none';

                    var videoOptions = {
                        controls: true,
                        playbackRates: [0.5, 1, 1.5, 2, 2.5],
                        plugins : {
                            hotkeys : {
                                volumeStep: 0.1,
                                seekStep: 5,
                                enableModifiersForNumbers: false
                            },
                            vastClient : {
                                adTagUrl: '{!! base64_decode($ads->site_vast_url) !!}',
                                adCancelTimeout : 5000,
                                adsEnabled : true
                            }
                        }
                    };
                    var player = videojs('playerVideojs', videoOptions);
                    player.src({ type: videotype, src: url });

                });
            </script>
        @endif
    </div>

    <div class="sm:flex justify-between items-center">
        <div class="flex space-x-1 w-full p-2 whitespace-nowrap overflow-y-auto pb-4" id="movie-players">
            @if(isset($player['type']))
                @foreach($player['type'] as $key => $value)
                    @if($value == 'direct')
                        @if(!empty($player['url'][$key]))
                            <a class="nav-btn border-2 border-yellow-400 px-4 py-1 rounded text-white hover:bg-yellow-500 hover:text-white cursor-pointer" id="direct" data-type="direct" data-url="{{$player['url'][$key]}}">{{$player['name'][$key]}}</a>
                        @endif
                    @elseif($value == 'hls')
                        @if(!empty($player['url'][$key]))
                            <a class="nav-btn border-2 border-yellow-400 px-4 py-1 rounded text-white hover:bg-yellow-500 hover:text-white cursor-pointer" id="hls" data-type="hls" data-url="{{$player['url'][$key]}}">{{$player['name'][$key]}}</a>
                        @endif
                    @elseif($value == 'youtube')
                        @if(!empty($player['url'][$key]))
                            <a class="nav-btn border-2 border-yellow-400 px-4 py-1 rounded text-white hover:bg-yellow-500 hover:text-white cursor-pointer" id="youtube" data-type="youtube" data-url="{{$player['url'][$key]}}">{{$player['name'][$key]}}</a>
                        @else
                            <div class="m-2 px-4 py-2 text-white">{{ __("There is no player set for this movie!") }}</div>
                        @endif
                    @elseif($value == 'embeded')
                        @if(!empty($player['url'][$key]))
                            <a class="nav-btn border-2 border-yellow-400 px-4 py-1 rounded text-white hover:bg-yellow-500 hover:text-white cursor-pointer" id="embeded" data-type="embeded" data-url="{{$player['url'][$key]}}">{{$player['name'][$key]}}</a>
                        @endif
                    @else
                        <div class="m-2 px-4 py-2 text-white">{{ __("There is no player set for this movie!") }}</div>
                    @endif
                @endforeach
            @endif
        </div>

        {{-- Report Button --}}
        <div class="p-2">
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
                                    <input name="items_id" class="hidden" value="{{ $movies->id }}">
                                    <input name="items_type" class="hidden" value="0">
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
        {{-- End Report Button --}}
    </div>
</div>

<script>
    $(function(){
        $('#movie-players a').on('click', function(){
            $('#movie-players a').not(this).removeClass('active');
            $(this).addClass('active');

            var url = $(this).attr('data-url');
            var type = $(this).attr('data-type');

            var videoOptions = {
                controls: true,
                playbackRates: [0.5, 1, 1.5, 2, 2.5],
                plugins : {
                    hotkeys : {
                        volumeStep: 0.1,
                        seekStep: 5,
                        enableModifiersForNumbers: false
                    },
                    vastClient : {
                        adTagUrl: '{!! base64_decode($ads->site_vast_url) !!}',
                        adCancelTimeout : 5000,
                        adsEnabled : true
                    }
                }
            };
            var player = videojs('playerVideojs', videoOptions);
            if(type == 'direct' || type == 'hls' || type == 'youtube' || type == 'trailer'){
                var source = document.getElementById('playerEmbeded');
                source.setAttribute('src', '');

                document.getElementById('player-videojs').style.display = 'block';
                document.getElementById('player-embeded').style.display = 'none';

                if(type == 'direct'){
                    var videotype = 'video/mp4';
                }else if(type == 'hls'){
                    var videotype = 'application/x-mpegURL';
                }else if(type == 'youtube'){
                    var videotype = 'video/youtube';
                }else if(type == 'trailer'){
                    var videotype = 'video/youtube';
                }

                player.src({ type: videotype, src: url });
            }else if(type == 'embeded'){
                if(!player.paused()){
                    console.log('paused video!');
                    player.pause();
                }

                document.getElementById('player-videojs').style.display = 'none';
                document.getElementById('player-embeded').style.display = 'block';

                var source = document.getElementById('playerEmbeded');
                source.setAttribute('src', url);
            }

        });
    });
</script>
