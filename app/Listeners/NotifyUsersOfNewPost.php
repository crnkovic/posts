<?php

namespace App\Listeners;

use App\Events\PostPublished;
use App\Models\User;
use App\Notifications\PostPublishedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyUsersOfNewPost implements ShouldQueue
{
    use Queueable;

    /**
     * Handle the event.
     *
     * @param PostPublished $event
     * @return void
     */
    public function handle(PostPublished $event) : void
    {
        $event->forEachNotifiable(
            fn (User $notifiable) => $notifiable->notify(new PostPublishedNotification($event->post))
        );
    }
}
