<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HousePhoto;
use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HousePhotoController extends Controller
{
    public function index(House $house)
    {
        $photos = $house->photos()->orderByDesc('created_at')->get();
        return view('admin.houses.photos.index', compact('house', 'photos'));
    }

    public function store(Request $request, House $house)
    {
        $request->validate([
            'photo' => 'required|image|max:4096',
            'description' => 'nullable|string|max:255',
        ]);
        
        $photo = $request->file('photo');
        $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
        $photo->move(public_path('uploads/houses'), $filename);
        $path = 'uploads/houses/' . $filename;
        
        $house->photos()->create([
            'photo' => $path,
            'description' => $request->description,
            'can_show' => true,
        ]);
        return back()->with('success', 'Photo uploaded successfully.');
    }

    public function destroy(House $house, HousePhoto $photo)
    {
        if ($photo->photo && file_exists(public_path($photo->photo))) {
            unlink(public_path($photo->photo));
        }
        $photo->delete();
        return back()->with('success', 'Photo deleted successfully.');
    }

    public function toggleVisibility(House $house, HousePhoto $photo)
    {
        $photo->can_show = !$photo->can_show;
        $photo->save();
        return back()->with('success', 'Photo visibility updated.');
    }
} 