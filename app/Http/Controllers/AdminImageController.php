<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminImage;
use Illuminate\Support\Facades\File;
use App\Models\AdminProfile;

class AdminImageController extends Controller
{
    // Fetch all images
    public function index()
    {
        $images = AdminImage::all();
        return response()->json($images);
    }

    // Store a new image
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif', // Removed max size limit
        ]);

        // Store the uploaded file in the public/images directory
        $image = $request->file('image');
        $imagePath = $image->move(public_path('images'), $image->getClientOriginalName());

        // Create a record for the uploaded image in the AdminImage table
        $adminImage = AdminImage::create([
            'image_path' => 'images/' . $image->getClientOriginalName(),
        ]);

        // Now, let's associate the image with the admin profile
        // Assuming you have a 'user_id' field in the AdminProfile table
        $user = auth()->user(); // Get the authenticated user
        if ($user) {
            $adminProfile = AdminProfile::where('user_id', $user->id)->first();

            if ($adminProfile) {
                // Update the profile with the new image path
                $adminProfile->update([
                    'image_path' => 'images/' . $image->getClientOriginalName(), // Update image path in profile
                ]);
            }
        }

        return response()->json([
            'message' => 'Image uploaded and associated with the profile successfully',
            'image' => $adminImage,
        ]);
    }

    // Show a specific image
    public function show($id)
    {
        $image = AdminImage::find($id);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        return response()->json($image);
    }

    // Update an image (replace with a new one)
    public function update(Request $request, $id)
    {
        $adminImage = AdminImage::find($id);

        if (!$adminImage) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif', // Removed max size limit
        ]);

        // Delete old image from public/images
        $oldImagePath = public_path($adminImage->image_path);
        if (File::exists($oldImagePath)) {
            File::delete($oldImagePath);
        }

        // Store new image in public/images
        $path = $request->file('image')->move(public_path('images'), $request->file('image')->getClientOriginalName());

        // Update record in the database
        $adminImage->update(['image_path' => 'images/' . $request->file('image')->getClientOriginalName()]);

        // Optionally, update the AdminProfile with the new image path
        $user = auth()->user();
        if ($user) {
            $adminProfile = AdminProfile::where('user_id', $user->id)->first();

            if ($adminProfile) {
                // Update profile's image_path with the new uploaded image path
                $adminProfile->update([
                    'image_path' => 'images/' . $request->file('image')->getClientOriginalName(),
                ]);
            }
        }

        return response()->json([
            'message' => 'Image updated successfully',
            'image' => $adminImage,
        ]);
    }

    // Delete an image
    public function destroy($id)
    {
        $adminImage = AdminImage::find($id);

        if (!$adminImage) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        // Delete image from public/images
        $imagePath = public_path($adminImage->image_path);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        // Delete the record from the database
        $adminImage->delete();

        // Optionally, you could also remove the image from the AdminProfile if it's associated
        $user = auth()->user();
        if ($user) {
            $adminProfile = AdminProfile::where('user_id', $user->id)->first();

            if ($adminProfile && $adminProfile->image_path === $adminImage->image_path) {
                $adminProfile->update(['image_path' => null]); // Set image_path to null or an appropriate default
            }
        }

        return response()->json(['message' => 'Image deleted successfully']);
    }
}