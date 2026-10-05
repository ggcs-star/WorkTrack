<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

trait HandlesTaskStatusUpdates
{
    private function applyStatusUpdate(Request $request, Task $task): array
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Task::STATUSES)],
            'reason' => ['required_if:status,dependency,need_clarification', 'nullable', 'string', 'max:2000'],
            'depends_on_user_id' => ['required_if:status,dependency', 'nullable', 'exists:users,id'],
        ]);

        $task->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === 'completed' ? now() : null,
            'depends_on_user_id' => $validated['status'] === 'dependency' ? $validated['depends_on_user_id'] : null,
        ]);

        if (in_array($validated['status'], ['dependency', 'need_clarification'], true)) {
            $task->comments()->create([
                'user_id' => $request->user()->id,
                'body' => $validated['reason'],
            ]);
        }

        return $validated;
    }
}
