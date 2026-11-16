<?php

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('it passes a post to the view', function () {
    $post = Post::factory()->create();

    $post->load(['user', 'topic']);

    $this->followingRedirects()
        ->get(route('posts.show', $post))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Posts/Show')
            ->hasResource('post', PostResource::make($post))
        );
});

test('it passes comments to the view', function () {
    $post = Post::factory()->create();

    Comment::factory()
        ->count(2)
        ->for($post)
        ->create();

    $this->followingRedirects()
        ->get(route('posts.show', $post))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Posts/Show')
            ->hasPaginatedResource(
                'comments',
                CommentResource::collection(
                    $post->comments()
                        ->with('user')
                        ->withCount('likes')
                        ->latest()
                        ->paginate(10)
                )
            )
        );
});
