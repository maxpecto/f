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
    <span>Edit User</span>
</div>
<section class="w-full text-white rounded-b">
    {!! Form::model($users, ['route' => ['members-update',$users->id],'method'=>'put','enctype' => 'multipart/form-data','class' => 'form profile']) !!}
    <div class="bg-gray-700 justify-between items=center border-1 border-t border-gray-700 p-5 w-full xl:flex space-x-0 xl:space-x-6 space-y-6 xl:space-y-0">
        <div class="w-full xl:w-3/12 space-y-6">
            <div class="w-full field image">
                <img src="{{ asset('assets/users')}}{{ $users->profile_img }}" alt="profile image" id="profile_image" class="w-full">
                <input type="hidden" name="profile_image_url" value="{{ asset('assets/users')}}{{ $users->profile_img }}">
                <input type="file" accept="image/*" name="profile_image" class="hidden">
                <button type="button" class="w-full bg-green-500 p-2 rounded-b">Select Profile</button>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Role</h4></label>
                <div class="control">
                    <select class="w-full rounded px-4 py-2 bg-gray-600 text-white" type="text" name="user_role"  placeholder="Role.." >
                        <option value="administrators" @if($users->role == 'administrators') selected @endif>Administrators</option>
                        <option value="members" @if($users->role == 'members') selected @endif>Members</option>
                    </select>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Location</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="location" placeholder="Location.." value="{{ $users->location }}">
                </div>
            </div>
        </div>
        <div class="w-full xl:w-9/12 space-y-6">
            <div  class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Username</h4></label>
                <div class="w-full flex items-center">
                    <span class="p-3 bg-gray-800 rounded-l border-2 border-gray-800">@ </span><input class="w-full rounded-r px-4 py-2 bg-gray-600 text-white" type="text" name="username" placeholder="Username.." value="{{ $users->username }}">
                </div>
            </div>
            <div class="w-full space-x-5 flex">
                <div class="w-6/12">
                    <label><h4 class="w-full mb-2 font-medium">First Name</h4></label>
                    <div class="w-full">
                        <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="fname" placeholder="First Name.." value="{{ $users->fname }}">
                    </div>
                </div>
                <div class="w-6/12">
                    <label><h4 class="w-full mb-2 font-medium">Last Name</h4></label>
                    <div class="w-full">
                        <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="lname" placeholder="Last Name.." value="{{ $users->lname }}">
                    </div>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Description</h4></label>
                <div class="w-full">
                    <textarea class="w-full rounded-t px-4 py-4 bg-gray-600 text-white" rows="4" name="description" placeholder="Description..">{{ $users->description }}</textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Email</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="email" name="email" placeholder="Email.." value="{{ $users->email }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Password</h4></label>
                <div class="w-full">
                    {{ Form::password('password',[ 'class'=>'w-full rounded-t px-4 py-2 bg-gray-600 text-white', 'placeholder'=>'Password..'])}}
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Website</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="website" placeholder="Website.." value="{{ $users->website }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Instagram</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="instagram" placeholder="Instagram url.." value="{{ $users->instagram }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Twitter</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="twitter" placeholder="Twitter.." value="{{ $users->twitter }}">
                </div>
            </div>
        </div>
    </div>
    <div class="bg-gray-900 justify-between items=center border-1 border-t border-gray-700 p-5 w-full flex items-center">
        <button type="submit" class="bg-green-500 rounded text-white px-4 py-2">Update User</button>
        <a href="/admin/users" class="bg-gray-500 rounded text-white px-4 py-2">Cancel</a>
    </div>
    {!! Form::close() !!}
</section>
@endsection

@section('js')
<script type="text/javascript">
    //Image Upload
	$(function () {
	  	$('.field.image button').click(function() {
			$(this).siblings('input[type="file"]')
			.click()
		});

	  	$('form.profile input[type="file"]').on('change', function() {
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

			$('input[name="profile_image_url"]').val('');
		});
	});
</script>
@endsection
