<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessTypes;
class BusinessTypeController extends Controller
{
    //select business type
    public function business(Request $req) {
        // Fetch all business types

            $businessTypes = BusinessTypes::all(); // Fetch all business types




        // Check if business types exist
        if ($businessTypes->isEmpty()) {
            return response()->json([
                'message' => 'No business types found!',
                'business' => []
            ], 404);
        }

        // Return success response
        return response()->json([
            'message' => 'All business types retrieved successfully!',
            'business_types' => $businessTypes // Use correct key
        ], 200);
    }
    //admin
    public function index()
    {
        // Fetch all business types
        $businessTypes = BusinessTypes::all();
        return response()->json($businessTypes);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'business_name' => 'nullable|string',
        ]);

        // Create a new BusinessType instance
        $businessType = BusinessTypes::create($validatedData);

        return response()->json([
            'message' => 'Business Name created successfully!',
            'data' => $businessType,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // Correct the model reference here
        $businessType = BusinessTypes::find($id);

        if (!$businessType) {
            return response()->json([
                'message' => 'Business Type not found',
            ], 404);
        }

        $validatedData = $request->validate([
            'business_name' => 'nullable|string',
        ]);

        $businessType->update($validatedData);

        return response()->json([
            'message' => 'Business Type updated successfully!',
            'data' => $businessType,
        ]);
    }

    public function destroy($id)
    {
        // Correct the model reference here
        $businessType = BusinessTypes::find($id);

        if (!$businessType) {
            return response()->json([
                'message' => 'Business Type not found',
            ], 404);
        }

        $businessType->delete();

        return response()->json([
            'message' => 'Business Type deleted successfully!',
            'data' => $businessType,
        ]);
    }

}
