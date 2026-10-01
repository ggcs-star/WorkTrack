<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Settings</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-10 text-center">
                <div class="mx-auto h-14 w-14 rounded-full bg-navy-100 text-navy-700 flex items-center justify-center mb-4">
                    <x-icon name="cog" class="h-7 w-7" />
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Coming Soon</h3>
                <p class="text-sm text-gray-500 mt-1">
                    Application settings will live here. In the meantime, manage your own account on your
                    <a href="{{ route('profile.edit') }}" class="text-sky-700 hover:text-sky-900 font-medium">Profile</a> page.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
