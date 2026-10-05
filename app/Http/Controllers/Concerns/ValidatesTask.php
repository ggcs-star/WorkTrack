<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

trait ValidatesTask
{
    private function validateTask(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'assigned_to' => ['required', 'exists:users,id'],
            'due_date' => ['required', 'date'],
            'priority' => ['required', Rule::in(Task::PRIORITIES)],
            'remarks' => ['nullable', 'string'],
        ]);
    }
}
