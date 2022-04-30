<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;

class FeedController extends Controller
{
    /**
     * Show the Post feed.
     *
     * @param PostRepository $posts
     * @return Response
     */
    public function show(PostRepository $posts) : JsonResponse
    {
        return new JsonResponse($posts->includingLikes());
    }
}
