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
    <span>Edit Series</span>
</div>
<section class="w-full text-white rounded-b">
    {!! Form::model($series, ['route' => ['admin.series-update',$series->id],'method'=>'put','enctype' => 'multipart/form-data','class' => 'form series']) !!}
    <div class="bg-gray-700 justify-between items=center border-1 border-t border-gray-700 p-5 w-full xl:flex space-x-0 xl:space-x-6 space-y-6 xl:space-y-0">
        <div class="w-full xl:w-3/12 space-y-6">
            <div class="w-full" id="tmdb">
                <label><h4 class="w-full mb-2 font-medium">TMDB ID</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t p-2 bg-gray-600 text-white" type="text" name="series_id" placeholder="TMDB ID" value="{{ $series->tmdb_id }}">
                </div>
                <button type="button" class="w-full bg-green-500 p-2 rounded-b flex items-center justify-center opacity-50" disabled><div class="spin animate-spin hidden"><span class="iconify" data-icon="mdi:loading" data-inline="false"></span></div><div class="ml-2">Generate<div></button>
            </div>
            <div class="w-full field image">
                <img src="{{  asset('/assets/series/poster/'.$series->poster) }}" alt="poster image" id="series_poster" class="w-full">
                <input type="hidden" name="series_poster_url" value="{{  asset('/assets/series/poster/'.$series->poster) }}">
                <input type="file" accept="image/*" name="series_poster" class="hidden">
                <button type="button" class="w-full bg-green-500 p-2 rounded-b">Select Poster</button>
            </div>
            <div class="w-full field image">
                <img src="{{  asset('/assets/series/backdrop/'.$series->backdrop) }}" alt="backdrop image" id="series_image" class="w-full">
                <input type="hidden" name="series_image_url" value="{{  asset('/assets/series/backdrop/'.$series->backdrop) }}">
                <input type="file" accept="image/*" name="series_image" class="hidden">
                <button type="button" class="w-full bg-green-500 p-2 rounded-b">Select Backdrop</button>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Genres</h4></label>
                <div class="w-full">
                    <input id="genres" type="text" name="series_genres" placeholder="Select Genres..">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Quality</h4></label>
                <div class="w-full">
                    <input id="quality" type="text" name="series_quality" placeholder="Create/Select Quality..">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Country</h4></label>
                <div class="w-full">
                    <input id="countries" name="series_countries" type="text" value="" placeholder="Select Country.." >
                </div>
            </div>
            {{-- Platform Seçimi Başlangıç --}}
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Platform</h4></label>
                <div class="w-full">
                    <select name="platform_id" id="platform_id" class="w-full rounded p-2 bg-gray-600 text-white select2-basic">
                        <option value="">Platform Seçiniz...</option>
                        @foreach ($platforms as $platform)
                            <option value="{{ $platform->id }}" {{ (old('platform_id', $series->platform_id) == $platform->id) ? 'selected' : '' }}>
                                {{ $platform->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('platform_id')
                        <span class="text-red-500 text-xs italic mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            {{-- Platform Seçimi Son --}}
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Duration (in min)</h4></label>
                <div class="w-full">
                    <input name="series_duration" type="text" placeholder="Duration" class="w-full rounded p-2 bg-gray-600 text-white" value="{{ $series->duration }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Rating</h4></label>
                <div class="w-full">
                    <input name="series_rating" type="text" placeholder="Rating" class="w-full rounded p-2 bg-gray-600 text-white" value="{{ $series->rating }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Release Date (YYYY-MM-DD)</h4></label>
                  <div class="w-full">
                    <input name="series_release_date" type="text" placeholder="Release Date" class="w-full rounded p-2 bg-gray-600 text-white" value="{{ $series->release_date }}">
                </div>
            </div>
        </div>
        <div class="w-full xl:w-9/12 space-y-6">
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Name</h4></label>
                  <div class="control">
                    <input name="series_name" id="series_name" type="text" placeholder="Name" value="{{ $series->title }}" class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Description</h4></label>
                <div class="control">
                    <textarea name="series_description" placeholder="Description" class="w-full rounded p-4 h-20 bg-gray-600 text-white leading-6">{{ $series->overviews }}</textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Keywords</h4></label>
                  <div class="control">
                    <input id="keywords" type="text" name="series_keywords"  placeholder="Keywords.." >
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Actors</h4></label>
                <input id="actors" type="text" name="series_actors"  placeholder="Actors.." >
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Directors</h4></label>
                <input id="directors" type="text" name="series_directors"  placeholder="Directors.." >
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Writers</h4></label>
                <input id="writers" type="text" name="series_writers" placeholder="Writers..">
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Trailer</h4></label>
                  <div class="control">
                    <input name="series_trailer" type="text" placeholder="Trailer Url.." class="w-full rounded p-2 bg-gray-600 text-white" value="{{ $series->trailer }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">ZIP Download</h4></label>
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
        <button type="submit" class="bg-green-500 rounded text-white px-4 py-2">Update</button>
        <a href="/admin/series" class="bg-gray-500 rounded text-white px-4 py-2">Cancel</a>
    </div>
    {!! Form::close() !!}
</section>
@endsection

@section('js')

<script>
	var takengenres = {!! $series->genres->pluck('id') !!};
    var takenkeyword = {!! $series->keywords->pluck('name') !!};
	var takenquality = {!! $series->qualities->pluck('name') !!};
	var takenactor = {!! $series->actors->pluck('tmdb_id') !!};
	var takendirector = {!! $series->directors->pluck('tmdb_id') !!};
	var takenwriter = {!! $series->writers->pluck('tmdb_id') !!};
	var takencountry = {!! $series->countries->pluck('name') !!};
</script>

<script type="text/javascript">
    //Player and Download
    $(document).ready(function(){
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

    //TMDB
    $(document).ready(function(){

        //genres
		var $genres = $('#genres').selectize({
		    delimiter: ',',
		    persist: true,
		    create: false
		});
		var genre = $genres[0].selectize;
		$.getJSON('/admin/data/get_genres', function(genresData) {
			$.each(genresData, function(val, key) {
                genre.addOption({ value: key.id, text: key.name });
			});
            $.each(takengenres, function(val, key) {
				genre.addItem(takengenres[val]);
			});
	    });

        //keywords
		var $keywords = $('#keywords').selectize({
		    delimiter: ',',
		    persist: true,
		    create: true
		});
		var keyword = $keywords[0].selectize;
		$.getJSON('/admin/data/get_keywords', function(keywordsData) {
			$.each(keywordsData, function(val, key) {
                keyword.addOption({ value: key.name, text: key.name });
			});
            $.each(takenkeyword, function(val, key) {
				keyword.addItem(takenkeyword[val]);
			});
	    });

        //quality
		var $qualitys = $('#quality').selectize({
		    delimiter: ',',
		    persist: true,
		    create: true
		});
		var quality = $qualitys[0].selectize;
		$.getJSON('/admin/data/get_quality', function(qualityData) {
			$.each(qualityData, function(val, key) {
				quality.addOption({ value: key.name, text: key.name });
			});
            $.each(takenquality, function(val, key) {
				quality.addItem(takenquality[val]);
			});
	    });

        //actors
		var $actors = $('#actors').selectize({
		    delimiter: ',',
		    persist: true,
		    create: false
		});
		var actor = $actors[0].selectize;
		$.getJSON('/admin/data/get_actors', function(actorsData) {
			$.each(actorsData, function(val, key) {
				actor.addOption({ value: key.tmdb_id, text: key.name });
			});
            $.each(takenactor, function(val, key) {
				actor.addItem(takenactor[val]);
			});
	    });

        //directors
		var $directors = $('#directors').selectize({
		    delimiter: ',',
		    persist: true,
		    create: false
		});
		var director = $directors[0].selectize;
		$.getJSON('/admin/data/get_directing', function(directorsData) {
			$.each(directorsData, function(val, key) {
				director.addOption({ value: key.tmdb_id, text: key.name });
			});
            $.each(takendirector, function(val, key) {
				director.addItem(takendirector[val]);
			});
	    });

        //writers
		var $writers = $('#writers').selectize({
		    delimiter: ',',
		    persist: true,
		    create: false
		});
		var writer = $writers[0].selectize;
		$.getJSON('/admin/data/get_writing', function(writersData) {
			$.each(writersData, function(val, key) {
				writer.addOption({ value: key.tmdb_id, text: key.name });
			});
            $.each(takenwriter, function(val, key) {
				writer.addItem(takenwriter[val]);
			});
	    });

        //countries
		var $countries = $('#countries').selectize({
		    delimiter: ',',
		    persist: true,
		    create: false
		});
		var countrie = $countries[0].selectize;
		$.getJSON('/admin/data/get_countries', function(countriesData) {
			$.each(countriesData, function(val, key) {
				countrie.addOption({ value: key.name, text: key.name });
			});
            $.each(takencountry, function(val, key) {
				countrie.addItem(takencountry[val]);
			});
	    });
    });

    //Image Upload
	$(function () {
	  	$('.field.image button').click(function() {
			$(this).siblings('input[type="file"]')
			.click()
		});

	  	$('form.series input[type="file"]').on('change', function() {
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

			$('input[name="series_poster_url"]').val('');
			$('input[name="series_image_url"]').val('');
		});
	});
</script>
@endsection
