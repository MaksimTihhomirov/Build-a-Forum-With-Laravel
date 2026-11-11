<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
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

    public function show(Post $post)
    {
        $post->load('user');

        return Inertia::render('Posts/Show', [
            'post' => PostResource::make($post->refresh()),
            'comments' => CommentResource::collection(
                $post->comments()
                    ->with('user')
                    ->latest()
                    ->paginate(10)
            ),
        ]);
    }
}
