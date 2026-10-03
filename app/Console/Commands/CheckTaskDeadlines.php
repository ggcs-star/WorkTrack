<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskDeadlineApproaching;
use App\Notifications\TaskOverdue;
use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;

class CheckTaskDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:check-deadlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify employees about tasks that are overdue or due soon';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->notifyApproaching();
        $this->notifyOverdue();
    }

    private function notifyApproaching(): void
    {
        foreach (['week' => 7, 'day' => 1] as $period => $daysBefore) {
            $tasks = Task::with('assignee')
                ->where('status', '!=', 'completed')
                ->whereDate('due_date', now()->addDays($daysBefore)->toDateString())
                ->get();

            foreach ($tasks as $task) {
                if ($this->alreadyNotified($task, TaskDeadlineApproaching::class)) {
                    continue;
                }

                $task->assignee->notify(new TaskDeadlineApproaching($task, $period));
                $this->info("Deadline reminder ({$period}) sent for task #{$task->id}");
            }
        }
    }

    private function notifyOverdue(): void
    {
        foreach (Task::with('assignee')->overdue()->get() as $task) {
            if ($this->alreadyNotified($task, TaskOverdue::class)) {
                continue;
            }

            $task->assignee->notify(new TaskOverdue($task));
            $this->info("Overdue notice sent for task #{$task->id}");
        }
    }

    private function alreadyNotified(Task $task, string $notificationClass): bool
    {
        return DatabaseNotification::where('type', $notificationClass)
            ->where('data->task_id', $task->id)
            ->where('created_at', '>=', now()->subDay())
            ->exists();
    }
}
