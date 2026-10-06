<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

trait ValidatesTask
{
    /**
     * $task is null when creating (due_date can't be in the past) and set when
     * editing (an already-overdue task's due_date isn't retroactively invalid).
     */
    private function validateTask(Request $request, ?Task $task = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'assigned_to' => ['required', 'exists:users,id'],
            'due_date' => array_filter(['required', 'date', $task ? null : 'after_or_equal:today']),
            'priority' => ['required', Rule::in(Task::PRIORITIES)],
            'remarks' => ['nullable', 'string'],
            'is_recurring' => ['required', 'boolean'],
        ]);
    }
}
