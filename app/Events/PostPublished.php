<?php

namespace App\Events;

use App\Models\Post;
use App\Models\User;
use Closure;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostPublished
{
    use Dispatchable, SerializesModels;

    public Post $post;

    /**
     * Create a new event instance.
     *
     * @param Post $post
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Invoke the callback for each notifiable user.
     *
     * @param Closure(User): (void) $callback
     * @return void
     */
    public function forEachNotifiable(Closure $callback) : void
    {
        // Chunk the results to improve data-fetching...
        User::whereNot('id', $this->post->user_id)->chunk(
            1000, fn ($users) => $users->each($callback)
        );
    }
}
