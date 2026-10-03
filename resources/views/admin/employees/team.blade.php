<x-app-layout>
    <x-slot name="header">
        <div>
            <x-breadcrumb :items="['Dashboard' => route('admin.dashboard'), 'Employees' => route('admin.employees.index'), $manager->name.'\'s Team' => '']" />
            <h2 class="font-semibold text-xl text-navy-700 leading-tight">{{ $manager->name }}'s Team</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <x-avatar :name="$manager->name" :photo="$manager->profile?->photo_path" size="10" />
                    <div>
                        <p class="font-semibold text-navy-900">{{ $manager->name }}</p>
                        <p class="text-xs text-gray-500">{{ $manager->email }} &middot; {{ \Illuminate\Support\Str::headline($manager->roles->first()->name ?? '—') }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.employees.index') }}" class="text-sm text-sky-700 hover:text-sky-900">&larr; Back to Employees</a>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                @if ($teamMembers->isEmpty())
                    <div class="px-6 py-14 text-center">
                        <p class="text-sm text-gray-500">{{ $manager->name }} hasn't added any team members yet.</p>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-sky-600">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Department</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Designation</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Role</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($teamMembers as $member)
                                <tr>
                                    <td class="px-6 py-3 text-sm text-gray-900">
                                        <div class="flex items-center gap-3">
                                            <x-avatar :name="$member->name" :photo="$member->profile?->photo_path" size="8" />
                                            <div>
                                                <p>{{ $member->name }}</p>
                                                <p class="text-xs text-gray-400">{{ $member->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-600">{{ $member->profile?->department ?: '—' }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-600">{{ $member->profile?->designation ?: '—' }}</td>
                                    <td class="px-6 py-3 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-navy-100 text-navy-800">
                                            {{ \Illuminate\Support\Str::headline($member->roles->first()->name ?? '—') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-right text-sm whitespace-nowrap">
                                        <a href="{{ route('admin.employees.show', $member) }}" title="View Profile">
                                            <x-icon-button icon="eye" color="gray" />
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
