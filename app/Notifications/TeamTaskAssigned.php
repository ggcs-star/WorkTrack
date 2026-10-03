<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TeamTaskAssigned extends Notification
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
            'type' => 'assigned',
            'task_id' => $this->task->id,
            'message' => $this->task->assigner->name.' assigned "'.$this->task->title.'" to '.$this->task->assignee->name.'.',
        ];
    }
}
