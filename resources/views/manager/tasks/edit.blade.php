<x-app-layout>
    <x-slot name="header">
        <div>
            <x-breadcrumb :items="['Dashboard' => route('employee.dashboard'), 'Team Tasks' => route('employee.assign-tasks.index'), 'Edit Task' => '']" />
            <h2 class="font-semibold text-xl text-navy-700 leading-tight">Edit Task</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
            x-data="{
                selectedEmployeeId: '{{ old('assigned_to', $task->assigned_to) }}',
                employees: {{ Illuminate\Support\Js::from(\App\Support\EmployeePickerData::forJs($employees)) }},
                get selected() { return this.employees.find(e => e.id === this.selectedEmployeeId) || null }
            }">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                <form method="POST" action="{{ route('employee.assign-tasks.update', $task) }}" class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    @include('tasks._form', ['task' => $task])

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('employee.assign-tasks.index') }}">
                            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <x-icon name="check-circle" class="h-4 w-4" /> {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>

                <div class="lg:col-span-1 space-y-6">
                    @include('tasks._employee-info-sidebar')

                    @include('tasks._quick-actions', ['routePrefix' => 'employee.assign-tasks'])
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
