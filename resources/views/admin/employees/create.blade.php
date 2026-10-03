<x-app-layout>
    <x-slot name="header">
        <div>
            <x-breadcrumb :items="['Dashboard' => route('admin.dashboard'), 'Employees' => route('admin.employees.index'), 'Add Employee' => '']" />
            <h2 class="font-semibold text-xl text-navy-700 leading-tight">Add Employee</h2>
            <p class="text-sm text-gray-500">Create a new employee and add their details to the system.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-gradient-to-br from-sky-50 to-navy-50 rounded-xl p-5 flex items-center gap-4">
                <span class="h-11 w-11 rounded-lg bg-white shadow-sm flex items-center justify-center shrink-0">
                    <x-icon name="user-circle" class="h-6 w-6 text-sky-600" />
                </span>
                <div>
                    <h3 class="font-semibold text-navy-900">Employee Information</h3>
                    <p class="text-sm text-gray-500">Fill in the details below to add a new employee.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="POST" action="{{ route('admin.employees.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @include('admin.employees._form', ['employee' => null])

                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('admin.employees.index') }}">
                            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700 focus:bg-sky-700 active:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <x-icon name="check-circle" class="h-4 w-4" /> {{ __('Save Employee') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
