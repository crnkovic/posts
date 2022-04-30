<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function users_can_like_a_post()
    {
        $user = User::factory()->create();

        $user->like(
            $post = Post::factory()->create([
                'total_likes' => 0,
            ]),
        );

        $this->assertEquals(1, $post->likes()->count());
        $this->assertEquals($user->id, $post->likes()->first()->pivot->user_id);

        $this->assertEquals(1, $post->fresh()->total_likes);
    }

    /** @test */
    function liking_increments_count()
    {
        $user = User::factory()->create();

        $user->like(
            $post = Post::factory()->create([
                'total_likes' => 10,
            ]),
        );

        $this->assertEquals(1, $post->likes()->count());
        $this->assertEquals($user->id, $post->likes()->first()->pivot->user_id);

        $this->assertEquals(11, $post->fresh()->total_likes);
    }

    /** @test */
    function users_can_remove_their_like()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'total_likes' => 10,
        ]);

        $user->likes()->attach($post);

        $user->unlike($post);

        $this->assertEquals(0, $post->likes()->count());
        $this->assertEquals(9, $post->fresh()->total_likes);
    }

    /** @test */
    function unliking_is_limited_to_0()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'total_likes' => 0,
        ]);

        $user->likes()->attach($post);

        $user->unlike($post);

        $this->assertEquals(0, $post->likes()->count());

        $this->assertEquals(0, $post->fresh()->total_likes);
    }

    /** @test */
    function can_determine_if_user_liked_a_post()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create();

        $this->assertFalse($user->hasLiked($post));

        $user->like($post);

        $this->assertTrue($user->hasLiked($post));

        $user->unlike($post);

        $this->assertFalse($user->hasLiked($post));
    }
}
