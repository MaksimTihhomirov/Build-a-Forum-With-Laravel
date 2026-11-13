<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $selectedTopic = Topic::query()
            ->where('slug', $request->query('topic'))
            ->first();

        $posts = Post::query()
            ->with(['user', 'topic'])
            ->when(
                $selectedTopic,
                fn ($query) => $query->whereBelongsTo($selectedTopic)
            )
            ->latest()
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Posts/Index', [
            'posts' => PostResource::collection($posts),
            'topics' => TopicResource::collection(
                Topic::query()->orderBy('name')->get()
            ),
            'selectedTopic' => $selectedTopic
                ? TopicResource::make($selectedTopic)
                : null,
        ]);
    }

    public function create()
    {
        return Inertia::render('Posts/Create');
    }

    public function show(Request $request, Post $post)
    {
        $expectedSlug = Str::slug($post->title);

        if ($request->route('slug') !== $expectedSlug) {
            return redirect(
                $post->showRoute($request->query()),
                301
            );
        }

        $post->load('user');

        return Inertia::render('Posts/Show', [
            'post' => PostResource::make($post),
            'comments' => CommentResource::collection(
                $post->comments()
                    ->with('user')
                    ->latest()
                    ->paginate(10)
            ),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'body' => ['required', 'string', 'min:3', 'max:10000'],
        ]);

        $post = Post::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'user_id' => $request->user()->id,
        ]);

        return redirect($post->showRoute());
    }
}
