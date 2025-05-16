@extends('auth.layouts.app')

@section('content')

<main class="container mx-auto mt-10">
    <div class="flex">
        <div class="flex w-full sm:px-6 align-center justify-center text-center">
            <section class="flex flex-wrap top-1/5 align-center justify-center">
                <div class="w-full text-center justify-center mb-5">
                    <header class="text-gray-700 sm:text-4xl text-xl font-bold mb-5 md:text-5xl">
                        {{ __("Welcome Back To") }} {{ config('app.name') }} {{ __("community") }}
                    </header>
                    <div class="sm:container sm:mx-auto sm:max-w-lg sm:mt-10 bg-white border-2 border-gray-200 shadow-lg px-5 py-10 rounded-lg">
                        <div class="text-gray-700 sm:text-5xl text-2xl font-bold mb-5 uppercase">
                            {{ __("Login") }}
                        </div>
                        @if (session('message'))
                            <div class="alert alert-danger">{{ session('message') }}</div>
                        @endif
                        <form class="w-full px-6 space-y-6 sm:px-10 sm:space-y-8" method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="flex flex-wrap">
                                <input id="email" type="email"
                                    class="form-input w-full border border-yellow-200 @error('email') border-red-500 @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __("Email") }}..">
                                @error('email')
                                <p class="text-red-500 text-xs italic mt-4">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div class="flex flex-wrap">
                                <input id="password" type="password"
                                    class="form-input w-full border border-yellow-200 @error('password') border-red-500 @enderror" name="password"
                                    required placeholder="{{ __("Password") }}..">

                                @error('password')
                                <p class="text-red-500 text-xs italic mt-4">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div class="flex items-center">
                                <label class="inline-flex items-center text-sm text-gray-700" for="remember">
                                    <input type="checkbox" name="remember" id="remember" class="form-checkbox text-yellow-500 border-2 border-yellow-500"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <span class="ml-2">{{ __('Remember Me') }}</span>
                                </label>

                                @if (Route::has('password.request'))
                                <a class="text-sm text-gray-700 hover:text-yellow-400 whitespace-no-wrap no-underline hover:underline ml-auto duration-150"
                                    href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                            </div>

                            <div class="flex flex-wrap">
                                <button type="submit"
                                class="w-full select-none font-bold whitespace-no-wrap p-3  text-base leading-normal no-underline text-white bg-gray-900 hover:bg-yellow-500 sm:py-4 duration-150">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('register'))
                                <p class="w-full text-xs text-center text-gray-700 mt-6 sm:text-sm sm:mt-8">
                                    {{ __("Don't have an account?") }}
                                    <a class="text-gray-900 hover:text-yellow-500 no-underline hover:underline duration-150" href="{{ route('register') }}">
                                        {{ __('Register') }}
                                    </a>
                                </p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection
