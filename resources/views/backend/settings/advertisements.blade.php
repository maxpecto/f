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
    <span>Advertisements Settings</span>
</div>
<section class="w-full text-white rounded-b">
    {!! Form::model($advertisements, ['route' => ['admin.update_advertisements_settings',$advertisements->id],'method'=>'put']) !!}
    <div class="bg-gray-700 justify-between items=center border-1 border-t border-gray-700 p-5 w-full xl:flex space-x-0 xl:space-x-6 space-y-6 xl:space-y-0">
        <div class="w-full xl:w-6/12 space-y-6">
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">728x90 Ads Unit</h4></label>
                <div class="w-full">
                    <textarea class="w-full rounded-t px-4 py-4 bg-gray-600 text-white leading-5" rows="3" cols="50" name="site_728x90_banner" placeholder="728x90 Ads Unit..">{{ base64_decode($advertisements->site_728x90_banner) }}</textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">468x60 Ads Unit</h4></label>
                <div class="w-full">
                    <textarea class="w-full rounded-t px-4 py-4 bg-gray-600 text-white leading-5" rows="3" cols="50" name="site_468x60_banner" placeholder="468x60 Ads Unit..">{{ base64_decode($advertisements->site_468x60_banner) }}</textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">300x250 Ads Unit</h4></label>
                <div class="w-full">
                    <textarea class="w-full rounded-t px-4 py-4 bg-gray-600 text-white leading-5" rows="3" cols="50" name="site_300x250_banner" placeholder="300x250 Ads Unit..">{{ base64_decode($advertisements->site_300x250_banner) }}</textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">320x100 Ads Unit</h4></label>
                <div class="w-full">
                    <textarea class="w-full rounded-t px-4 py-4 bg-gray-600 text-white leading-5" rows="3" cols="50" name="site_320x100_banner" placeholder="320x100 Ads Unit..">{{ base64_decode($advertisements->site_320x100_banner) }}</textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">VAST URL</h4></label>
                <div class="w-full">
                    <textarea class="w-full rounded-t px-4 py-4 bg-gray-600 text-white leading-5" rows="3" cols="50" name="site_vast_url" placeholder="VAST URL..">{{ base64_decode($advertisements->site_vast_url) }}</textarea>
                </div>
            </div>
        </div>
        <div class="w-full xl:w-6/12 space-y-6">

            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Popunder</h4></label>
                <div class="w-full">
                    <textarea class="w-full rounded-t px-4 py-4 bg-gray-600 text-white leading-5" rows="3" cols="50" name="site_popunder" placeholder="Popunder..">{{ base64_decode($advertisements->site_popunder) }}</textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Sticky Banner</h4></label>
                <div class="w-full">
                    <textarea class="w-full rounded-t px-4 py-4 bg-gray-600 text-white leading-5" rows="3" cols="50" name="site_sticky_banner" placeholder="Sticky Banner..">{{ base64_decode($advertisements->site_sticky_banner) }}</textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Push Notifications</h4></label>
                <div class="w-full">
                    <textarea class="w-full rounded-t px-4 py-4 bg-gray-600 text-white leading-5" rows="3" cols="50" name="site_push_notifications" placeholder="Push Notifications..">{{ base64_decode($advertisements->site_push_notifications) }}</textarea>
                </div>
            </div>
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Desktop Fullpage Interstitial</h4></label>
                <div class="w-full">
                    <textarea class="w-full rounded-t px-4 py-4 bg-gray-600 text-white leading-5" rows="3" cols="50" name="site_desktop_fullpage_interstitial" placeholder="Desktop Fullpage Interstitial..">{{ base64_decode($advertisements->site_desktop_fullpage_interstitial) }}</textarea>
                </div>
            </div>

            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Activate Advertisement</h4></label>
                <label class="flex items-center w-full rounded px-4 py-4 bg-gray-600 text-white leading-5 space-x-2 mb-2"><input type="checkbox" name="activate" @if($advertisements->activate == 1) Checked @endif> <h4 class="w-full font-medium">Activate Advertisement</h4></label>
                <p>If it's checked, then only all advertisement will be shown on the site!</p>
            </div>

            {{-- Left Sidebar Ad --}}
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Sol Sidebar Reklamı (Örn: 160x600)</h4></label>
                <div class="control">
                    <textarea name="site_left_sidebar" placeholder="HTML/JS Reklam Kodu" class="w-full rounded p-4 h-32 bg-gray-600 text-white leading-6">{!! isset($advertisements->site_left_sidebar) ? base64_decode($advertisements->site_left_sidebar) : '' !!}</textarea>
                </div>
            </div>

            {{-- Right Sidebar Ad --}}
            <div class="w-full">
                <label><h4 class="w-full mb-2 font-medium">Sağ Sidebar Reklamı (Örn: 160x600)</h4></label>
                <div class="control">
                    <textarea name="site_right_sidebar" placeholder="HTML/JS Reklam Kodu" class="w-full rounded p-4 h-32 bg-gray-600 text-white leading-6">{!! isset($advertisements->site_right_sidebar) ? base64_decode($advertisements->site_right_sidebar) : '' !!}</textarea>
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
