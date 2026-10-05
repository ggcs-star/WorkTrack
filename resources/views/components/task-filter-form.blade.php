@props(['indexRoute', 'employees' => null])

<form method="GET" class="flex flex-wrap gap-3 items-end bg-white rounded-xl shadow-sm p-4">
    <div class="flex-1 min-w-[180px]">
        <x-input-label for="search" value="Search" />
        <div class="relative mt-1">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <x-icon name="search" class="h-4 w-4" />
            </span>
            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search tasks..."
                class="block w-full pl-9 border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
        </div>
    </div>
    <div>
        <x-input-label for="status" value="Status" />
        <select id="status" name="status" class="mt-1 border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
            <option value="">All</option>
            @foreach (\App\Models\Task::STATUSES as $statusOption)
                <option value="{{ $statusOption }}" @selected(request('status') === $statusOption)>{{ \App\Models\Task::STATUS_STYLES[$statusOption]['label'] }}</option>
            @endforeach
            <option value="overdue" @selected(request('status') === 'overdue')>Overdue</option>
        </select>
    </div>
    <div>
        <x-input-label for="priority" value="Priority" />
        <select id="priority" name="priority" class="mt-1 border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
            <option value="">All</option>
            @foreach (\App\Models\Task::PRIORITIES as $priorityOption)
                <option value="{{ $priorityOption }}" @selected(request('priority') === $priorityOption)>{{ ucfirst($priorityOption) }}</option>
            @endforeach
        </select>
    </div>
    @if ($employees)
        <div>
            <x-input-label for="assigned_to" value="Employee" />
            <select id="assigned_to" name="assigned_to" class="mt-1 border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm">
                <option value="">All</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @selected((string) request('assigned_to') === (string) $employee->id)>{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <x-secondary-button type="submit">Filter</x-secondary-button>
    @if (request()->anyFilled(['status', 'assigned_to', 'priority', 'search']))
        <a href="{{ route($indexRoute) }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
    @endif
</form>
