<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2500'],
        ]);

        Comment::make($data)
            ->user()->associate($request->user())
            ->post()->associate($post)
            ->save();

        return redirect()->route('posts.show', $post);
    }
}
