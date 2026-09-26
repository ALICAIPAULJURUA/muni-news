<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    protected array $likeables = [
        \App\Models\Article::class,
        \App\Models\Newsletter::class,
    ];

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'likeable_type' => ['required', 'string'],
            'likeable_id' => ['required', 'integer'],
        ]);

        if (! in_array($validated['likeable_type'], $this->likeables)) {
            return response()->json(['message' => 'Invalid type.'], 422);
        }

        $type = $validated['likeable_type'];
        $model = $type::findOrFail($validated['likeable_id']);

        $existing = Like::where('user_id', auth()->id())
            ->where('likeable_type', $type)
            ->where('likeable_id', $model->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            $model->likes()->create(['user_id' => auth()->id()]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $model->likes()->count(),
        ]);
    }

    public function destroy(Like $like): JsonResponse
    {
        if ($like->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $model = $like->likeable;
        $like->delete();

        return response()->json([
            'liked' => false,
            'count' => $model ? $model->likes()->count() : 0,
        ]);
    }
}