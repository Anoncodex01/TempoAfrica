<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomPhoto;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomPhotoController extends Controller
{
    public function index(Room $room)
    {
        $photos = $room->photos()->orderByDesc('created_at')->get();
        return view('admin.rooms.photos.index', compact('room', 'photos'));
    }

    public function store(Request $request, Room $room)
    {
        $request->validate([
            'photo' => 'required|image|max:4096',
            'description' => 'nullable|string|max:255',
        ]);
        $path = $request->file('photo')->store('room_photos', 'public');
        $room->photos()->create([
            'photo' => $path,
            'description' => $request->description,
            'can_show' => true,
        ]);
        return back()->with('success', 'Photo uploaded successfully.');
    }

    public function destroy(Room $room, RoomPhoto $photo)
    {
        if ($photo->photo) {
            Storage::disk('public')->delete($photo->photo);
        }
        $photo->delete();
        return back()->with('success', 'Photo deleted successfully.');
    }

    public function toggleVisibility(Room $room, RoomPhoto $photo)
    {
        $photo->can_show = !$photo->can_show;
        $photo->save();
        return back()->with('success', 'Photo visibility updated.');
    }
} 