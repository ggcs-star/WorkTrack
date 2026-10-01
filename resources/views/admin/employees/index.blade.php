<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Employees</h2>
                <p class="text-sm text-gray-500">Manage your team members</p>
            </div>
            <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700">
                <x-icon name="plus-circle" class="h-4 w-4" /> Add Employee
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($employees as $index => $employee)
                            <tr>
                                <td class="px-6 py-3 text-sm text-gray-500">{{ $employees->firstItem() + $index }}</td>
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $employee->name }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600">{{ $employee->email }}</td>
                                <td class="px-6 py-3 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-navy-100 text-navy-700">
                                        {{ ucfirst($employee->roles->first()->name ?? '—') }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm">
                                    <form method="POST" action="{{ route('admin.employees.toggle-status', $employee) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->is_active ? 'bg-success-50 text-success-700 border border-success-500' : 'bg-gray-100 text-gray-500 border border-gray-300' }}">
                                            {{ $employee->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-3 text-right text-sm space-x-2 whitespace-nowrap">
                                    <a href="{{ route('admin.employees.edit', $employee) }}" class="inline-flex text-sky-700 hover:text-sky-900" title="Edit">
                                        <x-icon name="pencil" class="h-4 w-4" />
                                    </a>
                                    <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" class="inline" onsubmit="return confirm('Remove this employee?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex text-danger-600 hover:text-danger-800" title="Remove">
                                            <x-icon name="trash" class="h-4 w-4" />
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-sm text-gray-500">No employees yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
