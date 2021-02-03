<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as Notification;

class ResetPassword extends Notification
{

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // construct route for the front end
        $url = url(config('app.client_url') . '/password/reset/' . $this->token) . '?email='.urlencode($notifiable->email);      
        return (new MailMessage)
                    ->line('You are receiving this email because we received a password request for this account.')
                    ->action('Reset Password', $url)
                    ->line('If you did not request this email, no further action is required.');
    }    
}
