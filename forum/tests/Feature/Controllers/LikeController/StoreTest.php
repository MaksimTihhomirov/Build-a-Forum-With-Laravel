<?php

namespace Tests\Feature\Controllers\LikeController;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_requires_authentication(): void
    {
        $post = Post::factory()->create();

        $this->post(route('likes.store', [
            'type' => 'post',
            'id' => $post->id,
        ]))->assertRedirect(route('login'));
    }

    public function test_allows_liking_a_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)
            ->post(route('likes.store', [
                'type' => 'post',
                'id' => $post->id,
            ]))
            ->assertRedirect();

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => $post->getMorphClass(),
        ]);

        $this->assertEquals(1, $post->fresh()->likes_count);
    }

    public function test_cannot_like_the_same_post_twice(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $url = route('likes.store', [
            'type' => 'post',
            'id' => $post->id,
        ]);

        $this->post($url)->assertRedirect();
        $this->post($url)->assertForbidden();

        $this->assertEquals(1, $post->fresh()->likes_count);
    }
}
