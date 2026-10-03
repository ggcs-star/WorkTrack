<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDeadlineApproaching extends Notification
{
    use Queueable;

    public function __construct(public Task $task, public string $period)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $when = $this->period === 'week' ? 'in 1 week' : 'tomorrow';

        $priorityEmojis = ['low' => '🟢', 'medium' => '🟡', 'high' => '🔴'];
        $priorityEmoji = $priorityEmojis[$this->task->priority] ?? '';

        return (new MailMessage)
            ->subject('Reminder: "'.$this->task->title.'" is due '.$when)
            ->greeting('Hi '.$notifiable->name.',')
            ->line('This is a friendly reminder that one of your assigned tasks is due '.$when.'. Please review the task details below and ensure it is completed within the deadline.')
            ->line('**📋 Task Details**')
            ->line('**Task:** '.$this->task->title)
            ->line('**Priority:** '.$priorityEmoji.' '.ucfirst($this->task->priority))
            ->line('**Due Date:** '.$this->task->due_date->format('d F Y'))
            ->line('**Action Required:**')
            ->line('Please complete the task before the due date and update the task status in WorkTrack once the work is completed.')
            ->action('View Task', route('employee.tasks.show', $this->task))
            ->line('If you are unable to complete the task due to a dependency, clarification, or other blocker, please update the task status and provide the relevant details in WorkTrack.')
            ->line('Thank you for your timely attention.')
            ->salutation("Best regards,\nWorkTrack Team");
    }

    public function toArray(object $notifiable): array
    {
        $when = $this->period === 'week' ? 'in 1 week' : 'tomorrow';

        return [
            'type' => 'deadline',
            'task_id' => $this->task->id,
            'message' => '"'.$this->task->title.'" is due '.$when.' ('.$this->task->due_date->format('M d, Y').').',
        ];
    }
}
