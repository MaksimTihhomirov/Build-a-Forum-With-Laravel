
<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('requires authentication', function () {
    $post = Post::factory()->create();

    $this->post(route('posts.comments.store', $post), [
        'body' => 'This is a comment',
    ])->assertRedirect(route('login'));
});
