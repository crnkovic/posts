<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /**
     * Show the Post feed.
     *
     * @param Request $request
     * @param PostRepository $posts
     * @return Response
     */
    public function show(Request $request, PostRepository $posts) : JsonResponse
    {
        return new JsonResponse(
            $posts->includingLikes($request)
        );
    }
}
