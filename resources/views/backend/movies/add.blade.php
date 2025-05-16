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
    <span>Add Movie</span>
</div>
<section class="w-full text-white rounded-b">
    {!! Form::open(['route' => 'admin.movies-store','method'=>'post','enctype' => 'multipart/form-data','class' => 'form movie']) !!}
    <div class="bg-gray-700 justify-between items=center border-1 border-t border-gray-700 p-5 w-full xl:flex space-x-0 xl:space-x-6 space-y-6 xl:space-y-0">
        <div class="w-full xl:w-3/12 space-y-6">
            <div class="w-full" id="tmdb">
                <label><h4 class="w-full mb-2 font-medium">TMDB ID</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t p-2 bg-gray-600 text-white" type="text" name="movie_id" placeholder="TMDB ID" val="">
                    <input class="hidden" type="text" name="imdb_id">
                </div>
                <button type="button" class="w-full bg-green-500 p-2 rounded-b flex items-center justify-center"><div class="spin animate-spin hidden"><span class="iconify" data-icon="mdi:loading" data-inline="false"></span></div><div class="ml-2">Generate<div></button>
            </div>
            <div class="w-full field image">
                <img src="{{ asset('assets/image/default_poster.jpg') }}" alt="poster image" id="movie_poster" class="w-full">
                <input type="hidden" name="movie_poster_url" val="">
                <input type="file" accept="image/*" name="movie_poster" class="hidden">
                <button type="button" class="w-full bg-green-500 p-2 rounded-b">Select Poster</button>
            </div>
            <div class="w-full field image">
                <img src="{{ asset('assets/image/default_backdrop.jpg') }}" alt="backdrop image" id="movie_image" class="w-full">
                <input type="hidden" name="movie_image_url" value="">
                <input type="file" accept="image/*" name="movie_image" class="hidden">
                <button type="button" class="w-full bg-green-500 p-2 rounded-b">Select Backdrop</button>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Genres</h4></label>
                <div class="w-full">
                    <input id="genres" type="text" name="movie_genres" placeholder="Select Genres.." >
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Quality</h4></label>
                <div class="w-full">
                    <input id="quality" type="text" name="movie_quality" placeholder="Create/Select Quality.." >
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Country</h4></label>
                <div class="w-full">
                    <input id="countries" name="movie_countries" type="text" value="" placeholder="Select Country.." >
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Platform</h4></label>
                <div class="w-full">
                    <select name="platform_id" id="platform_id" class="w-full rounded p-2 bg-gray-600 text-white select2-basic">
                        <option value="">Platform Seçiniz...</option>
                        @foreach ($platforms as $platform)
                            <option value="{{ $platform->id }}" {{ old('platform_id') == $platform->id ? 'selected' : '' }}>{{ $platform->name }}</option>
                        @endforeach
                    </select>
                    @error('platform_id')
                        <span class="text-red-500 text-xs italic mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Duration (in min)</h4></label>
                <div class="w-full">
                    <input name="movie_duration" type="text" placeholder="Duration" class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Rating</h4></label>
                <div class="w-full">
                    <input name="movie_rating" type="text" placeholder="Rating" class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Release Date (YYYY-MM-DD)</h4></label>
                  <div class="w-full">
                    <input name="movie_release_date" type="text" placeholder="Release Date" class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
        </div>
        <div class="w-full xl:w-9/12 space-y-6">
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Name</h4></label>
                  <div class="control">
                    <input name="movie_name" id="movie_name" type="text" placeholder="Name" val="" class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Description</h4></label>
                <div class="control">
                    <textarea name="movie_description" placeholder="Description" class="w-full rounded p-4 h-20 bg-gray-600 text-white leading-6"></textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Keywords</h4></label>
                  <div class="control">
                    <input id="keywords" type="text" name="movie_keywords"  placeholder="Keywords.." >
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Actors</h4></label>
                <input id="actors" type="text" name="movie_actors"  placeholder="Actors.." >
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Directors</h4></label>
                <input id="directors" type="text" name="movie_directors"  placeholder="Directors.." >
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Writers</h4></label>
                <input id="writers" type="text" name="movie_writers" placeholder="Writers..">
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Trailer</h4></label>
                  <div class="control">
                    <input name="movie_trailer" type="text" placeholder="Trailer Url.." class="w-full rounded p-2 bg-gray-600 text-white">
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
        <a href="/admin/movies" class="bg-gray-500 rounded text-white px-4 py-2">Cancel</a>
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
        var playerfieldHTML = '<div class="flex space-x-2"><select name="player_type[]" placeholder="Player Type.." class="w-1/5 rounded p-2 bg-gray-600 text-white"><option value="youtube">Youtube</option><option value="direct">Direct(Mp4, Mkv etc..)</option><option value="hls">HLS (.m3u8)</option><option value="embeded">Embeded</option></select><input type="text" name="player_name[]" value="" placeholder="Player Name.." class="w-1/5 rounded p-2 bg-gray-600 text-white"/><input type="text" name="player_url[]" value="" placeholder="Player Url.." class="w-full rounded p-2 bg-gray-600 text-white"/><a href="javascript:void(0);" class="remove_player bg-red-900 text-white rounded flex items-center p-2"><span class="iconify" data-icon="line-md:remove" data-inline="false"></span></a></div>';
        //New input field html

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

    //TMDB
    $(document).ready(function(){
        // Search by movieID
	   	$('#tmdb button').click(function(){
            $('.spin').removeClass('hidden');

            var movieID = $('#tmdb input[name="movie_id"]').val();
		  	if(movieID > 0){
                checkIfExist(movieID);
	  		}else{
	  			alert('Movie id should not be null or invalid!');
                $('.spin').addClass('hidden');
	  		}
            //remove button loding class

		});

        //check If Tmdb id Exist Function
	   	function checkIfExist(id){
            var movieID = $('#tmdb input[name="movie_id"]').val();
	   		$.ajax({
		        url: 'check_tmdb/'+id,
		        type: 'get',
		        dataType: 'json',
		        success: function(data) {
		        	if(data == 0){
                        fetchRecords(movieID);
                        $('.spin').addClass('hidden');
                    }else{
                        alert('Movie Added Already!');
                        $('.spin').addClass('hidden');
                    }
		        }
		    });
	   	}

        //genres
		var $genres = $('#genres').selectize({
		    delimiter: ',',
		    persist: true,
		    create: false
		});
		var genre = $genres[0].selectize;
		$.getJSON('../data/get_genres', function(genresData) {
			$.each(genresData, function(val, key) {
                genre.addOption({ value: key.id, text: key.name });
			});
	    });

        //keywords
		var $keywords = $('#keywords').selectize({
		    delimiter: ',',
		    persist: true,
		    create: true
		});
		var keyword = $keywords[0].selectize;
		$.getJSON('../data/get_keywords', function(keywordsData) {
			$.each(keywordsData, function(val, key) {
                keyword.addOption({ value: key.name, text: key.name });
			});
	    });

        //quality
		var $qualitys = $('#quality').selectize({
		    delimiter: ',',
		    persist: true,
		    create: true
		});
		var quality = $qualitys[0].selectize;
		$.getJSON('../data/get_quality', function(qualityData) {
			$.each(qualityData, function(val, key) {
				quality.addOption({ value: key.name, text: key.name });
			});
	    });

        //actors
		var $actors = $('#actors').selectize({
		    delimiter: ',',
		    persist: true,
		    create: false
		});
		var actor = $actors[0].selectize;
		$.getJSON('../data/get_actors', function(actorsData) {
			$.each(actorsData, function(val, key) {
				actor.addOption({ value: key.tmdb_id, text: key.name });
			});
	    });

        //directors
		var $directors = $('#directors').selectize({
		    delimiter: ',',
		    persist: true,
		    create: false
		});
		var director = $directors[0].selectize;
		$.getJSON('../data/get_directing', function(directorsData) {
			$.each(directorsData, function(val, key) {
				director.addOption({ value: key.tmdb_id, text: key.name });
			});
	    });

        //writers
		var $writers = $('#writers').selectize({
		    delimiter: ',',
		    persist: true,
		    create: false
		});
		var writer = $writers[0].selectize;
		$.getJSON('../data/get_writing', function(writersData) {
			$.each(writersData, function(val, key) {
				writer.addOption({ value: key.tmdb_id, text: key.name });
			});
	    });

        //countries
		var $countries = $('#countries').selectize({
		    delimiter: ',',
		    persist: true,
		    create: false
		});
		var countrie = $countries[0].selectize;
		$.getJSON('../data/get_countries', function(countriesData) {
			$.each(countriesData, function(val, key) {
				countrie.addOption({ value: key.name, text: key.name });
			});
	    });

        //Fetch Function
	   	function fetchRecords(id){
	   		$.ajax({
		        url: 'get_movie_data/'+id,
		        type: 'get',
		        dataType: 'json',
		        success: function(data) {
		        	if(data['title'] === undefined){
						//not valid movie id!
						alert(data['status_message']);
					}
					else{
					   	//Add to field
					   	$('input[name="movie_id"]').val(id);
                        //IMDB
					   	$('input[name="imdb_id"]').val(data['imdb_id']);
					   	//Tagline
					   	$('input[name="movie_tagline"]').val(data['tagline']);
					   	//title
					   	$('input[name="movie_name"]').val(data['title']);
					   	//desc
					   	$('textarea[name="movie_description"]')
						.val(data['overview']);
						//movie rating
						$('input[name="movie_rating"]')
						.val(data['vote_average']);
						//movie release date
						$('input[name="movie_release_date"]')
						.val(data['release_date']);
						//movie duration
						$('input[name="movie_duration"]')
						.val(data['runtime']);
						//Poster Image
						if (data['poster_path'] == null) {
						    var movie_poster = '{{ URL::asset('assets/image/default_poster.jpg') }}';
						}else{
							var movie_poster = 'https://image.tmdb.org/t/p/w500'+data['poster_path'];
						}
						//Backdrop Image
						if (data['backdrop_path'] == null) {
						    var movie_image = '{{ URL::asset('assets/image/default_backdrop.jpg') }}';
						}else{
							var movie_image = 'https://image.tmdb.org/t/p/w1280'+data['backdrop_path'];
						}
						//Poster Image
						$('input[name="movie_poster_url"]')
						.val(movie_poster);
						//Backdrop Image
						$('input[name="movie_image_url"]')
						.val(movie_image);
						//Poster Image //Backdrop Image
						$('#movie_image')[0].src  = movie_image;
						$('#movie_poster')[0].src = movie_poster;

						//movie actors
						actor.clear();
						$.each(data.cast, function(val, key) {
                            actor.addOption({ value: key.id, text: key.name });
                            actor.addItem(key.id);
                            if(val==19) return false;
						});

                        //movie directors
						director.clear();
						$.each(data.crew, function(val, key) {
							if (key.department == 'Directing') {
				        		director.addOption({ value: key.id, text: key.name });
					        	director.addItem(key.id);
                                if(val==19) return false;
				        	}
						});
						//movie creators
						writer.clear();
						$.each(data.crew, function(val, key) {
							if (key.department == 'Writing') {
				        		writer.addOption({ value: key.id, text: key.name });
					        	writer.addItem(key.id);
                                if(val==19) return false;
				        	}
						});

						//movie keywords
						keyword.clear();
						$.each(data.keywords, function(val, key) {
				        	keyword.addOption({ value: key.name, text: key.name });
				        	keyword.addItem(key.name);
						});

						//Genres
						$.each(data.genres, function(val, key) {
				        	genre.addItem(key.id);
						});
						//Countries
						$.each(data.production_countries, function(val, key) {
				        	countrie.addItem(key.name);
						});

						//Trailer
						var videoid;
						$.each(data.results, function(val, key) {
							if(key.site =='YouTube'){
								//video.push(key.key);
								videoid = key.key;
							}
						});

						if(videoid == undefined){
							$('input[name="movie_trailer"]')
							.val('');
						}else{
							$('input[name="movie_trailer"]')
							.val('https://www.youtube.com/embed/'+videoid);
						}

					}
		        },
		        error: function (request, status, error) {
	             	alert(status + ", " + error);
	            }
		    });
	   	}
    });

    //Image Upload
	$(function () {
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

			$('input[name="movie_poster_url"]').val('');
			$('input[name="movie_image_url"]').val('');
		});
	});
</script>
@endsection
