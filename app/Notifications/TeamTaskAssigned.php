<?php

namespace App\Notifications;

class TeamTaskAssigned extends TaskNotification
{
    protected function type(): string
    {
        return 'assigned';
    }

    protected function message(object $notifiable): string
    {
        return $this->task->assigner->name.' assigned "'.$this->task->title.'" to '.$this->task->assignee->name.'.';
    }
}
