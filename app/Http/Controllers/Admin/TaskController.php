<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesTaskPriorityUpdates;
use App\Http\Controllers\Concerns\HandlesTaskStatusUpdates;
use App\Http\Controllers\Concerns\ManagesAssignableUsers;
use App\Http\Controllers\Concerns\ManagesTaskComments;
use App\Http\Controllers\Concerns\ValidatesTask;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Notifications\TaskAssigned;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    use HandlesTaskPriorityUpdates, HandlesTaskStatusUpdates, ManagesAssignableUsers, ManagesTaskComments, ValidatesTask;

    public function index(Request $request): View
    {
        $tasks = Task::with(['assignee.profile', 'assigner', 'comments.user.profile'])
            ->search($request->input('search'))
            ->filterStatus($request->input('status'))
            ->filterPriority($request->input('priority'))
            ->filterAssignedTo($request->input('assigned_to'))
            ->filterDueFrom($request->input('due_from'))
            ->filterDueTo($request->input('due_to'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.tasks.index', [
            'tasks' => $tasks,
            'employees' => $this->assignableEmployees(),
            'colleagues' => $this->assignableEmployees(),
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
        return view('admin.tasks.show', [
            'task' => $task->load(['assignee.profile', 'assigner', 'dependsOnUser']),
        ]);
    }

    public function edit(Task $task): View
    {
        return view('admin.tasks.edit', ['task' => $task, 'employees' => $this->assignableEmployees()]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $this->validateTask($request, $task);

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

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $this->applyStatusUpdate($request, $task);

        return redirect()->route('admin.tasks.index')->with('status', 'Task status updated.');
    }

    public function storeComment(Request $request, Task $task): RedirectResponse
    {
        $this->postComment($request, $task);

        return back()->with('status', 'Reply posted.');
    }

    public function updatePriority(Request $request, Task $task): RedirectResponse
    {
        $this->applyPriorityUpdate($request, $task);

        return redirect()->route('admin.tasks.index')->with('status', 'Task priority updated.');
    }
}
