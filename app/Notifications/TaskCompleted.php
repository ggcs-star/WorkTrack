<?php

namespace App\Notifications;

class TaskCompleted extends TaskNotification
{
    protected function type(): string
    {
        return 'completed';
    }

    protected function message(object $notifiable): string
    {
        return $this->task->assignee->name.' has marked "'.$this->task->title.'" as completed.';
    }
}
