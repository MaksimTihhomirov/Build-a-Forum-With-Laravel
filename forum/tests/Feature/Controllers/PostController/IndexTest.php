
<?php
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

use App\Http\Resources\PostResource;
use App\Models\Post;
use Inertia\Testing\AssertableInertia as Assert;

test('it passes posts to the view', function () {
    $posts = Post::factory()->count(3)->create();

    $this->get(route('posts.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Posts/Index')
            ->hasPaginatedResource(
                'posts',
                PostResource::collection(
                    Post::query()->latest()->paginate(10)
                )
            )
        );
});
