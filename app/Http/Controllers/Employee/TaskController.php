<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Notifications\TaskCompleted;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $baseQuery = Task::where('assigned_to', $userId);

        $counts = [
            'all' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'overdue' => (clone $baseQuery)->overdue()->count(),
        ];

        $tasks = $baseQuery
            ->when($request->input('status') === 'overdue', fn ($q) => $q->overdue())
            ->when($request->filled('status') && $request->input('status') !== 'overdue', fn ($q) => $q->where('status', $request->input('status')))
            ->orderBy('due_date')
            ->paginate(15)
            ->withQueryString();

        return view('employee.tasks.index', ['tasks' => $tasks, 'counts' => $counts]);
    }

    public function show(Request $request, Task $task): View
    {
        abort_unless($task->assigned_to === $request->user()->id, 403);

        return view('employee.tasks.show', ['task' => $task->load(['assignee', 'assigner'])]);
    }

    public function start(Request $request, Task $task): RedirectResponse
    {
        abort_unless($task->assigned_to === $request->user()->id, 403);

        $task->update(['status' => 'in_progress']);

        return back()->with('status', 'Task marked in progress.');
    }

    public function complete(Request $request, Task $task): RedirectResponse
    {
        abort_unless($task->assigned_to === $request->user()->id, 403);

        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $task->assigner->notify(new TaskCompleted($task));

        return back()->with('status', 'Task marked complete.');
    }
}
