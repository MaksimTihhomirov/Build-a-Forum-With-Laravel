<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Posts/Index', [
            'posts' => PostResource::collection(
                Post::query()
                    ->with('user')
                    ->latest()
                    ->paginate(10)
            ),
        ]);
    }

    public function show(Post $post): Response
    {
        $post->load('user');

        return Inertia::render('Posts/Show', [
            'post' => PostResource::make($post),
            'comments' => CommentResource::collection(
                $post->comments()
                    ->with('user')
                    ->latest()
                    ->paginate(10)
            ),
        ]);
    }
}
