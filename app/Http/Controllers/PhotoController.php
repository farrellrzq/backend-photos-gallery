<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index()
    {
        return Photo::withCount('likes', 'comments')->with('comments')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = $request->file('image')->store('photos', 'public');

        return Photo::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $path,
            'uploaded_by' => auth()->id(),
        ]);
    }

    public function update(Request $request, Photo $photo)
    {
        // $this->authorize('update', $photo); // bisa tambahkan policy nanti
        if ($photo->uploaded_by !== auth()->id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
        }


        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($photo->image_path);
            $path = $request->file('image')->store('photos', 'public');
            $photo->image_path = $path;
        }

         $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus foto lama
            Storage::disk('public')->delete($photo->image_path);

            // Simpan foto baru
            $photo->image_path = $request->file('image')->store('photos', 'public');
        }

        $photo->update($request->only('title', 'description'));
        return $photo;
    }

    public function destroy(Photo $photo)
    {
        Storage::disk('public')->delete($photo->image_path);
        $photo->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function download(Photo $photo)
    {
        return response()->download(storage_path("app/public/{$photo->image_path}"));
    }
}
