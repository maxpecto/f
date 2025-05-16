@extends('auth.layouts.app')

@section('content')
<main class="container mx-auto mt-10">
    <div class="flex">
        <div class="w-full sm:px-6">
            <section class="flex flex-wrap">
                <div class="w-full xl:w-1/2 text-center justify-center md:p-20 mb-5">
                    <header class="text-gray-700 sm:text-5xl text-xl font-bold mb-5">
                        {{ __("Join the") }} {{ config('app.name') }} {{ __("community") }}
                    </header>
                    <p class="text-xl text-gray-700"></p>

                    <div class="">
                        <form class="w-full px-6 space-y-6 sm:px-10 sm:space-y-8" method="POST"
                            action="{{ route('register') }}">
                            @csrf
                            <div class="flex flex-wrap sap">
                                <div class="w-1/2 px-2">
                                    <input id="fname" type="text" class="form-input w-full border border-yellow-200 @error('fname') border-yellow-500 @enderror"
                                    name="fname" value="{{ old('fname') }}" required autocomplete="fname" autofocus placeholder="{{ __("First Name") }}..">
                                    @error('fname')
                                    <p class="text-yellow-500 text-xs italic mt-4">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                                <div class="w-1/2 px-2">
                                    <input id="lname" type="text" class="form-input w-full border border-yellow-200 @error('lname') border-yellow-500 @enderror"
                                    name="lname" value="{{ old('lname') }}" required autocomplete="lname" autofocus placeholder="{{ __("Last Name") }}..">
                                    @error('lname')
                                    <p class="text-yellow-500 text-xs italic mt-4">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex flex-wrap px-2">
                                <input id="username" type="text"
                                class="form-input w-full border border-yellow-200 @error('username') border-yellow-500 @enderror" name="username"
                                value="{{ old('username') }}" required autocomplete="username" placeholder="{{ __("Username") }}..">
                                @error('username')
                                <p class="text-yellow-500 text-xs italic mt-4">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div class="flex flex-wrap px-2">
                                <input id="email" type="email"
                                class="form-input w-full border border-yellow-200 @error('email') border-yellow-500 @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __("Email") }}..">
                                @error('email')
                                <p class="text-yellow-500 text-xs italic mt-4">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div class="flex flex-wrap px-2">
                                <input id="password" type="password"
                                    class="form-input w-full border border-yellow-200 @error('password') border-yellow-500 @enderror" name="password"
                                    required autocomplete="new-password" placeholder="{{ __("Password") }}..">
                                @error('password')
                                <p class="text-yellow-500 text-xs italic mt-4">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div class="flex flex-wrap px-2">
                                <input id="password-confirm" type="password" class="form-input w-full border border-yellow-200"
                                    name="password_confirmation" required autocomplete="new-password" placeholder="{{ __("Confirm Password") }}..">
                            </div>

                            <div class="flex flex-wrap px-2">
                                <button type="submit"
                                    class="w-full select-none font-bold whitespace-no-wrap p-3 rounded-lg text-base leading-normal no-underline text-white bg-gray-900 hover:bg-yellow-500 sm:py-4 duration-150">
                                    {{ __('Register') }}
                                </button>

                                <p class="w-full text-xs text-center text-gray-700 my-6 sm:text-sm sm:my-8">
                                    {{ __('Already have an account?') }}
                                    <a class="text-yellow-500 hover:text-yellow-400 no-underline hover:underline duration-150" href="{{ route('login') }}">
                                        {{ __('Login') }}
                                    </a>
                                </p>
                            </div>
                        </form>
                        <p class="text-xs text-gray-700">{{ __("By joining, you agree to our Terms of Service and Privacy Policy") }}</p>
                    </div>
                </div>
                <div class="w-full xl:w-1/2 text-center justify-center items-center xl:block hidden">
                    <div class="flex flex-wrap h-full text-center justify-center items-center">
                        <div class="flex flex-col w-3/12 m-3" >
                            @if(isset($join1))
                                @foreach($join1 as $join)
                                    @if($join->type == 'movies')
                                        <img class="w-full rounded m-3" src="/assets/movies/poster/{{ $join->poster }}">
                                    @else
                                        <img class="w-full rounded m-3" src="/assets/series/poster/{{ $join->poster }}">
                                    @endif
                                @endforeach
                            @else
                                <img class="w-full rounded m-3" src="https://source.unsplash.com/random/1000x1200?nature,">
                                <img class="w-full rounded m-3" src="https://source.unsplash.com/random/1000x1200?water">
                            @endif
                        </div>
                        <div class="flex flex-col w-3/12 -mt-6 m-3" >
                            @if(isset($join2))
                                @foreach($join2 as $join)
                                    @if($join->type == 'movies')
                                        <img class="w-full rounded m-3" src="/assets/movies/poster/{{ $join->poster }}">
                                    @else
                                        <img class="w-full rounded m-3" src="/assets/series/poster/{{ $join->poster }}">
                                    @endif
                                @endforeach
                            @else
                                <img class="w-full rounded m-3" src="https://source.unsplash.com/random/1000x1200?film">
                                <img class="w-full rounded m-3" src="https://source.unsplash.com/random/1000x1200?animals">
                                <img class="w-full rounded m-3" src="https://source.unsplash.com/random/1000x1200?nature">
                            @endif
                        </div>
                        <div class="flex flex-col w-3/12 m-3" >
                            @if(isset($join2))
                                @foreach($join3 as $join)
                                    @if($join->type == 'movies')
                                        <img class="w-full rounded m-3" src="/assets/movies/poster/{{ $join->poster }}">
                                    @else
                                        <img class="w-full rounded m-3" src="/assets/series/poster/{{ $join->poster }}">
                                    @endif
                                @endforeach
                            @else
                                <img class="w-full rounded m-3" src="https://source.unsplash.com/random/1000x1200?flower">
                                <img class="w-full rounded m-3" src="https://source.unsplash.com/random/1000x1200?wallpapers">
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection
