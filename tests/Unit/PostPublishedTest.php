<?php

namespace Tests\Unit;

use App\Events\PostPublished;
use App\Listeners\NotifyUsersOfNewPost;
use App\Models\Post;
use App\Models\User;
use App\Notifications\PostPublishedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PostPublishedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_notifies_all_users_of_a_new_post()
    {
        Notification::fake();

        User::factory(5)->create();

        $event = new PostPublished(
            $post = Post::factory()->create()
        );

        (new NotifyUsersOfNewPost)->handle($event);

        Notification::assertTimesSent(5, PostPublishedNotification::class);
        Notification::assertNothingSentTo($post->author);
    }
}
