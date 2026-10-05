<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Concerns\HandlesTaskStatusUpdates;
use App\Http\Controllers\Concerns\ManagesAssignableUsers;
use App\Http\Controllers\Concerns\ManagesTaskComments;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Notifications\TaskCompleted;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    use HandlesTaskStatusUpdates, ManagesAssignableUsers, ManagesTaskComments;

    public function index(Request $request): View
    {
        $baseQuery = Task::where('assigned_to', $request->user()->id)->with('comments.user.profile');

        $counts = [
            'all' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'completed' => (clone $baseQuery)->completed()->count(),
            'overdue' => (clone $baseQuery)->overdue()->count(),
        ];

        $tasks = $baseQuery
            ->search($request->input('search'))
            ->filterStatus($request->input('status'))
            ->filterPriority($request->input('priority'))
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
        $this->authorize('isAssignee', $task);

        return view('employee.tasks.show', [
            'task' => $task->load(['assignee', 'assigner', 'dependsOnUser']),
            'colleagues' => $this->colleagues($request),
        ]);
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('isAssignee', $task);

        $validated = $this->applyStatusUpdate($request, $task);

        if ($validated['status'] === 'completed') {
            $task->assigner->notify(new TaskCompleted($task));
        }

        return back()->with('status', 'Task status updated.');
    }

    public function storeComment(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('isAssignee', $task);

        $this->postComment($request, $task);

        return back()->with('status', 'Reply posted.');
    }
}
