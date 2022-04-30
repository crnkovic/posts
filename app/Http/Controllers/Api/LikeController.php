<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only([
            'store', 'destroy'
        ]);
    }

    /**
     * Get all likes of a specific post.
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function index(Post $post) : JsonResponse
    {
        // Could use pagination... Depending on scope...

        return new JsonResponse([
            'likes' => $post->likes,
        ]);
    }

    /**
     * Store a new like for the post.
     *
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     */
    public function store(Request $request, Post $post) : JsonResponse
    {
        // Depends on business specs, we can restrict so users cannot like their own post...

        if (! $request->user()->hasLiked($post)) {
            $request->user()->like($post);
        }

        return new JsonResponse('', 201);
    }

    /**
     * Remove a like from the post.
     *
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Request $request, Post $post) : JsonResponse
    {
        // Depends on business specs, we can restrict so users cannot unlike their own post...

        if ($request->user()->hasLiked($post)) {
            $request->user()->unlike($post);
        }

        return new JsonResponse('');
    }
}
