<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskRedirectController extends Controller
{
    /**
     * A single stable URL that notification emails link to, so "View Task" works
     * no matter who clicks it: the browser might be signed in as the assignee,
     * their manager, an admin — or not signed in at all. The `auth` middleware
     * handles the logged-out case (redirect to login, then back here via Laravel's
     * intended-URL mechanism); this dispatches an authenticated visitor to whichever
     * role-specific task page they're actually allowed to see.
     */
    public function show(Request $request, Task $task): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.tasks.show', $task);
        }

        if ($user->can('isAssignee', $task)) {
            return redirect()->route('employee.tasks.show', $task);
        }

        if ($user->can('manage', $task)) {
            return redirect()->route('employee.assign-tasks.show', $task);
        }

        abort(403);
    }
}
