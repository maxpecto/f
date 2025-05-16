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
    <span>Search Engines Settings</span>
</div>
<section class="w-full text-white rounded-b">
    {!! Form::model($seosettings, ['route' => ['admin.update_searchengine_settings',$seosettings->id],'method'=>'put']) !!}
    <div class="bg-gray-700 justify-between items=center border-1 border-t border-gray-700 p-5 w-full xl:flex space-x-0 xl:space-x-6 space-y-6 xl:space-y-0">
        <div class="w-full xl:w-6/12 space-y-6">
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Google - site verification</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="site_google_verification_code" placeholder="Google - site verification.." value="{{ $seosettings->site_google_verification_code }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Bing - site verification</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="site_bing_verification_code" placeholder="Bing - site verification.." value="{{ $seosettings->site_bing_verification_code }}">
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Yandex - site verification</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="site_yandex_verification_code" placeholder="Yandex - site verification.." value="{{ $seosettings->site_yandex_verification_code }}">
                </div>
            </div>
        </div>
        <div class="w-full xl:w-6/12 space-y-6">
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Google analytics</h4></label>
                <div class="w-full">
                    <textarea class="w-full rounded-t px-4 py-4 bg-gray-600 text-white leading-5" rows="4" cols="50" name="site_google_analytics" placeholder="Google Analytics Code..">{{ $seosettings->site_google_analytics }}</textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Robots</h4></label>
                <div class="w-full">
                    <input class="w-full rounded-t px-4 py-2 bg-gray-600 text-white" type="text" name="site_robots" placeholder="Robots.." value="{{ $seosettings->site_robots }}">
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
