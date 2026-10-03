@php
$employeesJson = $employees->map(fn ($e) => [
    'id' => (string) $e->id,
    'name' => $e->name,
    'email' => $e->email,
    'designation' => $e->profile?->designation,
    'department' => $e->profile?->department,
    'mobile' => $e->profile?->mobile_number,
    'photo' => $e->profile?->photo_path ? asset('storage/'.$e->profile->photo_path) : null,
])->values();
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <x-breadcrumb :items="['Dashboard' => route('employee.dashboard'), 'Team Tasks' => route('employee.assign-tasks.index'), 'Assign Task' => '']" />
            <h2 class="font-semibold text-xl text-navy-700 leading-tight">Assign Task</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if ($employees->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-8 text-center space-y-3">
                <p class="text-sm text-gray-500">You don't have any team members yet. Add employees to your team before assigning tasks.</p>
                <a href="{{ route('employee.team.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700">
                    <x-icon name="users" class="h-4 w-4" /> Manage My Team
                </a>
            </div>
        @else
        <div
            x-data="{
                selectedEmployeeId: '{{ old('assigned_to', '') }}',
                employees: {{ Illuminate\Support\Js::from($employeesJson) }},
                get selected() { return this.employees.find(e => e.id === this.selectedEmployeeId) || null }
            }">
            <form method="POST" action="{{ route('employee.assign-tasks.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                @csrf

                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 space-y-4">
                    @include('admin.tasks._form', ['task' => null])

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('employee.assign-tasks.index') }}">
                            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <x-icon name="check-circle" class="h-4 w-4" /> {{ __('Assign Task') }}
                        </button>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    @include('admin.tasks._employee-info-sidebar')
                </div>
            </form>
        </div>
        @endif
        </div>
    </div>
</x-app-layout>
