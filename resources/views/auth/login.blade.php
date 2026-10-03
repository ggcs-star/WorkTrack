<x-guest-layout>
    <h1 class="text-4xl font-bold text-navy-900">Welcome Back!</h1>
    <p class="text-base text-gray-500 mt-2 mb-8">Sign in to your account to continue</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <x-icon name="mail" class="h-5 w-5" />
                </span>
                <x-text-input id="email" class="block w-full pl-10" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <x-icon name="lock" class="h-5 w-5" />
                </span>
                <x-text-input id="password" class="block w-full pl-10 pr-10"
                                type="password"
                                x-bind:type="show ? 'text' : 'password'"
                                name="password"
                                required autocomplete="current-password" />
                <button type="button" @click="show = ! show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <x-icon name="eye" x-show="! show" class="h-5 w-5" />
                    <x-icon name="eye-slash" x-show="show" x-cloak class="h-5 w-5" />
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-sky-600 shadow-sm focus:ring-sky-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-sky-700 hover:text-sky-900 font-medium" href="{{ route('password.request') }}">
                    {{ __('Forgot Password?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-sky-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Login') }}
            <x-icon name="arrow-right" class="h-4 w-4" />
        </button>

        <div class="flex items-center gap-3">
            <div class="flex-1 border-t border-gray-200"></div>
            <span class="text-xs font-medium text-gray-400 uppercase">{{ __('Or') }}</span>
            <div class="flex-1 border-t border-gray-200"></div>
        </div>

        <p class="text-center text-sm text-gray-500">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}" class="text-sky-700 hover:text-sky-900 font-medium">{{ __('Register here') }}</a>
        </p>
    </form>
</x-guest-layout>
