<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostRepository
{
    public const PER_PAGE = 2;
    public const DISPLAYABLE_LIKES = 5;

    /**
     * Get all of the posts for the feed.
     *
     * @return AnonymousResourceCollection
     */
    public function includingLikes() : AnonymousResourceCollection
    {
        return PostResource::collection(
                Post::latest('created_at')
                    ->with(['author:id,name,email', 'likes' => fn ($query) => $query->limit(static::DISPLAYABLE_LIKES)])
                    ->simplePaginate(static::PER_PAGE)
        );
    }

    /**
     * Get all of the user's posts.
     *
     * @param User $user
     * @return AnonymousResourceCollection
     */
    public function forUser(User $user) : AnonymousResourceCollection
    {
        return PostResource::collection(
                $user->posts()
                    ->latest('created_at')
                    ->with(['author:id,name,email', 'likes' => fn ($query) => $query->limit(static::DISPLAYABLE_LIKES)])
                    ->simplePaginate(static::PER_PAGE)
        );
    }
}
