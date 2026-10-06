<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Concerns\HandlesTaskPriorityUpdates;
use App\Http\Controllers\Concerns\HandlesTaskStatusUpdates;
use App\Http\Controllers\Concerns\ManagesAssignableUsers;
use App\Http\Controllers\Concerns\ManagesTaskComments;
use App\Http\Controllers\Concerns\ValidatesTask;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use App\Notifications\TeamTaskAssigned;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class TaskController extends Controller
{
    use HandlesTaskPriorityUpdates, HandlesTaskStatusUpdates, ManagesAssignableUsers, ManagesTaskComments, ValidatesTask;

    public function index(Request $request): View
    {
        $tasks = Task::with(['assignee.profile', 'assigner', 'comments.user.profile'])
            ->forTeamOf($request->user())
            ->search($request->input('search'))
            ->filterStatus($request->input('status'))
            ->filterPriority($request->input('priority'))
            ->filterAssignedTo($request->input('assigned_to'))
            ->filterDueFrom($request->input('due_from'))
            ->filterDueTo($request->input('due_to'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('manager.tasks.index', [
            'tasks' => $tasks,
            'employees' => $this->teamMembers($request),
            'colleagues' => $this->colleagues($request),
        ]);
    }

    public function create(Request $request): View
    {
        return view('manager.tasks.create', ['employees' => $this->teamMembers($request)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTask($request);
        abort_unless($request->user()->can('assignTo', [Task::class, (int) $validated['assigned_to']]), 404);

        $validated['assigned_by'] = $request->user()->id;
        $validated['status'] = 'pending';

        $task = Task::create($validated);

        $task->assignee->notify(new TaskAssigned($task));

        Notification::send(User::role('admin')->get(), new TeamTaskAssigned($task));

        return redirect()->route('employee.assign-tasks.index')->with('status', 'Task assigned.');
    }

    public function show(Request $request, Task $task): View
    {
        abort_unless($request->user()->can('manage', $task), 404);

        return view('manager.tasks.show', [
            'task' => $task->load(['assignee.profile', 'assigner', 'dependsOnUser']),
        ]);
    }

    public function edit(Request $request, Task $task): View
    {
        abort_unless($request->user()->can('manage', $task), 404);

        return view('manager.tasks.edit', ['task' => $task, 'employees' => $this->teamMembers($request)]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        abort_unless($request->user()->can('manage', $task), 404);

        $validated = $this->validateTask($request, $task);
        abort_unless($request->user()->can('assignTo', [Task::class, (int) $validated['assigned_to']]), 404);

        $task->update($validated);

        return redirect()->route('employee.assign-tasks.index')->with('status', 'Task updated.');
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        abort_unless($request->user()->can('manage', $task), 404);

        $task->delete();

        return redirect()->route('employee.assign-tasks.index')->with('status', 'Task deleted.');
    }

    public function markCompleted(Request $request, Task $task): RedirectResponse
    {
        abort_unless($request->user()->can('manage', $task), 404);

        $task->update(['status' => 'completed', 'completed_at' => now()]);

        return redirect()->route('employee.assign-tasks.show', $task)->with('status', 'Task marked as completed.');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        abort_unless($request->user()->can('manage', $task), 404);

        $this->applyStatusUpdate($request, $task);

        return redirect()->route('employee.assign-tasks.index')->with('status', 'Task status updated.');
    }

    public function storeComment(Request $request, Task $task): RedirectResponse
    {
        abort_unless($request->user()->can('manage', $task), 404);

        $this->postComment($request, $task);

        return back()->with('status', 'Reply posted.');
    }

    public function updatePriority(Request $request, Task $task): RedirectResponse
    {
        abort_unless($request->user()->can('manage', $task), 404);

        $this->applyPriorityUpdate($request, $task);

        return redirect()->route('employee.assign-tasks.index')->with('status', 'Task priority updated.');
    }

    private function teamMembers(Request $request)
    {
        return $request->user()->teamMembers()->with('profile')->get();
    }
}
