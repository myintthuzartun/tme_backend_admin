<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AddBusinessType;
use Illuminate\Support\Facades\Validator;

class AddBusinessTypeController extends Controller
{
    // Get all business types with translations
    public function index(Request $request)
    {
        $lang = $request->query('lang', 'en'); // Default to English
    
        $businessTypes = AddBusinessType::all()->map(function ($businessType) use ($lang) {
            $business_name = $businessType->{'business_name_' . $lang} ?? null;

            // If business name is null, exclude this row
            if ($business_name === null) {
                return null;
            }

            return [
                'id' => $businessType->id,
                'business_name' => $business_name,
            ];
        })->filter(); // Remove null values

        return response()->json($businessTypes->values()); // Reset array keys
    }

    // Create a new business type
    public function store(Request $request)
    {
        $rules = [
            'business_name_en' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[A-Za-z0-9\s]+$/', $value)) {
                    $fail('The English business name must contain only English letters, numbers, and spaces.');
                }
            }],
            'business_name_myan' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Myanmar}\s]+$/u', $value)) {
                    $fail('The Myanmar business name must contain only Myanmar script.');
                }
            }],
            'business_name_thai' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Thai}\s]+$/u', $value)) {
                    $fail('The Thai business name must contain only Thai script.');
                }
            }],
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $businessType = AddBusinessType::create($request->all());
    
        return response()->json([
            'message' => 'Business type created successfully!',
            'data' => $businessType,
        ], 201);
    }

    // Show a single business type by ID and language
    public function show($id, $lang = 'en')
    {
        $businessType = AddBusinessType::find($id);

        if (!$businessType) {
            return response()->json(['message' => 'Business type not found'], 404);
        }

        return response()->json([
            'id' => $businessType->id,
            'business_name' => $businessType->{'business_name_' . $lang} ?? null,
        ]);
    }

    // Update a business type
    public function update(Request $request, $id)
    {
        $businessType = AddBusinessType::find($id);
    
        if (!$businessType) {
            return response()->json(['message' => 'Business type not found'], 404);
        }
    
        $rules = [
            'business_name_en' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[A-Za-z0-9\s]+$/', $value)) {
                    $fail('The English business name must contain only English letters, numbers, and spaces.');
                }
            }],
            'business_name_myan' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Myanmar}\s]+$/u', $value)) {
                    $fail('The Myanmar business name must contain only Myanmar script.');
                }
            }],
            'business_name_thai' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Thai}\s]+$/u', $value)) {
                    $fail('The Thai business name must contain only Thai script.');
                }
            }],
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $businessType->update($request->all());
    
        return response()->json([
            'message' => 'Business type updated successfully!',
            'data' => $businessType,
        ]);
    }

    // Delete a business type
    public function destroy($id)
    {
        $businessType = AddBusinessType::find($id);

        if (!$businessType) {
            return response()->json(['message' => 'Business type not found'], 404);
        }

        $businessType->delete();

        return response()->json(['message' => 'Business type deleted successfully!']);
    }
}
