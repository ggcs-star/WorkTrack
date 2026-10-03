<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Concerns\HandlesTaskStatusUpdates;
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
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskController extends Controller
{
    use HandlesTaskStatusUpdates, ManagesTaskComments, ValidatesTask;

    public function index(Request $request): View
    {
        $teamIds = $this->teamMemberIds($request);

        $tasks = Task::with(['assignee.profile', 'assigner', 'comments.user.profile'])
            ->whereIn('assigned_to', $teamIds)
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->input('search').'%'))
            ->when($request->input('status') === 'overdue', fn ($q) => $q->overdue())
            ->when($request->filled('status') && $request->input('status') !== 'overdue', fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->input('priority')))
            ->when($request->filled('assigned_to'), fn ($q) => $q->where('assigned_to', $request->input('assigned_to')))
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
        $this->ensureTeamMember($request, (int) $validated['assigned_to']);

        $validated['assigned_by'] = $request->user()->id;
        $validated['status'] = 'pending';

        $task = Task::create($validated);

        $task->assignee->notify(new TaskAssigned($task));

        Notification::send(User::role('admin')->get(), new TeamTaskAssigned($task));

        return redirect()->route('employee.assign-tasks.index')->with('status', 'Task assigned.');
    }

    public function show(Request $request, Task $task): View
    {
        $this->ensureTeamMember($request, $task->assigned_to);

        return view('manager.tasks.show', [
            'task' => $task->load(['assignee.profile', 'assigner', 'dependsOnUser']),
        ]);
    }

    public function edit(Request $request, Task $task): View
    {
        $this->ensureTeamMember($request, $task->assigned_to);

        return view('manager.tasks.edit', ['task' => $task, 'employees' => $this->teamMembers($request)]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->ensureTeamMember($request, $task->assigned_to);

        $validated = $this->validateTask($request);
        $this->ensureTeamMember($request, (int) $validated['assigned_to']);

        $task->update($validated);

        return redirect()->route('employee.assign-tasks.index')->with('status', 'Task updated.');
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        $this->ensureTeamMember($request, $task->assigned_to);

        $task->delete();

        return redirect()->route('employee.assign-tasks.index')->with('status', 'Task deleted.');
    }

    public function markCompleted(Request $request, Task $task): RedirectResponse
    {
        $this->ensureTeamMember($request, $task->assigned_to);

        $task->update(['status' => 'completed', 'completed_at' => now()]);

        return redirect()->route('employee.assign-tasks.show', $task)->with('status', 'Task marked as completed.');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $this->ensureTeamMember($request, $task->assigned_to);

        $this->applyStatusUpdate($request, $task);

        return redirect()->route('employee.assign-tasks.index')->with('status', 'Task status updated.');
    }

    public function storeComment(Request $request, Task $task): RedirectResponse
    {
        $this->ensureTeamMember($request, $task->assigned_to);

        $this->postComment($request, $task);

        return back()->with('status', 'Reply posted.');
    }

    public function updatePriority(Request $request, Task $task): RedirectResponse
    {
        $this->ensureTeamMember($request, $task->assigned_to);

        $validated = $request->validate([
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
        ]);

        $task->update(['priority' => $validated['priority']]);

        return redirect()->route('employee.assign-tasks.index')->with('status', 'Task priority updated.');
    }

    private function teamMembers(Request $request)
    {
        return $request->user()->teamMembers()->with('profile')->get();
    }

    private function teamMemberIds(Request $request): array
    {
        return $request->user()->teamMembers()->pluck('users.id')->toArray();
    }

    private function ensureTeamMember(Request $request, int $employeeId): void
    {
        if (! in_array($employeeId, $this->teamMemberIds($request), true)) {
            throw new NotFoundHttpException;
        }
    }

    private function colleagues(Request $request)
    {
        return User::where('id', '!=', $request->user()->id)
            ->whereHas('roles', fn ($q) => $q->where('name', '!=', 'admin'))
            ->orderBy('name')
            ->get();
    }
}
