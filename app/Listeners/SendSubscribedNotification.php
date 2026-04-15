<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Subscribed;
use App\Notifications\Subscribed as SubscribedNotification;

class SendSubscribedNotification implements ShouldQueue
{
    public $queue = 'listeners';

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Subscribed $event): void
    {
        $event->blog->user->notify(new SubscribedNotification($event->user, $event->blog));
    }
}
