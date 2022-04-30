<?php

namespace Tests\Feature;

use App\Events\PostPublished;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function users_can_create_a_new_post()
    {
        Event::fake([PostPublished::class]);

        Storage::fake('public');

        $user = User::factory()->create();

        $image = File::image('dummy.jpg');

        $response = $this->actingAs($user)->post('/posts', [
            'title' => 'Hello World',
            'image' => $image,
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertEquals(1, Post::count());

        $post = Post::first();

        Event::assertDispatched(PostPublished::class, fn ($event) => $event->post->is($post));

        $this->assertTrue($post->author->is($user));
        $this->assertEquals('Hello World', $post->title);
        $this->assertNotNull($post->image_path);
        Storage::disk('public')->assertExists($post->image_path);
    }

    /** @test */
    function guests_cannot_publish_new_posts()
    {
        $image = File::image('dummy.jpg');

        $this->post('/posts', [
            'title' => 'Hello World',
            'image' => $image,
        ])->assertStatus(302)->assertRedirect('/login');

        $this->assertEquals(0, Post::count());
    }

    /** @test */
    function post_title_is_required()
    {
        $user = User::factory()->create();

        $image = File::image('dummy.jpg');

        $response = $this->actingAs($user)->post('/posts', [
            'title' => '',
            'image' => $image,
        ]);

        $response->assertSessionHasErrors('title');

        $this->assertEquals(0, Post::count());
    }

    /** @test */
    function post_image_is_required()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/posts', [
            'title' => 'Hello World',
            'image' => '',
        ]);

        $response->assertSessionHasErrors('image');

        $this->assertEquals(0, Post::count());
    }

    /** @test */
    function post_image_must_be_valid_image()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/posts', [
            'title' => 'Hello World',
            'image' => File::create('dummy.pdf', 200),
        ]);

        $response->assertSessionHasErrors('image');

        $this->assertEquals(0, Post::count());
    }

    /** @test */
    function post_image_must_be_at_most_10_mb()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/posts', [
            'title' => 'Hello World',
            'image' => File::create('dummy.jpg', 10001),
        ]);

        $response->assertSessionHasErrors('image');

        $this->assertEquals(0, Post::count());
    }

    /** @test */
    function users_can_delete_their_post()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($post->author)->delete('/posts/'.$post->id);

        $this->assertNull($post->fresh());
        $this->assertEquals(0, Post::count());
    }

    /** @test */
    function users_cannot_delete_somebody_elses_post()
    {
        $post = Post::factory()->create();

        $response = $this->actingAs(
            User::factory()->create()
        )->delete('/posts/'.$post->id);

        $this->assertNotNull($post->fresh());
        $this->assertEquals(1, Post::count());
    }
}
