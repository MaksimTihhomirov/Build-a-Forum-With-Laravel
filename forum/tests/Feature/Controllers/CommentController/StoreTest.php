
<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('requires authentication', function () {
    $post = Post::factory()->create();

    $this->post(route('posts.comments.store', $post), [
        'body' => 'This is a comment',
    ])->assertRedirect(route('login'));
});

test('redirects to the post after creating a comment', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $this->actingAs($user)
        ->post(route('posts.comments.store', $post), [
            'body' => 'This is a comment',
        ])
        ->assertRedirect($post->showRoute());
});
