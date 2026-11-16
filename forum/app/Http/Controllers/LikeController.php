<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LikeController extends Controller
{
    public function store(Request $request, string $type, int $id)
    {
        $modelName = Relation::getMorphedModel($type);

        abort_unless($modelName, 404);

        $likeable = $modelName::findOrFail($id);

        Gate::authorize('create', Like::class);

        abort_if(
            $likeable->likes()
                ->where('user_id', $request->user()->id)
                ->exists(),
            403
        );

        $likeable->likes()->create([
            'user_id' => $request->user()->id,
        ]);

        return back();
    }

    public function destroy(Request $request, string $type, int $id)
    {
        $modelName = Relation::getMorphedModel($type);

        abort_unless($modelName, 404);

        $likeable = $modelName::findOrFail($id);

        $like = $likeable->likes()
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        Gate::authorize('delete', $like);

        $like->delete();

        return back();
    }
}
