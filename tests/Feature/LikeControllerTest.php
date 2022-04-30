<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function users_can_like_a_post()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create();

        $this->assertFalse($user->hasLiked($post));

        $this->actingAs($user)
                ->postJson('/api/posts/'.$post->id.'/likes')
                ->assertStatus(201);

        $this->assertTrue($user->hasLiked($post));
    }

    /** @test */
    function users_can_unlike_a_post()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create();

        $user->like($post);

        $this->assertTrue($user->hasLiked($post));

        $this->actingAs($user)
                ->deleteJson('/api/posts/'.$post->id.'/likes')
                ->assertStatus(200);

        $this->assertFalse($user->hasLiked($post));
    }
}
