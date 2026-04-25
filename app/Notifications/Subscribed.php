<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Blog;
use Illuminate\Notifications\AnonymousNotifiable;
use App\Mail\Subscribed as SubscribedMailable;
use Illuminate\Notifications\Messages\BroadcastMessage;

class Subscribed extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public readonly User $user, public readonly Blog $blog)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        // return (new MailMessage)
        //     ->line('The introduction to the notification.')
        //     ->action('Notification Action', url('/'))
        //     ->line('Thank you for using our application!');

        $address = $notifiable instanceof AnonymousNotifiable
                 ? $notifiable->routeNotificationFor('mail')
                 : $notifiable->email;

        return (new SubscribedMailable($this->user, $this->blog))
              ->to($address);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
            'broadcast' => 'broadcasts'
        ];
    }

    public function shouldSend($notifiable, $channel)
    {
        return true;
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'user' => $this->user,
        ]);
    }
}
