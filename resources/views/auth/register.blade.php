<x-guest-layout>
    <h1 class="text-4xl font-bold text-navy-900">Create Employee Account</h1>
    <p class="text-base text-gray-500 mt-2 mb-8">Register yourself to start using WorkTrack</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <x-icon name="user-circle" class="h-5 w-5" />
                </span>
                <x-text-input id="name" class="block w-full pl-10" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <x-icon name="mail" class="h-5 w-5" />
                </span>
                <x-text-input id="email" class="block w-full pl-10" type="email" name="email" :value="old('email')" required autocomplete="username" />
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
                                x-bind:type="show ? 'text' : 'password'"
                                name="password"
                                required autocomplete="new-password" />
                <button type="button" @click="show = ! show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <x-icon name="eye" x-show="! show" class="h-5 w-5" />
                    <x-icon name="eye-slash" x-show="show" x-cloak class="h-5 w-5" />
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div x-data="{ show: false }">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <x-icon name="lock" class="h-5 w-5" />
                </span>
                <x-text-input id="password_confirmation" class="block w-full pl-10 pr-10"
                                x-bind:type="show ? 'text' : 'password'"
                                name="password_confirmation"
                                required autocomplete="new-password" />
                <button type="button" @click="show = ! show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <x-icon name="eye" x-show="! show" class="h-5 w-5" />
                    <x-icon name="eye-slash" x-show="show" x-cloak class="h-5 w-5" />
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-sky-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Register') }}
            <x-icon name="arrow-right" class="h-4 w-4" />
        </button>

        <div class="flex items-center gap-3">
            <div class="flex-1 border-t border-gray-200"></div>
            <span class="text-xs font-medium text-gray-400 uppercase">{{ __('Or') }}</span>
            <div class="flex-1 border-t border-gray-200"></div>
        </div>

        <p class="text-center text-sm text-gray-500">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}" class="text-sky-700 hover:text-sky-900 font-medium">{{ __('Sign in') }}</a>
        </p>
    </form>
</x-guest-layout>
