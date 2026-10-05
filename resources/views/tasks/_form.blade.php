<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="title" value="Task Title" />
        <x-text-input id="title" name="title" type="text" class="block mt-1 w-full" :value="old('title', $task?->title)" required autofocus />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="assigned_to" value="Assigned To" />
        <select id="assigned_to" name="assigned_to" x-model="selectedEmployeeId" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
            <option value="">Select Employee</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" @selected((int) old('assigned_to', $task?->assigned_to) === $employee->id)>{{ $employee->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
    </div>
</div>

<div>
    <x-input-label for="description" value="Description" />
    <textarea id="description" name="description" rows="3" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm">{{ old('description', $task?->description) }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="due_date" value="Due Date" />
        <x-text-input id="due_date" name="due_date" type="date" class="block mt-1 w-full" :value="old('due_date', $task?->due_date?->format('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="priority" value="Priority" />
        <select id="priority" name="priority" class="block mt-1 w-full border-gray-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm" required>
            @foreach (\App\Models\Task::PRIORITIES as $priority)
                <option value="{{ $priority }}" @selected(old('priority', $task?->priority ?? 'medium') === $priority)>{{ ucfirst($priority) }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('priority')" class="mt-2" />
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label value="Assigned By" />
        @php
            $assigner = $task?->assigner ?? auth()->user();
            $assignerRole = $assigner->roles->first()->name ?? null;
            $assignerRoleLabel = $assignerRole ? \App\Support\RoleLabel::for($assignerRole) : null;
            $assignedByText = $assignerRoleLabel ? $assignerRoleLabel.' ('.$assigner->name.')' : $assigner->name;
        @endphp
        <x-text-input type="text" class="block mt-1 w-full bg-gray-50" :value="$assignedByText" disabled />
    </div>

    <div>
        <x-input-label for="remarks" value="Remarks (Optional)" />
        <x-text-input id="remarks" name="remarks" type="text" class="block mt-1 w-full" :value="old('remarks', $task?->remarks)" />
        <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
    </div>
</div>
