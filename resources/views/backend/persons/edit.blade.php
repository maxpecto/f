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
<!-- Message End -->

<div class="w-full p-5 text-white bg-gray-900 rounded-t flex justify-between">
    <span>Update Person</span>
</div>
<section class="w-full text-white rounded-b">
    {!! Form::model($person, ['route' => ['persons-update',$person->id],'method'=>'put','enctype' => 'multipart/form-data']) !!}
    <div class="bg-gray-700 justify-between items=center border-1 border-t border-gray-700 p-5 w-full xl:flex space-x-0 xl:space-x-6 space-y-6 xl:space-y-0">
        <div class="w-full xl:w-3/12 space-y-6">
            <div class="w-full" id="tmdb">
                <label><h4 class="w-full mb-2 font-medium">TMDB Person ID</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t p-2 bg-gray-600 text-white" type="text" name="persons_tmdb_id" placeholder="TMDB Person ID" value="{{ $person->tmdb_id }}" >
                </div>
                <button type="button" class="w-full bg-green-500 p-2 rounded-b flex items-center justify-center opacity-50" disabled><div class="spin animate-spin hidden"><span class="iconify" data-icon="mdi:loading" data-inline="false"></span></div><div class="ml-2">Generate<div></button>
            </div>
            <div class="w-full field image">
                <img src="{{  asset('/assets/persons/'.$person->profile_path) }}" alt="poster image" id="persons_poster" class="w-full">
                <input type="hidden" name="persons_poster_url" value="{{  asset('/assets/persons/'.$person->profile_path) }}">
                <input type="file" accept="image/*" name="persons_poster" class="hidden">
                <button type="button" class="w-full bg-green-500 p-2 rounded-b">Select Poster</button>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Known For Department</h4></label>
                <select id="persons_known_for_department" name="persons_known_for_department" placeholder="Select Department...">
                    <option value="">Select Department...</option>
                    <option value="Acting">Acting</option>
                    <option value="Directing">Directing</option>
                    <option value="Writing">Writing</option>
                </select>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Gender</h4></label>
                <select id="persons_gender" name="persons_gender" placeholder="Select Gender...">
                    <option value="">Select Gender...</option>
                    <option value="0">Unknown</option>
                    <option value="1">Female</option>
                    <option value="2">Male</option>
                </select>
            </div>
        </div>
        <div class="w-full xl:w-9/12 space-y-6">
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Name</h4></label>
                  <div class="control">
                    <input name="persons_name" id="persons_name" type="text" placeholder="Name" value="{{ $person->name }}" class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Biography</h4></label>
                <div class="control">
                    <textarea name="persons_biography" placeholder="Biography" class="w-full rounded p-4 h-20 bg-gray-600 text-white leading-6">{{ $person->biography }}</textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">IMDB ID</h4></label>
                  <div class="control">
                    <input id="persons_imdb_id" type="text" name="persons_imdb_id"  value="{{ $person->imdb_id }}" placeholder="IMDB ID.." class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Popularity(TMDB)</h4></label>
                  <div class="control">
                    <input id="persons_popularity" type="text" name="persons_popularity" value="{{ $person->popularity }}" placeholder="Popularity.." class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Birth of Date (YYYY-MM-DD)</h4></label>
                  <div class="w-full">
                    <input name="persons_birth_date" type="text" placeholder="Birth of Date" value="{{ $person->birthday }}" class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Death Date (YYYY-MM-DD)</h4></label>
                  <div class="w-full">
                    <input name="persons_death_date" type="text" placeholder="Death Date" value="{{ $person->deathday }}" class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Place of Birth</h4></label>
                  <div class="w-full">
                    <div class="select is-multiple is-primary is-fullwidth">
                        <input id="persons_place_of_birth" name="persons_place_of_birth" type="text" value="{{ $person->place_of_birth }}" placeholder="Place of Birth.." class="w-full rounded p-2 bg-gray-600 text-white">
                    </div>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Homepage</h4></label>
                  <div class="control">
                    <input name="persons_homepage" id="persons_homepage" type="text" placeholder="Homepage.." value="{{ $person->homepage }}" class="w-full rounded p-2 bg-gray-600 text-white">
                </div>
            </div>
        </div>
    </div>
    <div class="bg-gray-900 justify-between items=center border-1 border-t border-gray-700 p-5 w-full flex items-center">
        <button type="submit" class="bg-green-500 rounded text-white px-4 py-2">UPDATE PERSON</button>
        <a href="/admin/persons" class="bg-gray-500 rounded text-white px-4 py-2">Cancel</a>
    </div>
    {!! Form::close() !!}
</section>
@endsection

@section('js')
<script>
	var takengender = {!! $person->gender !!};
	var takendepartment = {!! $person->known_for_department !!};
</script>
<script type="text/javascript">
    //Person TMDB
    $(document).ready(function(){
        //Departments
        var $departments = $('#persons_known_for_department').selectize({
            create: false,
            sortField: {
                field: 'text',
                direction: 'asc'
            },
        });
        var department = $departments[0].selectize;
        department.addItem('{!! $person->known_for_department !!}');

        //Gender
        var $genders = $('#persons_gender').selectize({
            create: false,
            sortField: {
                field: 'text',
                direction: 'asc'
            },
        });
        var gender = $genders[0].selectize;
        gender.addItem('{!! $person->gender !!}');


        // Search by personID
	   	$('#tmdb button').click(function(){
            $('.spin').removeClass('hidden');
            var personID = $('#tmdb input[name="persons_tmdb_id"]').val();
		  	if(personID > 0){
                checkIfExist(personID);
	  		}else{
	  			alert('Movie id should not be null or invalid!');
	  		}
		});
        //check If Tmdb id Exist Function
	   	function checkIfExist(id){
            var personID = $('#tmdb input[name="persons_tmdb_id"]').val();
	   		$.ajax({
		        url: 'check_person/'+id,
		        type: 'get',
		        dataType: 'json',
		        success: function(data) {
		        	if(data == 0){
                        fetchRecords(personID);
                    }else{
                        alert('Movie Added Already!');
                        $('.spin').addClass('hidden');
                    }
		        }
		    });
	   	}
        //Fetch Function
	   	function fetchRecords(id){
	   		$.ajax({
		        url: 'get_person_data/'+id,
		        type: 'get',
		        dataType: 'json',
		        success: function(data) {
		        	if(data['name'] === undefined){
						//not valid movie id!
						alert(data['status_message']);
					}
					else{
					   	//Add to field
					   	$('input[name="persons_tmdb_id"]').val(id);
					   	//Name
					   	$('input[name="persons_name"]').val(data['name']);
					   	//Biography
					   	$('textarea[name="persons_biography"]')
						.val(data['biography']);

                        //Poster Image
						if (data['profile_path'] == null) {
						    var profile_poster = '{{ URL::asset('assets/image/default_person.jpg') }}';
						}else{
							var profile_poster = 'https://image.tmdb.org/t/p/w500'+data['profile_path'];
						}
                        //Poster Image
						$('input[name="persons_poster_url"]')
						.val(profile_poster);
                        // //Poster Image
						$('#persons_poster')[0].src = profile_poster;
						//IMDB ID
						$('input[name="persons_imdb_id"]')
						.val(data['imdb_id']);
						//Popularity(TMDB)
						$('input[name="persons_popularity"]')
						.val(data['popularity']);
						//Birth of Date
						$('input[name="persons_birth_date"]')
						.val(data['birthday']);
                        //Deathday
						$('input[name="persons_death_date"]')
						.val(data['deathday']);
                        //Place of Birth
                        $('input[name="persons_place_of_birth"]')
						.val(data['place_of_birth']);
                        //Homepage
                        $('input[name="persons_homepage"]')
						.val(data['homepage']);

						//gender
						gender.clear();
                        gender.addItem(data.gender);

						//Department
                        department.clear();
                        department.addItem(data.known_for_department);

                        $('.spin').addClass('hidden');
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

	  	$('.field.image input[type="file"]').on('change', function() {
			var file    = $(this)[0].files[0];
			var reader  = new FileReader();
			var This 	= $(this);

			if(/^image\/(jpeg|jpg|ico|png|svg)$/.test(file.type))
			{
				reader.addEventListener("load", function()
				{
					This.siblings('img')
					.attr('src', reader.result);
				}, false);

				if(file)
					reader.readAsDataURL(file);
			}
			else
			{
				$(this).val('');
			}

			$('input[name="persons_poster_url"]')
				.val('');
		});
	});
</script>
@endsection
