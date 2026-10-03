<section>
    <header class="flex items-center gap-2 pb-3 border-b border-gray-200">
        <span class="h-7 w-7 rounded-md bg-sky-100 text-sky-700 flex items-center justify-center shrink-0">
            <x-icon name="user-circle" class="h-4 w-4" />
        </span>
        <div>
            <h2 class="text-base font-semibold text-navy-900">
                {{ __('Profile Information') }}
            </h2>
            <p class="text-sm text-gray-500">
                {{ __("Update your account's name and email address.") }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <x-icon name="user-circle" class="h-5 w-5" />
                </span>
                <x-text-input id="name" name="name" type="text" class="block w-full pl-10" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <x-icon name="mail" class="h-5 w-5" />
                </span>
                <x-text-input id="email" name="email" type="email" class="block w-full pl-10" :value="old('email', $user->email)" required autocomplete="username" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="sm:col-span-2 flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <x-icon name="check-circle" class="h-4 w-4" /> {{ __('Save') }}
            </button>
        </div>
    </form>
</section>
