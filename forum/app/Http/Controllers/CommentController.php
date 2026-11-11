<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2500'],
        ]);

        $post->comments()->create([
            'body' => $validated['body'],
            'user_id' => $request->user()->id,
        ]);

        return to_route('posts.show', $post);
    }

    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2500'],
        ]);

        $comment->update($validated);

        return to_route('posts.show', [
            'post' => $comment->post_id,
            'page' => $request->query('page'),
        ]);
    }

    public function destroy(Request $request, Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return to_route('posts.show', [
            'post' => $comment->post_id,
            'page' => $request->query('page'),
        ]);
    }
}
