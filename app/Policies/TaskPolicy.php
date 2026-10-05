<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Self-scoped: is $user the assignee of $task. Used by Employee\TaskController
     * for "My Tasks" — deliberately narrow (no team fallback), since that controller
     * is also hit by managers/team-leaders viewing their OWN tasks and must not grant
     * them access to teammates' tasks through this route.
     */
    public function isAssignee(User $user, Task $task): bool
    {
        return $task->assigned_to === $user->id;
    }

    /**
     * Team-scoped: is $task assigned to someone on $user's team. Used by
     * Manager\TaskController for all of its task-mutation/view actions.
     */
    public function manage(User $user, Task $task): bool
    {
        return in_array($task->assigned_to, $user->teamMemberIds(), true);
    }

    /**
     * Validates a *new* assigned_to value at create/update time, not an existing
     * task's current assignee.
     */
    public function assignTo(User $user, int $employeeId): bool
    {
        return in_array($employeeId, $user->teamMemberIds(), true);
    }
}
