
<?php

use function Pest\Laravel\get;

use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('it passes posts to the view', function () {
    Post::factory()->count(3)->create();

    $posts = Post::query()
        ->with(['user', 'topic'])
        ->latest()
        ->latest('id')
        ->paginate(10);

    get(route('posts.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Posts/Index')
            ->has('posts.data', 3)
            ->has('posts.links')
            ->has('posts.meta')
        );
});

test('it passes topics to the view', function () {
    $topics = Topic::factory()->count(3)->create();

    get(route('posts.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Posts/Index')
            ->has('selectedTopic')
        );
});

test('it can filter posts by topic', function () {
    $general = Topic::factory()->create([
        'slug' => 'general',
    ]);

    $reviews = Topic::factory()->create([
        'slug' => 'reviews',
    ]);

    Post::factory()->count(2)->create([
        'topic_id' => $general->id,
    ]);

    Post::factory()->count(2)->create([
        'topic_id' => $reviews->id,
    ]);

    get(route('posts.index', ['topic' => $general->slug]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Posts/Index')
            ->has('posts.data', 2)
            ->where('selectedTopic.slug', 'general')
        );
});
