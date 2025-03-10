<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminProfile;

class AdminProfileController extends Controller
{
    // Fetch all admin profiles
    public function index()
    {
        $adminProfiles = AdminProfile::all();
        return response()->json($adminProfiles);
    }

    // Create a new admin profile
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id'   => 'required|exists:users,id|unique:admin_profile,user_id',
            'about'     => 'required|string', // No length limit to support large text
            'company'   => 'required|string|max:255',
            'job'       => 'required|string|max:255',
            'country'   => 'required|string|max:255',
            'address'   => 'required|string|max:255',
            'phone'     => 'required|string|max:255',
            'twitter'   => 'nullable|string|max:255',
            'facebook'  => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin'  => 'nullable|string|max:255',
        ]);

        $adminProfile = AdminProfile::create($validatedData);

        return response()->json([
            'message' => 'Admin profile created successfully!',
            'data'    => $adminProfile,
        ], 201);
    }

    // Get a single admin profile
    public function show($id)
    {
        $adminProfile = AdminProfile::find($id);

        if (!$adminProfile) {
            return response()->json(['message' => 'Admin profile not found'], 404);
        }

        return response()->json($adminProfile);
    }

    // Update an admin profile
    public function update(Request $request, $id)
    {
        $adminProfile = AdminProfile::find($id);

        if (!$adminProfile) {
            return response()->json(['message' => 'Admin profile not found'], 404);
        }

        $validatedData = $request->validate([
            'about'     => 'required|string', // Allows long text input
            'company'   => 'required|string|max:255',
            'job'       => 'required|string|max:255',
            'country'   => 'required|string|max:255',
            'address'   => 'required|string|max:255',
            'phone'     => 'required|string|max:255',
            'twitter'   => 'nullable|string|max:255',
            'facebook'  => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin'  => 'nullable|string|max:255',
        ]);

        $adminProfile->update($validatedData);

        return response()->json([
            'message' => 'Admin profile updated successfully!',
            'data'    => $adminProfile,
        ], 200);
    }

    // Delete an admin profile
    public function destroy($id)
    {
        $adminProfile = AdminProfile::find($id);

        if (!$adminProfile) {
            return response()->json(['message' => 'Admin profile not found'], 404);
        }

        $adminProfile->delete();

        return response()->json(['message' => 'Admin profile deleted successfully!']);
    }
}
