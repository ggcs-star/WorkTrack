<x-app-layout>
    <x-slot name="header">
        <div>
            <x-breadcrumb :items="['Dashboard' => $user->hasRole('admin') ? route('admin.dashboard') : route('employee.dashboard'), 'My Profile' => '']" />
            <h2 class="font-semibold text-xl text-navy-700 leading-tight">My Profile</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
                <x-avatar :name="$user->name" :photo="$user->profile?->photo_path" size="16" />
                <div>
                    <p class="text-lg font-semibold text-navy-900">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-full text-xs font-medium bg-navy-100 text-navy-800">
                        {{ ucfirst($user->roles->first()->name ?? '—') }}
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                @include('profile.partials.update-password-form')
            </div>

            @unless ($user->hasRole('admin'))
                <div class="bg-white rounded-xl shadow-sm p-6">
                    @include('profile.partials.update-employee-details-form')
                </div>
            @endunless

            <div class="bg-white rounded-xl shadow-sm p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
