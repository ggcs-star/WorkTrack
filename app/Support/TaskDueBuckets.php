<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;

class TaskDueBuckets
{
    /**
     * Build the today/week/month "due soon" buckets from a base task query,
     * each capped to 10 rows and eager-loaded for the due-tasks-panel component.
     */
    public static function from(Builder $base): array
    {
        return [
            'today' => (clone $base)->with('assignee')->dueToday()->orderBy('due_date')->take(10)->get(),
            'week' => (clone $base)->with('assignee')->dueThisWeek()->orderBy('due_date')->take(10)->get(),
            'month' => (clone $base)->with('assignee')->dueThisMonth()->orderBy('due_date')->take(10)->get(),
        ];
    }
}
