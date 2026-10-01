<aside class="hidden lg:flex lg:flex-col lg:w-64 lg:shrink-0 bg-gradient-to-b from-navy-900 to-navy-800 lg:sticky lg:top-16 lg:h-[calc(100vh-4rem)]">
    <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
        @role('admin')
            <x-sidebar-link href="{{ route('admin.dashboard') }}" icon="dashboard" :active="request()->routeIs('admin.dashboard')">Dashboard</x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.employees.index') }}" icon="users" :active="request()->routeIs('admin.employees.*')">Employees</x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.tasks.create') }}" icon="plus-circle" :active="request()->routeIs('admin.tasks.create')">Create Task</x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.tasks.index') }}" icon="clipboard" :active="request()->routeIs('admin.tasks.index') && ! in_array(request('status'), ['pending', 'completed', 'overdue'])">All Tasks</x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.tasks.index', ['status' => 'pending']) }}" icon="clock" :active="request()->routeIs('admin.tasks.index') && request('status') === 'pending'">Pending Tasks</x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.tasks.index', ['status' => 'completed']) }}" icon="check-circle" :active="request()->routeIs('admin.tasks.index') && request('status') === 'completed'">Completed Tasks</x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.tasks.index', ['status' => 'overdue']) }}" icon="overdue" :active="request()->routeIs('admin.tasks.index') && request('status') === 'overdue'">Overdue Tasks</x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.reports') }}" icon="chart" :active="request()->routeIs('admin.reports')">Reports</x-sidebar-link>
            <x-sidebar-link href="{{ route('admin.settings') }}" icon="cog" :active="request()->routeIs('admin.settings')">Settings</x-sidebar-link>
        @else
            <x-sidebar-link href="{{ route('employee.dashboard') }}" icon="dashboard" :active="request()->routeIs('employee.dashboard')">Dashboard</x-sidebar-link>
            <x-sidebar-link href="{{ route('employee.tasks.index') }}" icon="list" :active="request()->routeIs('employee.tasks.*')">My Tasks</x-sidebar-link>
            <x-sidebar-link href="{{ route('notifications.index') }}" icon="bell" :active="request()->routeIs('notifications.*')">Notifications</x-sidebar-link>
            <x-sidebar-link href="{{ route('profile.edit') }}" icon="user-circle" :active="request()->routeIs('profile.edit')">Profile</x-sidebar-link>
        @endrole
    </nav>

    <div class="px-3 py-4 border-t border-navy-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-navy-200 hover:bg-navy-800 hover:text-white transition">
                <x-icon name="logout" class="h-5 w-5 shrink-0" />
                Logout
            </button>
        </form>
    </div>
</aside>
