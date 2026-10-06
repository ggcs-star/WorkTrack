<?php

namespace App\Observers;

use App\Models\Task;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskDeadlineApproaching;

class TaskObserver
{
    /**
     * The daily `tasks:check-deadlines` job only catches a task whose due date is
     * exactly 7 or exactly 1 day out *at the moment it runs*. A task created after
     * that day's run — e.g. assigned at 2pm with a due date of tomorrow — would
     * silently miss that checkpoint forever, since the job never looks backwards.
     * Covering the same two checkpoints here, at creation time, closes that gap.
     */
    public function created(Task $task): void
    {
        $period = match (today()->diffInDays($task->due_date)) {
            1 => 'day',
            7 => 'week',
            default => null,
        };

        if ($period === null) {
            return;
        }

        $task->assignee?->notify(new TaskDeadlineApproaching($task, $period));
    }

    /**
     * When a recurring task is marked completed, spawn next month's occurrence
     * so the series continues automatically.
     */
    public function updated(Task $task): void
    {
        if (! $task->is_recurring || ! $task->wasChanged('status') || $task->status !== 'completed') {
            return;
        }

        $next = $task->spawnNextOccurrenceIfNeeded();

        $next?->assignee?->notify(new TaskAssigned($next));
    }
}
