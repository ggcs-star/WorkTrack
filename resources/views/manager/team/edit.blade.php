<x-app-layout>
    <x-slot name="header">
        <div>
            <x-breadcrumb :items="['Dashboard' => route('employee.dashboard'), 'My Team' => '']" />
            <h2 class="font-semibold text-xl text-navy-700 leading-tight">My Team</h2>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ showModal: false, search: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">These employees report to you and can be assigned tasks.</p>
                <button type="button" @click="showModal = true" class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700">
                    <x-icon name="plus-circle" class="h-4 w-4" /> Add Team
                </button>
            </div>

            <form method="GET" class="flex flex-wrap gap-3 items-end bg-white rounded-xl shadow-sm p-4">
                <div class="flex-1 min-w-[180px]">
                    <x-input-label for="search" value="Search" />
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <x-icon name="search" class="h-4 w-4" />
                        </span>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search team members..."
                            class="block w-full pl-9 border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
                    </div>
                </div>
                <div>
                    <x-input-label for="department" value="Department" />
                    <select id="department" name="department" class="mt-1 border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
                        <option value="">All</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department }}" @selected(request('department') === $department)>{{ $department }}</option>
                        @endforeach
                    </select>
                </div>
                <x-secondary-button type="submit">Filter</x-secondary-button>
                @if (request()->anyFilled(['search', 'department']))
                    <a href="{{ route('employee.team.edit') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
                @endif
            </form>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                @if ($teamMembers->isEmpty())
                    <div class="px-6 py-14 text-center">
                        @if (request()->anyFilled(['search', 'department']))
                            <p class="text-sm text-gray-500">No team members match your filters.</p>
                            <a href="{{ route('employee.team.edit') }}" class="text-sm text-sky-700 hover:text-sky-900 mt-1 inline-block">Clear filters</a>
                        @else
                            <p class="text-sm text-gray-500">You haven't added any team members yet.</p>
                            <button type="button" @click="showModal = true" class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700">
                                <x-icon name="plus-circle" class="h-4 w-4" /> Add Team
                            </button>
                        @endif
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-sky-600">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Department</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Designation</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Role</th>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            @if ($teamMembers->isNotEmpty())
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">Showing {{ $teamMembers->firstItem() ?? 0 }} to {{ $teamMembers->lastItem() ?? 0 }} of {{ $teamMembers->total() }} entries</p>
                    {{ $teamMembers->links() }}
                </div>
            @endif
        </div>

        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto px-4 py-6" @keydown.escape.window="showModal = false">
            <div class="fixed inset-0 bg-black/50 transition-opacity" @click="showModal = false"></div>

            <div class="relative bg-white rounded-xl shadow-xl max-w-2xl mx-auto overflow-hidden" @click.outside="showModal = false">
                <form method="POST" action="{{ route('employee.team.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-navy-900">Add Team Members</h3>
                        <p class="text-sm text-gray-500 mt-1">Tick who reports to you.</p>
                        <div class="relative mt-3">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <x-icon name="search" class="h-4 w-4" />
                            </span>
                            <input type="text" x-model="search" placeholder="Search employee by name..."
                                class="block w-full pl-9 border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
                        </div>
                    </div>

                    <div class="max-h-96 overflow-y-auto divide-y divide-gray-100">
                        @forelse ($candidates as $candidate)
                            <label x-show="@js(\Illuminate\Support\Str::lower($candidate->name)).includes(search.toLowerCase())"
                                class="flex items-center gap-3 px-6 py-3 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="members[]" value="{{ $candidate->id }}"
                                    @checked(in_array($candidate->id, $teamMemberIds))
                                    class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500 shrink-0">
                                <x-avatar :name="$candidate->name" :photo="$candidate->profile?->photo_path" size="8" />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">{{ $candidate->name }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ $candidate->email }} &middot; {{ $candidate->profile?->department ?: '—' }}</p>
                                </div>
                            </label>
                        @empty
                            <p class="px-6 py-10 text-center text-sm text-gray-500">No employees available.</p>
                        @endforelse
                    </div>

                    <div class="flex justify-end gap-3 p-4 bg-gray-50 border-t border-gray-100">
                        <x-secondary-button type="button" @click="showModal = false">{{ __('Cancel') }}</x-secondary-button>
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <x-icon name="check-circle" class="h-4 w-4" /> {{ __('Save Team') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
