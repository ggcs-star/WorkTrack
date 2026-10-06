<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskAssigned;
use Illuminate\Console\Command;

class SpawnOverdueRecurringTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:spawn-overdue-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Keep recurring task series moving even when a cycle goes overdue unresolved';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $overdueRecurring = Task::where('is_recurring', true)
            ->where('status', '!=', 'completed')
            ->where('due_date', '<', today())
            ->get();

        foreach ($overdueRecurring as $task) {
            $next = $task->spawnNextOccurrenceIfNeeded();

            if (! $next) {
                continue;
            }

            $next->assignee?->notify(new TaskAssigned($next));
            $this->info("Spawned next occurrence (task #{$next->id}) for overdue recurring task #{$task->id}");
        }
    }
}
