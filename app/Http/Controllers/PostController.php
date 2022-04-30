<?php

namespace App\Http\Controllers;

use App\Events\PostPublished;
use App\Http\Requests\CreatePostRequest;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display all of the posts feed.
     *
     * @param Request $request
     * @param PostRepository $posts
     * @return Response
     */
    public function index(Request $request, PostRepository $posts) : Response
    {
        return Inertia::render('Dashboard', [
            'posts' => $posts->forUser($request->user()),
        ]);
    }

    /**
     * Display the form for writing a post.
     *
     * @return Response
     */
    public function create() : Response
    {
        return Inertia::render('Write');
    }

    /**
     * Create a new post for the user.
     *
     * @param CreatePostRequest $request
     * @return RedirectResponse
     */
    public function store(CreatePostRequest $request) : RedirectResponse
    {
        PostPublished::dispatch($request->user()->posts()->create([
            'title' => $request->title,
            'image_path' => $request->uploadedImagePath(),
        ]));

        return redirect('/dashboard');
    }

    /**
     * Delete the user's post.
     *
     * @param Post $post
     * @return RedirectResponse
     */
    public function destroy(Post $post) : RedirectResponse
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect('/dashboard');
    }
}
