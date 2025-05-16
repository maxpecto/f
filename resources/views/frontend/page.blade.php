@extends('layouts.app')

@section('head')

<style>
    h1{
        font-size: 30px;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    h2{
        font-size: 24px;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    h3{
        font-size: 20px;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    h4{
        font-size: 18px;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    h5{
        font-size: 16px;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    h6{
        font-size: 14px;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    p{
        padding-top: 10px;
        padding-bottom: 10px;
        line-height: 24px;
        color: rgb(226, 226, 226);
    }
    ol {
        padding-top: 20px;
        box-sizing: border-box;
        list-style: disc;
    }
    ul {
        padding-top: 20px;
        box-sizing: border-box;
        list-style: circle;
    }
    li{
        padding-top: 5px;
        padding-bottom: 5px;
    }
</style>

@endsection

@section('content')
@include('frontend.layouts.alpa')

<div class="container mx-auto bg-black flex">
    <section class="flex xl:flex-nowrap flex-wrap w-full xl:space-x-4">
        {{-- Main Contain --}}
        <div class="space-y-8 xl:w-9/12 w-full">
            {{-- Page--}}
            <div class="w-full lg:px-10 px-0 py-10 text-white">
                <div class="ui text menu m-0 flex justify-between items-center pb-4 text-yellow-400 border-b-2 border-yellow-500">
                    <div class="flex items-center space-x-1 text-4xl">
                        <span class="iconify" data-icon="ic:baseline-splitscreen" data-inline="false"></span> <a class="item font-medium">{{ $pages->title }}</a>
                    </div>
                </div>

                <!-- Ads Start -->
                @if($ads->activate == 1)
                    @if(isset($ads->site_728x90_banner))
                    <div class=" 2xl:flex xl:flex lg:hidden md:hidden sm:hidden hidden py-4 justify-center">
                        {!! base64_decode($ads->site_728x90_banner) !!}
                    </div>
                    @endif
                    @if(isset($ads->site_468x60_banner))
                    <div class="flex 2xl:hidden xl:hidden lg:flex md:flex sm:hidden hidden py-4 justify-center">
                        {!!  base64_decode($ads->site_468x60_banner) !!}
                    </div>
                    @endif
                    @if(isset($ads->site_320x100_banner))
                    <div class="flex 2xl:hidden xl:hidden lg:hidden md:hidden sm:flex  py-4 justify-center">
                        {!!  base64_decode($ads->site_320x100_banner) !!}
                    </div>
                    @endif
                @endif
                <!-- Ads End -->

                <div>
                {!! $pages->body !!}
                </div>

                <!-- Ads Start -->
                @if($ads->activate == 1)
                    @if(isset($ads->site_728x90_banner))
                    <div class=" 2xl:flex xl:flex lg:hidden md:hidden sm:hidden hidden py-4 justify-center">
                        {!! base64_decode($ads->site_728x90_banner) !!}
                    </div>
                    @endif
                    @if(isset($ads->site_468x60_banner))
                    <div class="flex 2xl:hidden xl:hidden lg:flex md:flex sm:hidden hidden py-4 justify-center">
                        {!!  base64_decode($ads->site_468x60_banner) !!}
                    </div>
                    @endif
                    @if(isset($ads->site_320x100_banner))
                    <div class="flex 2xl:hidden xl:hidden lg:hidden md:hidden sm:flex  py-4 justify-center">
                        {!!  base64_decode($ads->site_320x100_banner) !!}
                    </div>
                    @endif
                @endif
                <!-- Ads End -->

            </div>
        </div>
        {{-- Sidebar --}}
        <div class="xl:w-3/12 w-full">
            <div class="w-full sm:p-6 space-y-8 px-10">
                @include('frontend.layouts.front-sidebar')
            </div>
        </div>
    </section>
</div>

@endsection

@push('js')

@endpush
