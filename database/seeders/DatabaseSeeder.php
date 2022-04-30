<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Josip Crnkovic',
            'email' => 'josip@hey.com',
        ]);

        $this->seedPosts();
    }

    protected function seedPosts() : void
    {
        $titles = [
            'Hello World',
            'This is a dummy.',
            'This is another dummy post.',
            'Another dummy post.',
        ];

        foreach ($titles as $title) {
            $randomImage = ['a', 'b', 'c'][random_int(0, 2)];

            Storage::disk(Post::DISK)->put(
                $path = 'images/'.Str::random(32).'.jpg', file_get_contents(storage_path('development/'.$randomImage.'.jpg'))
            );

            Post::factory()->create([
                'title' => $title,
                'user_id' => 1,
                'image_path' => $path,
            ]);
        }
    }
}
