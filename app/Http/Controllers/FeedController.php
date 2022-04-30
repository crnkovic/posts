<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;
use Inertia\Inertia;
use Inertia\Response;

class FeedController extends Controller
{
    /**
     * Show the Post feed.
     *
     * @param Request $request
     * @param PostRepository $posts
     * @return Response
     */
    public function show(PostRepository $posts) : Response
    {
        return Inertia::render('Feed', [
            'posts' => $posts->includingLikes(),
        ]);
    }
}
