<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Concerns\HandlesTaskStatusUpdates;
use App\Http\Controllers\Concerns\ManagesTaskComments;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskCompleted;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    use HandlesTaskStatusUpdates, ManagesTaskComments;

    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $baseQuery = Task::where('assigned_to', $userId)->with('comments.user.profile');

        $counts = [
            'all' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'overdue' => (clone $baseQuery)->overdue()->count(),
        ];

        $tasks = $baseQuery
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->input('search').'%'))
            ->when($request->input('status') === 'overdue', fn ($q) => $q->overdue())
            ->when($request->filled('status') && $request->input('status') !== 'overdue', fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->input('priority')))
            ->orderBy('due_date')
            ->paginate(15)
            ->withQueryString();

        return view('employee.tasks.index', [
            'tasks' => $tasks,
            'counts' => $counts,
            'colleagues' => $this->colleagues($request),
        ]);
    }

    public function show(Request $request, Task $task): View
    {
        abort_unless($task->assigned_to === $request->user()->id, 403);

        return view('employee.tasks.show', [
            'task' => $task->load(['assignee', 'assigner', 'dependsOnUser']),
            'colleagues' => $this->colleagues($request),
        ]);
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        abort_unless($task->assigned_to === $request->user()->id, 403);

        $validated = $this->applyStatusUpdate($request, $task);

        if ($validated['status'] === 'completed') {
            $task->assigner->notify(new TaskCompleted($task));
        }

        return back()->with('status', 'Task status updated.');
    }

    public function storeComment(Request $request, Task $task): RedirectResponse
    {
        abort_unless($task->assigned_to === $request->user()->id, 403);

        $this->postComment($request, $task);

        return back()->with('status', 'Reply posted.');
    }

    private function colleagues(Request $request)
    {
        return User::where('id', '!=', $request->user()->id)
            ->whereHas('roles', fn ($q) => $q->where('name', '!=', 'admin'))
            ->orderBy('name')
            ->get();
    }
}
