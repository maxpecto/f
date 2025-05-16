@extends('layouts.backend')

@section('content')

<!-- Message -->
@if ($message = Session::get('success'))
    <div x-data="{ show: true }" x-show="show"
        class="mb-4 flex justify-between items-center bg-green-500 relative text-white py-2 px-4 rounded">
        <div>
            {{ $message }}
        </div>
        <div>
            <button type="button" @click="show = false" class="text-white focus:outline-none">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
    </div>
@endif

@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <div x-data="{ show: true }" x-show="show"
            class="mb-4 flex justify-between items-center bg-red-500 relative text-white py-2 px-4 rounded">
            <div>
                {{ $error }}
            </div>
            <div>
                <button type="button" @click="show = false" class="text-white focus:outline-none">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
        </div>
    @endforeach
@endif

@if(Session::has('message'))
<div x-data="{ show: true }" x-show="show"
    class="mb-4 flex justify-between items-center bg-red-500 relative text-white py-2 px-4 rounded">
    <div>
        {{ Session::get('progress') }}
    </div>
    <div>
        <button type="button" @click="show = false" class="text-white focus:outline-none">
            <span class="text-2xl">&times;</span>
        </button>
    </div>
</div>
@endif
<!-- Message End -->

<div class="w-full p-5 text-white bg-gray-900 rounded-t flex items-center space-x-2">
    <span>Edit </span><span class="text-green-400 font-bold">" {{ $episodes->series->title }} (S{{ $episodes->season_id }}E{{ $episodes->episode_id }}) "</span>
</div>
<section class="w-full text-white rounded-b">
    {!! Form::model($episodes, ['route' => ['admin.episodes-update',$episodes->id],'method'=>'put','enctype' => 'multipart/form-data','class' => 'form movie']) !!}
    <div class="bg-gray-700 justify-between items=center border-1 border-t border-gray-700 p-5 w-full xl:flex space-x-0 xl:space-x-6 space-y-6 xl:space-y-0">
        <div class="w-full xl:w-3/12 space-y-6">
            <div class="w-full field image">
                <img src="{{  asset('/assets/episodes/backdrop/'.$episodes->backdrop) }}" alt="backdrop image" id="episode_image" class="w-full">
                <input type="hidden" name="episode_image_url" value="{{  asset('/assets/episodes/backdrop/'.$episodes->backdrop) }}">
                <input type="file" accept="image/*" name="episode_image" class="hidden">
                <button type="button" class="w-full bg-green-500 p-2 rounded-b">Select Backdrop</button>
            </div>

            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Air Date (YYYY-MM-DD)</h4></label>
                  <div class="w-full">
                    <input name="episode_airdate" type="text" placeholder="Air Date" value="{{ $episodes->air_date }}" class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
        </div>
        <div class="w-full xl:w-9/12 space-y-6">
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Episode Name</h4></label>
                  <div class="control">
                    <input name="episode_name" id="episode_name" type="text" placeholder="Episode Name" value="{{ $episodes->name }}" class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Description</h4></label>
                <div class="control">
                    <textarea name="episode_description" placeholder="Description" class="w-full rounded p-4 h-20 bg-gray-600 text-white leading-6">{{ $episodes->description }}</textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Player</h4></label>
                <div class="field_player_wrapper space-y-2">
                    @if(isset($player) && isset($player['type']) && is_array($player['type']))
                        @php $i = 1 @endphp
                        @foreach($player['type'] as $key => $value)
                            @if($i == 1)
                                <div class="flex space-x-2">
                                    @if($value == 'youtube')
                                        <select name="player_type[]" placeholder="Player Type.." class="w-1/5 rounded p-2 bg-gray-600 text-white">
                                            <option selected value="youtube">Youtube</option>
                                            <option value="direct">Direct(Mp4, Mkv etc..)</option>
                                            <option value="hls">HLS (.m3u8)</option>
                                            <option value="embeded">Embeded</option>
                                        </select>
                                    @elseif($value == 'direct')
                                        <select name="player_type[]" placeholder="Player Type.." class="w-1/5 rounded p-2 bg-gray-600 text-white">
                                            <option value="youtube">Youtube</option>
                                            <option selected value="direct">Direct(Mp4, Mkv etc..)</option>
                                            <option value="hls">HLS (.m3u8)</option>
                                            <option value="embeded">Embeded</option>
                                        </select>
                                    @elseif($value == 'hls')
                                        <select name="player_type[]" placeholder="Player Type.." class="w-1/5 rounded p-2 bg-gray-600 text-white">
                                            <option value="youtube">Youtube</option>
                                            <option value="direct">Direct(Mp4, Mkv etc..)</option>
                                            <option selected value="hls">HLS (.m3u8)</option>
                                            <option value="embeded">Embeded</option>
                                        </select>
                                    @elseif($value == 'embeded')
                                        <select name="player_type[]" placeholder="Player Type.." class="w-1/5 rounded p-2 bg-gray-600 text-white">
                                            <option value="youtube">Youtube</option>
                                            <option value="direct">Direct(Mp4, Mkv etc..)</option>
                                            <option value="hls">HLS (.m3u8)</option>
                                            <option selected value="embeded">Embeded</option>
                                        </select>
                                    @endif
                                    <input type="text" name="player_name[]" value="{{$player['name'][$key]}}" placeholder="Player Name.." class="w-1/5 rounded p-2 bg-gray-600 text-white"/>
                                    <input type="text" name="player_url[]" value="{{$player['url'][$key]}}" placeholder="Player Url.. (if embeded dont put whole iframe just put the link.)" class="w-full rounded p-2 bg-gray-600 text-white"/>
                                    <a href="javascript:void(0);" class="add_player bg-gray-900 text-white rounded flex items-center p-2" title="Add Player">
                                        <span class="iconify" data-icon="fluent:add-12-filled" data-inline="false"></span>
                                    </a>
                                </div>
                            @else
                                <div class="flex space-x-2">
                                    @if($value == 'youtube')
                                        <select name="player_type[]" placeholder="Player Type.." class="w-1/5 rounded p-2 bg-gray-600 text-white">
                                            <option selected value="youtube">Youtube</option>
                                            <option value="direct">Direct(Mp4, Mkv etc..)</option>
                                            <option value="hls">HLS (.m3u8)</option>
                                            <option value="embeded">Embeded</option>
                                        </select>
                                    @elseif($value == 'direct')
                                        <select name="player_type[]" placeholder="Player Type.." class="w-1/5 rounded p-2 bg-gray-600 text-white">
                                            <option value="youtube">Youtube</option>
                                            <option selected value="direct">Direct(Mp4, Mkv etc..)</option>
                                            <option value="hls">HLS (.m3u8)</option>
                                            <option value="embeded">Embeded</option>
                                        </select>
                                    @elseif($value == 'hls')
                                        <select name="player_type[]" placeholder="Player Type.." class="w-1/5 rounded p-2 bg-gray-600 text-white">
                                            <option value="youtube">Youtube</option>
                                            <option value="direct">Direct(Mp4, Mkv etc..)</option>
                                            <option selected value="hls">HLS (.m3u8)</option>
                                            <option value="embeded">Embeded</option>
                                        </select>
                                    @elseif($value == 'embeded')
                                        <select name="player_type[]" placeholder="Player Type.." class="w-1/5 rounded p-2 bg-gray-600 text-white">
                                            <option value="youtube">Youtube</option>
                                            <option value="direct">Direct(Mp4, Mkv etc..)</option>
                                            <option value="hls">HLS (.m3u8)</option>
                                            <option selected value="embeded">Embeded</option>
                                        </select>
                                    @endif
                                    <input type="text" name="player_name[]" value="{{$player['name'][$key]}}" placeholder="Player Name.." class="w-1/5 rounded p-2 bg-gray-600 text-white"/>
                                    <input type="text" name="player_url[]" value="{{$player['url'][$key]}}" placeholder="Player Url.. (if embeded dont put whole iframe just put the link.)" class="w-full rounded p-2 bg-gray-600 text-white"/>
                                    <a href="javascript:void(0);" class="remove_player bg-red-900 text-white rounded flex items-center p-2">
                                        <span class="iconify" data-icon="line-md:remove" data-inline="false"></span>
                                    </a>
                                </div>
                            @endif
                            @php $i++ @endphp
						@endforeach
					@else
                        <div class="flex space-x-2">
                            <select name="player_type[]" placeholder="Player Type.." class="w-1/5 rounded p-2 bg-gray-600 text-white">
                                <option value="youtube">Youtube</option>
                                <option value="direct">Direct(Mp4, Mkv etc..)</option>
                                <option value="hls">HLS (.m3u8)</option>
                                <option value="embeded">Embeded</option>
                            </select>
                            <input type="text" name="player_name[]" value="" placeholder="Player Name.." class="w-1/5 rounded p-2 bg-gray-600 text-white"/>
                            <input type="text" name="player_url[]" value="" placeholder="Player Url.. (if embeded dont put whole iframe just put the link.)" class="w-full rounded p-2 bg-gray-600 text-white"/>
                            <a href="javascript:void(0);" class="add_player bg-gray-900 text-white rounded flex items-center p-2" title="Add Download Links">
                                <span class="iconify" data-icon="fluent:add-12-filled" data-inline="false"></span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Download</h4></label>
                <div class="field_download_wrapper space-y-2">
                    @if(isset($download))
                        @php $i = 1 @endphp
                        @foreach($download as $key => $value)
                            @if($i == 1)
                                <div class="flex space-x-2">
                                    <input type="text" name="download_name[]" value="{{ $key }}" placeholder="Download Name.." class="w-1/5 rounded p-2 bg-gray-600 text-white"/>
                                    <input type="text" name="download_url[]" value="{{ $value }}" placeholder="Download Links" class="w-full rounded p-2 bg-gray-600 text-white"/>
                                    <a href="javascript:void(0);" class="add_download bg-gray-900 text-white rounded flex items-center p-2" title="Add Download">
                                        <span class="iconify" data-icon="fluent:add-12-filled" data-inline="false"></span>
                                    </a>
                                </div>
                            @else
                                <div class="flex space-x-2">
                                    <input type="text" name="download_name[]" value="{{ $key }}" placeholder="Download Name.." class="w-1/5 rounded p-2 bg-gray-600 text-white"/>
                                    <input type="text" name="download_url[]" value="{{ $value }}" placeholder="Download Links" class="w-full rounded p-2 bg-gray-600 text-white"/>
                                    <a href="javascript:void(0);" class="remove_download bg-red-900 text-white rounded flex items-center p-2">
                                        <span class="iconify" data-icon="line-md:remove" data-inline="false"></span>
                                    </a>
                                </div>
                            @endif
                            @php $i++ @endphp
                        @endforeach
                    @else
                        <div class="flex space-x-2">
                            <input type="text" name="download_name[]" value="" placeholder="Download Name.." class="w-1/5 rounded p-2 bg-gray-600 text-white"/>
                            <input type="text" name="download_url[]" value="" placeholder="Download Links" class="w-full rounded p-2 bg-gray-600 text-white"/>
                            <a href="javascript:void(0);" class="add_download bg-gray-900 text-white rounded flex items-center p-2" title="Add Download">
                                <span class="iconify" data-icon="fluent:add-12-filled" data-inline="false"></span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="bg-gray-900 justify-between items=center border-1 border-t border-gray-700 p-5 w-full flex items-center">
        <button type="submit" class="bg-green-500 rounded text-white px-4 py-2">Update</button>
        <a href="/admin/episodes" class="bg-gray-500 rounded text-white px-4 py-2">Cancel</a>
    </div>
    {!! Form::close() !!}
</section>
@endsection

@section('js')

<script type="text/javascript">
    //Player and Download
    $(document).ready(function(){
        //Players
        var addPlayerButton = $('.add_player'); //Add button selector
        var playerwrapper = $('.field_player_wrapper'); //Input field wrapper
        var playerfieldHTML = '<div class="flex space-x-2"><select name="player_type[]" placeholder="Player Type.." class="w-1/5 rounded p-2 bg-gray-600 text-white"><option value="youtube">Youtube</option><option value="direct">Direct(Mp4, Mkv etc..)</option><option value="hls">HLS (.m3u8)</option><option value="embeded">Embeded</option></select><input type="text" name="player_name[]" value="" placeholder="Player Name.." class="w-1/5 rounded p-2 bg-gray-600 text-white"/><input type="text" name="player_url[]" value="" placeholder="Player Url.." class="w-full rounded p-2 bg-gray-600 text-white"/><a href="javascript:void(0);" class="remove_player bg-red-900 text-white rounded flex items-center p-2"><span class="iconify" data-icon="line-md:remove" data-inline="false"></span></a></div>'; //New input field html

        //Once add button is clicked
        $(addPlayerButton).click(function(){
            $(playerwrapper).append(playerfieldHTML); //Add field html
        });

        //Once remove button is clicked
        $(playerwrapper).on('click', '.remove_player', function(e){
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
        });

        //Download
        var addDownloadButton = $('.add_download'); //Add button selector
        var downloadwrapper = $('.field_download_wrapper'); //Input field wrapper
        var downloadfieldHTML = '<div class="flex space-x-2"><input type="text" name="download_name[]" value="" placeholder="Download Name.." class="w-1/5 rounded p-2 bg-gray-600 text-white"/><input type="text" name="download_url[]" value="" placeholder="Download Links" class="w-full rounded p-2 bg-gray-600 text-white"/><a href="javascript:void(0);" class="remove_download bg-red-900 text-white rounded flex items-center p-2"><span class="iconify" data-icon="line-md:remove" data-inline="false"></span></a></div>'; //New input field html

        //Once add button is clicked
        $(addDownloadButton).click(function(){
            $(downloadwrapper).append(downloadfieldHTML); //Add field html
        });
        //Once remove button is clicked
        $(downloadwrapper).on('click', '.remove_download', function(e){
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
        });
    });

    //Episodes
    $(document).ready(function(){
        //Series list
        var $series = $('#series_list').selectize({
            create      : true,
            sortField   : 'text',
        });
        var seriesList = $series[0].selectize;
        $.getJSON('../data/get_series', function(genresData) {
            $.each(genresData, function(val, key) {
                    seriesList.addOption({ value: key.id, text: key.title + ' [ TMDB ID : '+ key.tmdb_id +' ]' });
            });
        });

        //Series Season list
        var $season = $('.select #series_seasons').selectize({
            create: false,
        });
        var seasonList = $season[0].selectize;

        //Series Episode list
        var $episode = $('.select #series_episode').selectize({
            create: false,
        });
        var episodeList = $episode[0].selectize;

        // Search Season
        $('#series_id button').click(function(){
            var seriesID = $('#series_id select[name="series_list"]').val();
            if(seriesID > 0){
                fetchSeason(seriesID);
            }else{
                alert('TMDB added series can only be genrated seasons!');
            }
        });

        function fetchSeason(id){
            $('#series_id .spin').removeClass('hidden');
            $.ajax({
                url: 'get_series/'+id,
                type: 'get',
                dataType: 'json',
                success: function(data) {
                    if(data['name'] === undefined){
                        $('#series_id .spin').addClass('hidden');
                        //not valid series id!
                        alert(data['status_message']);
                    }
                    else{
                        $('#series_seasons .select').removeClass('hidden');
                        $('#series_seasons button').removeClass('hidden');
                        $('#series_seasons .addown').addClass('hidden');
                        var seasons;
                        for (seasons = 1; seasons <= data['number_of_seasons']; seasons++) {
                            seasonList.addOption({ value: seasons, text: "Season "+seasons });
                        }
                        $('#series_id .spin').addClass('hidden');
                    }
                }
            });
        }
        //Fetch End

        // Fetch Episode
        $('#series_seasons button').click(function(){
            var seriesSeasonID = $('#series_seasons select[name="tmdb_series_seasons"]').val();
            var seriesID = $('#series_id select[name="series_list"]').val();
            fetchEpisodes(seriesSeasonID,seriesID);
        });
        function fetchEpisodes(seriesSeasonID,seriesID){
            $('#series_seasons .spin').removeClass('hidden');
            $.ajax({
                url: 'get_series/'+seriesID+'/'+seriesSeasonID,
                type: 'get',
                dataType: 'json',
                success: function(data){
                    $('#series_episode .select').removeClass('hidden');
                    $('#series_episode button').removeClass('hidden');
                    $('#series_episode .addown').addClass('hidden');
                    $.each(data.episodes, function(val, key) {
                        episodeList.addOption({ value: key.episode_number, text: "Episode "+key.episode_number });
                    });
                    $('#series_seasons .spin').addClass('hidden');
                }
            });
        }
        //Fetch End

        // Fetch Episode Data
        $('#series_episode button').click(function(){
            var seriesSeasonID = $('#series_seasons select[name="tmdb_series_seasons"]').val();
            var seriesEpisodeID = $('#series_episode select[name="tmdb_series_episode"]').val();
            var seriesID = $('#series_id select[name="series_list"]').val();

            fetchEpisodesData(seriesSeasonID,seriesEpisodeID,seriesID);
        });
        function fetchEpisodesData(seriesSeasonID,seriesEpisodeID,seriesID){
            $('#series_episode .spin').removeClass('hidden');
            $.ajax({
                url: 'get_series/'+seriesID+'/'+seriesSeasonID+'/'+seriesEpisodeID,
                type: 'get',
                dataType: 'json',
                success: function(data){
                    //title
                    $('input[name="episode_name"]').val(data['name']);
                    //desc
                    $('textarea[name="episode_description"]').val(data['overview']);
                    //Air Date
                    $('input[name="episode_airdate"]').val(data['air_date']);

                    //Backdrop Image
                    if (data['still_path'] == null) {
                        var episode_image = '{{ URL::asset('public/assets/image/default_backdrop.jpg') }}';
                    }else{
                        var episode_image = 'https://image.tmdb.org/t/p/w1280'+data['still_path'];
                    }
                    //Backdrop Image
                    $('input[name="episode_image_url"]')
                    .val(episode_image);
                    $('#episode_image')[0].src  = episode_image;

                    $('#series_episode .spin').addClass('hidden');
                }
            });
        }
        //Fetch End

        //On Form Submit Check Episode already added!
        $(":submit").click(function () {
            var seriesSeasonID = $('#series_seasons select[name="tmdb_series_seasons"]').val();
            var seriesEpisodeID = $('#series_episode select[name="tmdb_series_episode"]').val();
            var seriesID = $('#series_id select[name="series_list"]').val();
            var seriesSeasonID1 = $('#series_seasons input[name="series_seasons"]').val();
            var seriesEpisodeID1 = $('#series_episode input[name="series_episode"]').val();
            if(!seriesSeasonID){
                $('input[name="episode_unique_id"]').val(seriesID+seriesSeasonID1+seriesEpisodeID1);
            }else{
                $('input[name="episode_unique_id"]').val(seriesID+seriesSeasonID+seriesEpisodeID);
            }
        });


    });

    //Image Upload
	$(document).ready(function(){
	  	$('.field.image button').click(function() {
			$(this).siblings('input[type="file"]')
			.click()
		});

	  	$('form.movie input[type="file"]').on('change', function() {
			var file    = $(this)[0].files[0];
			var reader  = new FileReader();
			var This 	= $(this);

			if(/^image\/(jpeg|jpg|ico|png|svg)$/.test(file.type)){
				reader.addEventListener("load", function(){
					This.siblings('img').attr('src', reader.result);
				}, false);
				if(file)
					reader.readAsDataURL(file);
			}else{
				$(this).val('');
			}

			$('input[name="episode_image_url"]').val('');
		});
	});
</script>
@endsection
