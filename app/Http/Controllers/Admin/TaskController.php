<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $tasks = Task::with(['assignee', 'assigner'])
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->input('search').'%'))
            ->when($request->input('status') === 'overdue', fn ($q) => $q->overdue())
            ->when($request->filled('status') && $request->input('status') !== 'overdue', fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->input('priority')))
            ->when($request->filled('assigned_to'), fn ($q) => $q->where('assigned_to', $request->input('assigned_to')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.tasks.index', [
            'tasks' => $tasks,
            'employees' => $this->assignableEmployees(),
        ]);
    }

    public function create(): View
    {
        return view('admin.tasks.create', ['employees' => $this->assignableEmployees()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTask($request);
        $validated['assigned_by'] = $request->user()->id;
        $validated['status'] = 'pending';

        $task = Task::create($validated);

        $task->assignee->notify(new TaskAssigned($task));

        return redirect()->route('admin.tasks.index')->with('status', 'Task assigned.');
    }

    public function show(Task $task): View
    {
        return view('admin.tasks.show', ['task' => $task->load(['assignee', 'assigner'])]);
    }

    public function edit(Task $task): View
    {
        return view('admin.tasks.edit', ['task' => $task, 'employees' => $this->assignableEmployees()]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $this->validateTask($request);

        $task->update($validated);

        return redirect()->route('admin.tasks.index')->with('status', 'Task updated.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')->with('status', 'Task deleted.');
    }

    public function markCompleted(Task $task): RedirectResponse
    {
        $task->update(['status' => 'completed', 'completed_at' => now()]);

        return redirect()->route('admin.tasks.show', $task)->with('status', 'Task marked as completed.');
    }

    private function validateTask(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'assigned_to' => ['required', 'exists:users,id'],
            'due_date' => ['required', 'date'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'remarks' => ['nullable', 'string'],
        ]);
    }

    private function assignableEmployees()
    {
        return User::whereHas('roles', fn ($q) => $q->whereIn('name', ['employee', 'hr']))->get();
    }
}
