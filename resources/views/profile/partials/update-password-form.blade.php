<section>
    <header class="flex items-center gap-2 pb-3 border-b border-gray-200">
        <span class="h-7 w-7 rounded-md bg-sky-100 text-sky-700 flex items-center justify-center shrink-0">
            <x-icon name="lock" class="h-4 w-4" />
        </span>
        <div>
            <h2 class="text-base font-semibold text-navy-900">
                {{ __('Update Password') }}
            </h2>
            <p class="text-sm text-gray-500">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
        @csrf
        @method('put')

        <div class="sm:col-span-2" x-data="{ show: false }">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <x-icon name="lock" class="h-5 w-5" />
                </span>
                <x-text-input id="update_password_current_password" name="current_password" class="block w-full pl-10 pr-10"
                    type="password" x-bind:type="show ? 'text' : 'password'" autocomplete="current-password" />
                <button type="button" @click="show = ! show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <x-icon name="eye" x-show="! show" class="h-5 w-5" />
                    <x-icon name="eye-slash" x-show="show" x-cloak class="h-5 w-5" />
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div x-data="{ show: false }">
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <x-icon name="lock" class="h-5 w-5" />
                </span>
                <x-text-input id="update_password_password" name="password" class="block w-full pl-10 pr-10"
                    type="password" x-bind:type="show ? 'text' : 'password'" autocomplete="new-password" />
                <button type="button" @click="show = ! show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <x-icon name="eye" x-show="! show" class="h-5 w-5" />
                    <x-icon name="eye-slash" x-show="show" x-cloak class="h-5 w-5" />
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div x-data="{ show: false }">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <x-icon name="lock" class="h-5 w-5" />
                </span>
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" class="block w-full pl-10 pr-10"
                    type="password" x-bind:type="show ? 'text' : 'password'" autocomplete="new-password" />
                <button type="button" @click="show = ! show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                    <x-icon name="eye" x-show="! show" class="h-5 w-5" />
                    <x-icon name="eye-slash" x-show="show" x-cloak class="h-5 w-5" />
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="sm:col-span-2 flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <x-icon name="check-circle" class="h-4 w-4" /> {{ __('Update Password') }}
            </button>
        </div>
    </form>
</section>
