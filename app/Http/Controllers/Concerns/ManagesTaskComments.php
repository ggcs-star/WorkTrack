<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Task;
use Illuminate\Http\Request;

trait ManagesTaskComments
{
    private function postComment(Request $request, Task $task): void
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $task->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);
    }
}
