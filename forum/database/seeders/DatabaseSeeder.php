<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(10)->create();

        Post::factory(20)
            ->recycle($users)
            ->has(
                Comment::factory(5)->recycle($users)
            )
            ->create();

        Post::factory()
            ->withFixture()
            ->recycle($users)
            ->create();
    }
}
