<?php

namespace Tests\Unit;

use App\Jobs\PrunePosts;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrunePostsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_prunes_posts_15_days_old()
    {
        $createdPosts = Post::factory()->count(5)->state(new Sequence(
            ['created_at' => now()->addDays(3)],
            ['created_at' => now()],
            ['created_at' => now()->subDays(15)],
            ['created_at' => now()->subDays(20)],
            ['created_at' => now()->subDays(25)],
        ))->create();

        (new PrunePosts)->handle();

        $posts = Post::all();

        $this->assertCount(2, $posts);

        $this->assertTrue($posts->contains($createdPosts[0]));
        $this->assertTrue($posts->contains($createdPosts[1]));
        $this->assertFalse($posts->contains($createdPosts[2]));
        $this->assertFalse($posts->contains($createdPosts[3]));
        $this->assertFalse($posts->contains($createdPosts[4]));
    }
}
