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

        return redirect($post->showRoute())
            ->with('flash.banner', 'Comment added!')
            ->with('flash.bannerStyle', 'success');
    }

    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2500'],
        ]);

        $comment->update($validated);

        return redirect(
            $comment->post->showRoute(
                $request->query()
            )
        )
            ->with('flash.banner', 'Comment updated!')
            ->with('flash.bannerStyle', 'success');
    }

    public function destroy(Request $request, Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $post = $comment->post;

        $comment->delete();

        return redirect(
            $post->showRoute(
                $request->query()
            )
        )
            ->with('flash.banner', 'Comment deleted!')
            ->with('flash.bannerStyle', 'success');
    }
}
