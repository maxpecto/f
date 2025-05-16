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
    <span>General Settings</span>
</div>
<section class="w-full text-white rounded-b">
    {!! Form::model($general, ['route' => ['admin.update_general_settings',$general->id],'method'=>'put','enctype' => 'multipart/form-data']) !!}
    <div class="bg-gray-700 justify-between items=center border-1 border-t border-gray-700 p-5 w-full xl:flex space-x-0 xl:space-x-6 space-y-6 xl:space-y-0">
        <div class="w-full xl:w-6/12 space-y-6">
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Site Name</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="site_name" placeholder="Site Name.." value="{{ $general->site_name }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Site Title</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="site_title" placeholder="Site Title.." value="{{ $general->site_title }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Site Description</h4></label>
                <div class="w-full">
                    <textarea class="w-full rounded-t px-4 py-2 bg-gray-600 text-white leading-5" rows="4" cols="50" name="site_description" placeholder="Site Description..">{{ $general->site_description }}</textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Site Keywords</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="site_keywords" placeholder="Site Keywords.." value="{{ $general->site_keywords }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Copyright Text</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="site_copyright" placeholder="Copyright Text.." value="{{ $general->site_copyright }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Site Author</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="site_author" placeholder="Site Author.." value="{{ $general->site_author }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Site Email</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="email" name="site_email" placeholder="Site Email.." value="{{ $general->site_email }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Logo</h4></label>
                <div class="w-full">
                    <input class="w-full rounded px-4 py-2 bg-gray-600 text-white" type="file" accept="image/*" name="site_logo">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Favicon</h4></label>
                <div class="w-full">
                    <input class="w-full rounded px-4 py-2 bg-gray-600 text-white" type="file" accept="image/*" name="site_favicon">
                </div>
            </div>
        </div>
        <div class="w-full xl:w-6/12 space-y-6">
            <div  class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Movies / Series / Items Per Page</h4></label>
                <div class="w-full flex items-center">
                    <input class="w-full rounded px-4 py-2 bg-gray-600 text-white" type="number" name="site_items_per_page" placeholder="Items Per Page.." value="{{ $general->site_items_per_page }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Default Player</h4></label>
                <div class="control">
                    <div class="w-full flex items-center">
                        <select name="site_player" class="w-full rounded px-4 py-2 bg-gray-600 text-white ">
                            <option @if($general->site_player == 'embeded') selected @endif value="embeded">Embeded</option>
                            <option @if($general->site_player == 'direct') selected @endif value="direct">Direct Link</option>
                            <option @if($general->site_player == 'hls') selected @endif value="hls">HLS(.m3u8)</option>
                            <option @if($general->site_player == 'trailer') selected @endif value="trailer">Trailer</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <label class="flex items-center w-full rounded p-4 bg-gray-600 text-white space-x-2"><input type="checkbox" name="site_comments_moderation" @if($general->site_comments_moderation == 1) checked @endif><h4 class="w-full font-medium">Comments Moderation</h4></label>
            </div>
            <div class="w-full">
                <label class="flex items-center w-full rounded p-4 bg-gray-600 text-white space-x-2 mb-6"><input type="checkbox" name="maintenance" @if($general->maintenance == 1)checked @endif><h4 class="w-full font-medium">Maintenance Mode(Check this if your site in maintenance!)
                </h4></label>
                <label><h4 class="w-full mb-2 font-medium">Maintenance Description</h4></label>
                <div class="w-full">
                    <textarea class="w-full rounded-t px-4 py-4 bg-gray-600 text-white leading-5" rows="4" cols="50" name="site_maintenance_description" placeholder="Site Maintenance Description..">{{ $general->site_maintenance_description }}</textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Facebook</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="site_facebook" placeholder="Facebook.." value="{{ $general->site_facebook }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Twitter</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="site_twitter" placeholder="Twitter.." value="{{ $general->site_twitter }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Youtube</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="site_youtube" placeholder="Youtube.." value="{{ $general->site_youtube }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Pinterest</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="site_pinterest" placeholder="Pinterest.." value="{{ $general->site_pinterest }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Linkedin</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="site_linkedin" placeholder="Linkedin.." value="{{ $general->site_linkedin }}">
                </div>
            </div>
        </div>
    </div>
    <div class="bg-gray-900 justify-between items=center border-1 border-t border-gray-700 p-5 w-full flex items-center">
        <button type="submit" class="bg-green-500 rounded text-white px-4 py-2">Save Setting</button>
        <a href="/admin" class="bg-gray-500 rounded text-white px-4 py-2">Cancel</a>
    </div>
    {!! Form::close() !!}
</section>
@endsection

@section('js')
@endsection
