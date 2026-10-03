<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Your WorkTrack Password')
            ->greeting('Hi '.$notifiable->name.',')
            ->line('We received a request to reset the password for your WorkTrack account.')
            ->line('Click the button below to choose a new password. This link will expire in 60 minutes.')
            ->action('Reset Password', $url)
            ->line('If you did not request a password reset, no further action is required and your password will remain unchanged.')
            ->salutation("Thank you,\nWorkTrack Team");
    }
}
