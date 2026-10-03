<x-app-layout>
    <x-slot name="header">
        <div>
            <x-breadcrumb :items="['Dashboard' => route('admin.dashboard'), 'Employees' => '']" />
            <h2 class="font-semibold text-xl text-navy-700 leading-tight">Employees</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">Manage your team members</p>
                <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700">
                    <x-icon name="plus-circle" class="h-4 w-4" /> Add Employee
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <x-stat-card label="Total Employees" :value="$totalCount" icon="users" color="sky" />
                <x-stat-card label="Active Employees" :value="$activeCount" icon="check-circle" color="success" />
                <x-stat-card label="Inactive" :value="$inactiveCount" icon="x-circle" color="danger" />
            </div>

            <form method="GET" class="flex flex-wrap gap-3 items-end bg-white rounded-xl shadow-sm p-4">
                <div class="flex-1 min-w-[180px]">
                    <x-input-label for="search" value="Search" />
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <x-icon name="search" class="h-4 w-4" />
                        </span>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search employees..."
                            class="block w-full pl-9 border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
                    </div>
                </div>
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1 border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
                        <option value="">All</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
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
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700">
                    Filter
                </button>
                @if (request()->anyFilled(['search', 'status', 'department']))
                    <a href="{{ route('admin.employees.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
                @endif
            </form>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-sky-600">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Id</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Department</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Designation</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Role</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wide">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($employees as $index => $employee)
                            <tr>
                                <td class="px-6 py-3 text-sm text-gray-500">{{ $employees->firstItem() + $index }}</td>
                                <td class="px-6 py-3 text-sm text-gray-900">
                                    <div class="flex items-center gap-3">
                                        <x-avatar :name="$employee->name" :photo="$employee->profile?->photo_path" size="8" />
                                        <div>
                                            <a href="{{ route('admin.employees.show', $employee) }}" class="hover:text-sky-700">{{ $employee->name }}</a>
                                            <p class="text-xs text-gray-400">{{ $employee->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600">{{ $employee->profile?->department ?: '—' }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600">{{ $employee->profile?->designation ?: '—' }}</td>
                                <td class="px-6 py-3 text-sm">
                                    @php
                                        $currentRole = $employee->roles->first()->name ?? 'employee';
                                        $roleColors = [
                                            'employee' => ['bg-navy-100 text-navy-800 border-navy-400', '#d9e1ee', '#182540'],
                                            'hr' => ['bg-purple-100 text-purple-800 border-purple-400', '#f3e8ff', '#6b21a8'],
                                        ];
                                        [$roleSelectClass, $roleOptBg, $roleOptColor] = $roleColors[$currentRole] ?? ['bg-teal-100 text-teal-800 border-teal-400', '#ccfbf1', '#115e59'];
                                    @endphp
                                    <form method="POST" action="{{ route('admin.employees.update-role', $employee) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" onchange="this.form.submit()" class="text-xs font-medium rounded-full pl-2.5 pr-6 py-1 border cursor-pointer focus:outline-none focus:ring-2 focus:ring-sky-500 {{ $roleSelectClass }}">
                                            @foreach ($roles as $r)
                                                @php
                                                    [, $optBg, $optColor] = $roleColors[$r->name] ?? ['', '#ccfbf1', '#115e59'];
                                                @endphp
                                                <option value="{{ $r->name }}" style="background-color:{{ $optBg }};color:{{ $optColor }};" @selected($currentRole === $r->name)>{{ $r->name === 'hr' ? 'HR' : \Illuminate\Support\Str::headline($r->name) }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-3 text-sm">
                                    <form method="POST" action="{{ route('admin.employees.update-status', $employee) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="is_active" onchange="this.form.submit()" class="text-xs font-medium rounded-full pl-2.5 pr-6 py-1 border cursor-pointer focus:outline-none focus:ring-2 focus:ring-sky-500 {{ $employee->is_active ? 'bg-success-50 text-success-700 border-success-500' : 'bg-danger-50 text-danger-700 border-danger-500' }}">
                                            <option value="1" style="background-color:#eafbf1;color:#116238;" @selected($employee->is_active)>Active</option>
                                            <option value="0" style="background-color:#fdecef;color:#a82443;" @selected(! $employee->is_active)>Inactive</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-3 text-right text-sm space-x-1.5 whitespace-nowrap">
                                    <a href="{{ route('admin.employees.show', $employee) }}" title="View">
                                        <x-icon-button icon="eye" color="gray" />
                                    </a>
                                    <a href="{{ route('admin.employees.edit', $employee) }}" title="Edit">
                                        <x-icon-button icon="pencil" color="sky" />
                                    </a>
                                    @if (in_array($currentRole, ['manager', 'team leader']))
                                        <a href="{{ route('admin.employees.team', $employee) }}" title="View Team">
                                            <x-icon-button icon="users" color="sky" />
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" class="inline" onsubmit="return confirm('Remove this employee?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Remove">
                                            <x-icon-button icon="trash" color="danger" />
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-6 text-center text-sm text-gray-500">No employees found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }} entries</p>
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
