<?php

namespace Tests\Unit;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_deletes_the_photo_from_storage_when_deleted()
    {
        Storage::fake('public');

        $path = Storage::disk('public')->put('dummy', $image = File::image('dummy.jpg'));

        Storage::disk('public')->assertExists($path);

        Post::factory()->create([
            'image_path' => $path,
        ])->delete();

        Storage::disk('public')->assertMissing($path);
    }

    /** @test */
    public function it_can_scope_the_query_for_posts_older_than_x_days()
    {
        $createdPosts = Post::factory()->count(5)->state(new Sequence(
            ['created_at' => now()->addDays(3)],
            ['created_at' => now()],
            ['created_at' => now()->subDays(5)],
            ['created_at' => now()->subDays(10)],
            ['created_at' => now()->subDays(15)],
        ))->create();

        $posts = Post::olderThan(days: 5)->get();

        $this->assertFalse($posts->contains($createdPosts[0]));
        $this->assertFalse($posts->contains($createdPosts[1]));
        $this->assertTrue($posts->contains($createdPosts[2]));
        $this->assertTrue($posts->contains($createdPosts[3]));
        $this->assertTrue($posts->contains($createdPosts[4]));
    }

    /** @test */
    function it_has_uuid_as_primary_key()
    {
        Str::createUuidsUsing(fn () => 'dummy-uuid');

        $post = Post::factory()->create();

        $this->assertEquals('dummy-uuid', $post->id);

        Str::createUuidsNormally();
    }
}
