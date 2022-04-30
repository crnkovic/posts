<?php

namespace App\Http\Controllers\Api;

use App\Events\PostPublished;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display all of the posts feed.
     *
     * @param Request $request
     * @param PostRepository $posts
     * @return JsonResponse
     */
    public function index(Request $request, PostRepository $posts) : JsonResponse
    {
        return new JsonResponse($posts->forUser($request->user()));
    }

    /**
     * Create a new post for the user.
     *
     * @param CreatePostRequest $request
     * @return JsonResponse
     */
    public function store(CreatePostRequest $request) : JsonResponse
    {
        PostPublished::dispatch($post = $request->user()->posts()->create([
            'title' => $request->title,
            'image_path' => $request->uploadedImagePath(),
        ]));

        return new JsonResponse($post, 201);
    }

    /**
     * Delete the user's post.
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post) : JsonResponse
    {
        $this->authorize('delete', $post);

        $post->delete();

        return new JsonResponse('');
    }
}
