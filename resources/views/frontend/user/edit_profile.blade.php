@extends('layouts.app')

@section('content')
<main class="container mx-auto bg-gray-900">
    <div class="flex">
        <div class="flex w-full sm:px-6 align-center justify-center">
            <section class="flex flex-wrap top-1/5 align-center justify-center">
                <div class="w-full justify-center p-10 mb-5">
                    @if ($message = Session::get('success'))
                        <div class="bg-gray-900 text-white shadow-md p-10">
                            {{ $message }}
                        </div>
                    @endif
                    <div class="sm:container sm:mx-auto sm:max-w-lg sm:mt-10 bg-gray-800 border-2 border-gray-700 shadow-md px-5 py-10 rounded divide-y-2 divide-green-100 divide-dashed space-y-5">
                        <header class="text-white text-3xl font-bold md:text-4xl text-center">
                            {{ __("Edit Your Profile") }}
                        </header>
                        {!! Form::model($users, ['route'=>['update_profile'],'method'=>'put','class'=>'w-full px-6 space-y-2 sm:px-10 sm:space-y-4','enctype' => 'multipart/form-data']) !!}
                            @csrf

                            <div class="w-full flex text-gray-700 items-center">
                                <div class="flex w-full align-center justify-center profile-header relative">
                                    <img class="w-32 h-32 rounded-full user_image cursor-pointer object-cover" src="{{ asset('/assets/users/') }}{{ $users->profile_img }}">
                                    <input id="user_image" type="file" name="user_image" class="hidden" accept="image/*">
                                    <input type="text" name="user_image" class="hidden" value="1" spellcheck="false">
                                    <input type="hidden" name="user_image_changed">
                                </div>
                            </div>

                            <div class="w-full block lg:flex text-gray-700 items-center lg:space-x-4">
                                <div class="align-center lg:w-1/2 w-full lg:mb-0 mb-2">
                                    <input id="fname" type="text" class="form-input w-full border border-gray-200 @error('fname') border-red-500 @enderror" value="{{ $users->fname }}" name="fname" required placeholder="{{ __("First Name") }}..">
                                </div>
                                <div class="align-center lg:w-1/2 w-full lg:mb-0 mb-2">
                                    <input id="lname" type="text" class="form-input w-full border border-gray-200 @error('lname') border-red-500 @enderror" value="{{ $users->lname }}" name="lname" required placeholder="{{ __("Last Name") }}..">
                                </div>
                            </div>

                            <div class="flex flex-wrap">
                                <input id="email" type="email"
                                    class="form-input w-full border border-gray-200 @error('email') border-red-500 @enderror" name="email"
                                    value="{{ $users->email }}" required autocomplete="email"  placeholder="{{ __("Email") }}..">
                            </div>

                            <div class="flex flex-wrap">
                                <textarea class="form-input w-full border border-gray-200 @error('description') border-red-500 @enderror" id="description"
                                    name="description" rows="5" placeholder="{{ __("Description") }}.." autofocus>{{ $users->description }}</textarea>
                            </div>

                            <div class="flex flex-wrap">
                                <input id="website" type="text"
                                    class="form-input w-full border border-gray-200 @error('website') border-red-500 @enderror" name="website"
                                    value="{{ $users->website }}" autocomplete="website"  placeholder="{{ __("Website") }}..">
                            </div>

                            <div class="flex flex-wrap">
                                <input id="instagram" type="text"
                                    class="form-input w-full border border-gray-200 @error('instagram') border-red-500 @enderror" name="instagram"
                                    value="{{ $users->instagram }}" autocomplete="instagram"  placeholder="{{ __("Instagram URL") }}..">
                            </div>

                            <div class="flex flex-wrap">
                                <input id="twitter" type="text"
                                    class="form-input w-full border border-gray-200 @error('twitter') border-red-500 @enderror" name="twitter"
                                    value="{{ $users->twitter }}" autocomplete="twitter"  placeholder="{{ __("Twitter URL") }}..">
                            </div>

                            <div class="flex flex-wrap">
                                <input id="location" type="text"
                                    class="form-input w-full border border-gray-200 @error('location') border-red-500 @enderror" name="location"
                                    value="{{ $users->location }}" autocomplete="location"  placeholder="{{ __("Location") }}..">
                            </div>

                            <div class="flex flex-col text-sm text-gray-600">
                                <strong class="pb-2 text-base text-white">{{ __("Change Password") }}</strong>
                                {{ Form::password('password',[ 'class'=>'w-full rounded-t px-4 py-2 bg-gray-600 text-white', 'placeholder'=>'Password..'])}}
                            </div>

                            <div class="flex text-sm text-yellow-500 items-center">
                                <strong class="pb-2 text-base text-white flex items-center">
                                    <input type="checkbox" name="hide_data" @if($users->hidden_items == 1) checked @endif><span class="pl-2">{{ __("Hide") }}</span>
                                </strong>
                                <span class="pl-2">({{ __("Hide Liked and Watchlisted items from other users") }})</span>
                            </div>


                            <!-- Modal Start -->
                            <div x-cloak x-data="{ open: false }" class="flex flex-col text-sm text-red-600">
                                <span class="cursor-pointer pb-2 text-base text-red-600" @click="open = true">{{ __("Delete your account") }}</span>
                                <div class="fixed z-50  top-0 left-0 flex items-center justify-center w-full h-full" style="background-color: rgba(0,0,0,.5);" x-show="open" >
                                    <div class="h-auto p-4 mx-2 text-left bg-gray-900 rounded shadow-xl w-9/12 md:max-w-xl md:p-6 lg:p-8 md:mx-0" @click.away="open = false">

                                        <div class="text-center text-4xl">
                                            <h3 class="text-lg font-medium leading-6 text-white uppercase border-1 border-b border-gray-600 w-max pb-4 ">
                                                {{ __("Delete your account") }}?
                                            </h3>
                                            <div class="mt-6 text-2xl text-white">
                                                {{ __("Are you sure? Want to delete you account? You can't undo this!") }}
                                            </div>
                                        </div>
                                        <div class="mt-5 sm:mt-6">
                                            <span class="flex w-full rounded-md shadow-sm space-x-4">
                                                <a class="inline-flex justify-center w-full px-4 py-2 text-white bg-red-500 rounded hover:bg-red-700 cursor-pointer focus:outline-none" href="/delete-profile/{{ $users->id }}">{{ __("Yes! Please") }}</a>
                                                <a @click="open = false" class="inline-flex justify-center w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-700 cursor-pointer focus:outline-none">
                                                    {{ __("Cancel") }}
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal end -->

                            <div class="flex flex-wrap space-x-2">
                                <button type="submit"
                                class="bg-gray-900 hover:bg-yellow-500 text-gray-100 hover:text-white px-4 py-2 rounded duration-150">
                                    {{ __("Update Profile") }}
                                </button>
                                <a href="/&#64;{{Auth::user()->username}}" class="bg-gray-900 hover:bg-yellow-500 text-white hover:text-gray-200 px-4 py-2 rounded duration-150">
                                    {{ __("Cancel") }}
                                </a>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

@push('js')
<script>
    $(function() {
        $('.profile-header img').on('click', function() {
            $('input[type="file"][name="user_image"]').click()
        });

        $('input[type="file"][name="user_image"]').on('change', function() {
            var file    = $(this)[0].files[0];
            var reader  = new FileReader();

            if(/^image\/(jpeg|jpg|ico|png|svg)$/.test(file.type))
            {
                reader.addEventListener("load", function() {
                    $('.profile-header img').attr('src', reader.result);
                }, false);

                if(file)
                {
                    reader.readAsDataURL(file);
                    $('.profile-header .image').show()
                                               .siblings('.placeholder').hide();

                    $('input[name="user_image_changed"]').val('1');
                }
            }
            else
            {
                console.log('Select Valid File!');

                $(this).val('');
            }
        });

        $('input[type="file"][name="user_image"]').on('change', function() {
            $('input[type="text"][name="user_image"]').val('');
        });

        if($('#profile input[type="text"][name="user_image"]').val().length)
        {
            $('#profile .ui.image').each(function(){
                $(this).show().siblings('.placeholder').hide();
            });
        }
    })
</script>
@endpush


@endsection
