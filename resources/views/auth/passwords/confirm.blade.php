@extends('auth.layouts.app')

@section('content')
<main class="sm:container sm:mx-auto sm:max-w-lg sm:mt-10">
    <div class="flex">
        <div class="w-full">
            <section class="flex flex-col break-words bg-white sm:border-1 sm:rounded-md sm:shadow-sm sm:shadow-lg">

                <header class="font-semibold bg-gray-800 text-white py-5 px-6 sm:py-6 sm:px-8 sm:rounded-t-md">
                    {{ __('Confirm Password') }}
                </header>

                <form class="w-full px-6 space-y-6 sm:px-10 sm:space-y-8" method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <p class="leading-normal text-gray-500">
                        {{ __('Please confirm your password before continuing.') }}
                    </p>

                    <div class="flex flex-wrap">
                        <input id="password" type="password"
                            class="form-input w-full border border-green-200 @error('password') border-red-500 @enderror" name="password"
                            required autocomplete="new-password" placeholder="{{ __("Password") }}..">

                        @error('password')
                        <p class="text-green-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="flex flex-wrap justify-center items-center space-y-6 pb-6 sm:pb-10 sm:space-y-0 sm:justify-between">
                        <button type="submit"
                        class="w-full select-none font-bold whitespace-no-wrap p-3 rounded-lg text-base leading-normal no-underline text-white bg-green-500 hover:bg-green-700 sm:w-auto sm:px-4 sm:order-1 duration-150">
                            {{ __('Confirm Password') }}
                        </button>

                        @if (Route::has('password.request'))
                        <a class="mt-4 text-xs text-green-500 hover:text-green-700 whitespace-no-wrap no-underline hover:underline sm:text-sm sm:order-0 sm:m-0 duration-150"
                            href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                        @endif
                    </div>
                </form>

            </section>
        </div>
    </div>
</main>
@endsection
