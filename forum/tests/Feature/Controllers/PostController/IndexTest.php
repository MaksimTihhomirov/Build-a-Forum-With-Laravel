
<?php

use App\Models\Post;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('it passes posts to the view', function () {
    $topic = Topic::factory()->create();

    Post::factory()
        ->count(3)
        ->for($topic)
        ->create();

    $this->get(route('posts.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Posts/Index')
            ->has('posts.data', 3)
            ->has('posts.links')
            ->has('posts.meta')
        );
});

test('it can filter posts by topic', function () {
    $general = Topic::factory()->create();
    $other = Topic::factory()->create();

    Post::factory()
        ->count(2)
        ->for($general)
        ->create();

    Post::factory()
        ->count(2)
        ->for($other)
        ->create();

    $this->get(route('posts.index', [
        'topic' => $general->slug,
    ]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Posts/Index')
            ->has('posts.data', 2)
            ->has('posts.links')
            ->has('posts.meta')
        );
});
