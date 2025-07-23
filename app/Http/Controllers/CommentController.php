<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Photo;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Photo $photo)
    {
        $request->validate(['comment' => 'required|string']);

        return Comment::create([
            'photo_id' => $photo->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
