<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

abstract class TaskNotification extends Notification
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
            'type' => $this->type(),
            'task_id' => $this->task->id,
            'message' => $this->message($notifiable),
        ];
    }

    abstract protected function type(): string;

    abstract protected function message(object $notifiable): string;
}
