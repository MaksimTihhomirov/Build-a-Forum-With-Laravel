<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    private static ?Collection $fixtures = null;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => str(fake()->sentence())->beforeLast('.')->title(),
            'body' => collect(range(1, 4))
                ->map(fn () => fake()->realText(1250))
                ->implode(PHP_EOL . PHP_EOL),
        ];
    }

    public function withFixture(): static
    {
        self::$fixtures ??= collect(
            File::files(database_path('fixtures/posts'))
        )
            ->map(fn ($file) => $file->getContents())
            ->map(function (string $contents) {
                [$title, $body] = explode("\n", $contents, 2);

                return [
                    'title' => str($title)->trim()->after('# ')->toString(),
                    'body' => trim($body),
                ];
            });

        return $this->sequence(...self::$fixtures->all());
    }
}
