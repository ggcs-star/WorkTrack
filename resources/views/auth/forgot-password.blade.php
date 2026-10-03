<x-guest-layout>
    <h1 class="text-4xl font-bold text-navy-900">Forgot Password?</h1>
    <p class="text-base text-gray-500 mt-2 mb-8">No problem. Enter your email and we'll send you a link to reset it.</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <x-icon name="mail" class="h-5 w-5" />
                </span>
                <x-text-input id="email" class="block w-full pl-10" type="email" name="email" :value="old('email')" required autofocus />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 bg-sky-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Send Reset Link') }}
            <x-icon name="arrow-right" class="h-4 w-4" />
        </button>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="text-sky-700 hover:text-sky-900 font-medium">&larr; Back to Login</a>
        </p>
    </form>
</x-guest-layout>
