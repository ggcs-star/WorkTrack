<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskDeadlineApproaching extends Notification
{
    use Queueable;

    public function __construct(public Task $task)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'deadline',
            'task_id' => $this->task->id,
            'message' => '"'.$this->task->title.'" will be due in '.now()->diffInDays($this->task->due_date).' day(s) ('.$this->task->due_date->format('M d, Y').').',
        ];
    }
}
