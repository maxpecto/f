@extends('auth.layouts.app')

@section('content')
<main class="sm:container sm:mx-auto sm:max-w-lg sm:mt-10">
    <div class="flex">
        <div class="w-full">
            <section class="flex flex-col break-words bg-white sm:border-1 sm:rounded-md sm:shadow-sm sm:shadow-lg">

                <header class="font-semibold bg-gray-800 text-white py-5 px-6 sm:py-6 sm:px-8 sm:rounded-t-md">
                    {{ __('Reset Password') }}
                </header>

                <form class="w-full px-6 space-y-6 sm:px-10 sm:space-y-8" method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="flex flex-wrap">
                        <input id="email" type="email"
                            class="form-input w-full border border-green-200 @error('email') border-red-500 @enderror" name="email"
                            value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="{{ __("Email") }}..">

                        @error('email')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="flex flex-wrap">
                        <input id="password" type="password"
                            class="form-input w-full border border-green-200 @error('password') border-red-500 @enderror" name="password"
                            required autocomplete="new-password" placeholder="{{ __("Password") }}..">

                        @error('password')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="flex flex-wrap">
                        <input id="password-confirm" type="password" class="form-input w-full border border-green-200"
                            name="password_confirmation" required autocomplete="new-password" placeholder="{{ __("Confirm Password") }}..">
                    </div>

                    <div class="flex flex-wrap pb-8 sm:pb-10">
                        <button type="submit"
                        class="w-full select-none font-bold whitespace-no-wrap p-3 rounded-lg text-base leading-normal no-underline text-white bg-gray-900 hover:bg-yellow-500 sm:py-4 duration-150">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>

            </section>
        </div>
    </div>
</main>
@endsection
