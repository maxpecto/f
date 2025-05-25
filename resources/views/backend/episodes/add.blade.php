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

<div class="w-full p-5 text-white bg-gray-900 rounded-t flex justify-between">
    <span>Add Episodes</span>
</div>
<section class="w-full text-white rounded-b">
    {!! Form::open(['route' => 'admin.episodes-store','method'=>'post','enctype' => 'multipart/form-data','class' => 'form movie']) !!}
    <div class="bg-gray-700 justify-between items=center border-1 border-t border-gray-700 p-5 w-full xl:flex space-x-0 xl:space-x-6 space-y-6 xl:space-y-0">
        <div class="w-full xl:w-3/12 space-y-6">
            <div class="w-full" id="series_id">
                <label><h4 class="w-full mb-2 font-medium">Select Series</h4></label>
                <div class="w-full select">
                    <select id="series_list" name="series_list" type="text" value="" placeholder="Select Series" ></select>
                </div>
                <button type="button" id="generate_seasons_btn" class="w-full bg-green-500 p-2 rounded-b flex items-center justify-center"><div class="spin animate-spin hidden"><span class="iconify" data-icon="mdi:loading" data-inline="false"></span></div><div class="ml-2">LOAD SEASONS<div></button>
            </div>

            <div class="w-full" id="series_seasons_container">
                <label><h4 class="w-full mb-2 font-medium">Select Season or Add New</h4></label>
                <div class="w-full select mb-2">
                    <select id="season_id_select" name="season_id" type="text" placeholder="Select Season"></select>
                </div>
                <input class="input w-full px-2 py-1 rounded mb-2" type="number" name="new_season_number" placeholder="Or Enter New Season Number" value="">
                <button type="button" class="w-full bg-green-500 p-2 rounded-b flex items-center justify-center hidden"><div class="spin animate-spin hidden"><span class="iconify" data-icon="mdi:loading" data-inline="false"></span></div><div class="ml-2">GENERATE EPISODES<div></button>
            </div>

            <div class="w-full" id="series_episode_container">
                <label><h4 class="w-full mb-2 font-medium">Enter Episode Number</h4></label>
                <div class="w-full addown">
                    <input class="input w-full px-2 py-1 rounded" type="number" name="series_episode" placeholder="Enter Episode Number" value="">
                </div>
                <button type="button" class="w-full bg-green-500 p-2 rounded-b flex items-center justify-center hidden"><div class="spin animate-spin hidden"><span class="iconify" data-icon="mdi:loading" data-inline="false"></span></div>GENERATE EPISODES</button>
            </div>

            <div class="w-full field image">
                <img src="{{ asset('assets/image/default_backdrop.jpg') }}" alt="backdrop image" id="episode_image" class="w-full">
                <input type="hidden" name="episode_image_url" value="">
                <input type="file" accept="image/*" name="episode_image" class="hidden">
                <button type="button" class="w-full bg-green-500 p-2 rounded-b">Select Backdrop</button>
            </div>

            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Air Date (YYYY-MM-DD)</h4></label>
                  <div class="w-full">
                    <input name="episode_airdate" type="text" placeholder="Air Date" class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
        </div>
        <div class="w-full xl:w-9/12 space-y-6">
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Episode Name</h4></label>
                  <div class="control">
                    <input name="episode_name" id="episode_name" type="text" placeholder="Episode Name" val="" class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Description</h4></label>
                <div class="control">
                    <textarea name="episode_description" placeholder="Description" class="w-full rounded p-4 h-20 bg-gray-600 text-white leading-6"></textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Player</h4></label>
                <div class="field_player_wrapper space-y-2">
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
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Download</h4></label>
                <div class="field_download_wrapper space-y-2">
                    <div class="flex space-x-2">
                        <input type="text" name="download_name[]" value="" placeholder="Download Name.." class="w-1/5 rounded p-2 bg-gray-600 text-white"/>
                        <input type="text" name="download_url[]" value="" placeholder="Download Links" class="w-full rounded p-2 bg-gray-600 text-white"/>
                        <a href="javascript:void(0);" class="add_download bg-gray-900 text-white rounded flex items-center p-2" title="Add Download">
                            <span class="iconify" data-icon="fluent:add-12-filled" data-inline="false"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-gray-900 justify-between items=center border-1 border-t border-gray-700 p-5 w-full flex items-center">
        <button type="submit" class="bg-green-500 rounded text-white px-4 py-2">Publish</button>
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

			$('input[name="movie_image_url"]').val('');
		});
	});

    $(document).ready(function(){
        //Series list
        var $series = $('#series_list').selectize({
            create      : true,
            sortField   : 'text',
        });
        var seriesList = $series[0].selectize;
        $.getJSON('../data/get_series', function(data) {
            $.each(data, function(val, key) {
                    seriesList.addOption({ value: key.id, text: key.title + ' [ TMDB ID : '+ key.tmdb_id +' ]' });
            });
        });

        //Series Season list
        var $season_select = $('#season_id_select').selectize({
            create: false, // Mevcut sezonları listeleyeceğimiz için false kalabilir
            placeholder: 'Select Season'
        });
        var seasonSelectList = $season_select[0].selectize;

        //Series Episode list (Bu kısım TMDB'den bölüm çekiyordu, şimdilik kaldırabilir veya yorum satırı yapabiliriz.)
        /* var $episode = $('.select #series_episode').selectize({
            create: false,
        });
        var episodeList = $episode[0].selectize; */

        // Load Seasons button click event
        $('#generate_seasons_btn').click(function(){
            var seriesID = seriesList.getValue(); // Get selected series ID
            if(seriesID && seriesID > 0){
                // Clear previous season options and new season input
                seasonSelectList.clear();
                seasonSelectList.clearOptions();
                $('input[name="new_season_number"]').val('');
                fetchLocalSeasons(seriesID);
            }else{
                alert('Please select a series first!');
            }
        });

        function fetchLocalSeasons(series_id){
            seasonSelectList.load(function(callback) {
                $.ajax({
                    url: '../episodes/get_series_local_seasons/' + series_id, // Yeni endpoint'imiz
                    type: 'GET',
                    success: function(res) {
                        if (res && res.length) {
                            seasonSelectList.clearOptions(); // Önceki seçenekleri temizle
                            res.forEach(function(season) {
                                seasonSelectList.addOption({value: season.season_id, text: 'Season ' + season.season_id});
                            });
                            seasonSelectList.refreshOptions(false); // Selectize'ı yenile
                            callback(res); // Gerekirse callback'i çağır
                        } else {
                            seasonSelectList.clearOptions();
                            seasonSelectList.addOption({value: '', text: 'No seasons found for this series. Add new below.'});
                            seasonSelectList.refreshOptions(false);
                            callback([]);
                        }
                    },
                    error: function() {
                        alert('Error loading seasons.');
                        callback([]);
                    }
                });
            });
        }

        // TMDB'den bölüm getirme fonksiyonu (fetchEpisode) yorum satırı yapıldı veya kaldırıldı.
        // Kullanıcı bölüm numarasını manuel girecek.

    });


</script>
@endsection
