<?php

namespace App\Notifications;

use App\Support\RoleLabel;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssigned extends TaskNotification
{
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $assignerRole = $this->task->assigner->roles->first()->name ?? null;
        $assignerLabel = $assignerRole ? RoleLabel::for($assignerRole) : 'Admin';

        $mail = (new MailMessage)
            ->subject('New Task Assigned: '.$this->task->title)
            ->greeting('Hi '.$notifiable->name.',')
            ->line('You have been assigned a new task by '.$this->task->assigner->name.' ('.$assignerLabel.').')
            ->line('**Task:** '.$this->task->title);

        if ($this->task->description) {
            $mail->line('**Description:** '.$this->task->description);
        }

        return $mail
            ->line('**Due Date:** '.$this->task->due_date->format('d M Y'))
            ->line('**Priority:** '.ucfirst($this->task->priority))
            ->line('Please ensure the task is reviewed and completed within the specified deadline. If you have any questions, require clarification, or are dependent on another team member, please update the task status accordingly in WorkTrack.')
            ->action('View Task', route('employee.tasks.show', $this->task))
            ->line('Please review the task details and get started at your earliest convenience.')
            ->salutation("Thank you,\nWorkTrack Team");
    }

    protected function type(): string
    {
        return 'assigned';
    }

    protected function message(object $notifiable): string
    {
        return 'You have been assigned a new task: '.$this->task->title.'.';
    }
}
