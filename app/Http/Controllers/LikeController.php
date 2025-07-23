<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Photo;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggleLike(Photo $photo)
    {
        $like = Like::where('photo_id', $photo->id)->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Unliked']);
        }

        Like::create([
            'photo_id' => $photo->id,
            'user_id' => auth()->id(),
        ]);

        return response()->json(['message' => 'Liked']);
    }
}
