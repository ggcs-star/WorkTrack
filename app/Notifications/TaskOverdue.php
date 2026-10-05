<?php

namespace App\Notifications;

class TaskOverdue extends TaskNotification
{
    protected function type(): string
    {
        return 'overdue';
    }

    protected function message(object $notifiable): string
    {
        return 'Task "'.$this->task->title.'" is overdue. Please update its status.';
    }
}
