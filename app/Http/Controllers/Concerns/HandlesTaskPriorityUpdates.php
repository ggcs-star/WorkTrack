<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

trait HandlesTaskPriorityUpdates
{
    private function applyPriorityUpdate(Request $request, Task $task): array
    {
        $validated = $request->validate([
            'priority' => ['required', Rule::in(Task::PRIORITIES)],
        ]);

        $task->update(['priority' => $validated['priority']]);

        return $validated;
    }
}
