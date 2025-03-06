<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorLevel;
class VendorLevelController extends Controller
{
    //
    public  function index()
    {
        $vendorLevels = VendorLevel::all();
        return $vendorLevels;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'vendor_level' => 'required|string|max:50',
            'level_description' => 'nullable|string',
            'benefits' => 'nullable|string',
        ]);

        $vendorLevel = VendorLevel::create($validatedData);

        return response()->json([
            'message' => 'Vendor level created successfully!',
            'data' => $vendorLevel,
        ], 201);
    }


    // Method to read a single vendor level by ID
    public function show($id)
    {
        // Find the vendor level by ID
        $vendorLevel = VendorLevel::find($id);

        // If vendor level not found, return a 404 response
        if (!$vendorLevel) {
            return response()->json([
                'message' => 'Vendor level not found',
            ], 404);
        }

        // Return the found vendor level
        return response()->json($vendorLevel);
    }



    public function update(Request $request, $id)
{
    $vendorLevel = VendorLevel::find($id);

    if (!$vendorLevel) {
        return response()->json([
            'message' => 'Vendor level not found',
        ], 404);
    }

    $validatedData = $request->validate([
        'vendor_level' => 'required|string|max:50',
        'level_description' => 'nullable|string',
        'benefits' => 'nullable|string',
    ]);

    $vendorLevel->update($validatedData);

    return response()->json([
        'message' => 'Vendor level updated successfully!',
        'data' => $vendorLevel,
    ]);
}


public function destroy($id)
{
    $vendorLevel = VendorLevel::find($id);

    if (!$vendorLevel) {
        return response()->json([
            'message' => 'Vendor level not found',
        ], 404);
    }

    $vendorLevel->delete();

    return response()->json([
        'message' => 'Vendor level deleted successfully!',
        'data' => $vendorLevel,
    ]);
}


}
