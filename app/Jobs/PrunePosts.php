<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PrunePosts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        // If there's a large number of posts, this can be improved by chunking posts,
        // getting image paths for all of these posts, running `$filesystem->delete($paths)` to mass-delete images from storage
        // and run a `DELETE * FROM posts WHERE IN id = []` mass-delete query to remove them.

        // Since we defined an `deleted` listener to purge the file from storage once post is deleted,
        // this way is currently used.

        Post::query()->olderThan(days: 15)->chunk(100, function ($posts) {
            $posts->each->delete();
        });
    }
}
