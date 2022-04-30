<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;
use Illuminate\Http\Request;
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
    public function show(Request $request, PostRepository $posts) : Response
    {
        return Inertia::render('Feed', [
            'posts' => $posts->includingLikes($request),
        ]);
    }
}
