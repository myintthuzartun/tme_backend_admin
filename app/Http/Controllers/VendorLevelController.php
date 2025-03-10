<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorLevel;
use Illuminate\Support\Facades\Validator; //Validate the input of data; 
class VendorLevelController extends Controller
{
    // Get all vendor levels with translations
    public function index(Request $request)
    {
        $lang = $request->query('lang', 'en'); // Default to English
    
        $vendorLevels = VendorLevel::all()->map(function ($vendorLevel) use ($lang) {
            $level = $vendorLevel->{'level_' . $lang};
            $level_description = $vendorLevel->{'level_description_' . $lang};
            $benefits = $vendorLevel->{'benefits_' . $lang};
    
            // If any key is null, exclude this row
            if ($level === null || $level_description === null || $benefits === null) {
                return null;
            }
    
            return [
                'id' => $vendorLevel->id,
                'level' => $level,
                'level_description' => $level_description,
                'benefits' => $benefits,
            ];
        })->filter(); // Remove null values
    
        return response()->json($vendorLevels->values()); // Reset array keys
    }
    
    
    // Create a new vendor level
  

    public function store(Request $request)
    {
        $rules = [
            'level_en' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[A-Za-z0-9\s]+$/', $value)) {
                    $fail('The English level must contain only English letters, numbers, and spaces.');
                }
            }],
            'level_myan' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Myanmar}\s]+$/u', $value)) {
                    $fail('The Myanmar level must contain only Myanmar script.');
                }
            }],
            'level_thai' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Thai}\s]+$/u', $value)) {
                    $fail('The Thai level must contain only Thai script.');
                }
            }],
            'level_description_en' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[A-Za-z0-9\s]+$/', $value)) {
                    $fail('The English description must contain only English letters, numbers, and spaces.');
                }
            }],
            'level_description_myan' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Myanmar}\s]+$/u', $value)) {
                    $fail('The Myanmar description must contain only Myanmar script.');
                }
            }],
            'level_description_thai' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Thai}\s]+$/u', $value)) {
                    $fail('The Thai description must contain only Thai script.');
                }
            }],
            'benefits_en' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[A-Za-z0-9\s]+$/', $value)) {
                    $fail('The English benefits must contain only English letters, numbers, and spaces.');
                }
            }],
            'benefits_myan' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Myanmar}\s]+$/u', $value)) {
                    $fail('The Myanmar benefits must contain only Myanmar script.');
                }
            }],
            'benefits_thai' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Thai}\s]+$/u', $value)) {
                    $fail('The Thai benefits must contain only Thai script.');
                }
            }],
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $vendorLevel = VendorLevel::create($request->all());
    
        return response()->json([
            'message' => 'Vendor level created successfully!',
            'data' => $vendorLevel,
        ], 201);
    }
    
    // Show a single vendor level by ID and language
    public function show($id, $lang = 'en')
    {
        $vendorLevel = VendorLevel::find($id);

        if (!$vendorLevel) {
            return response()->json(['message' => 'Vendor level not found'], 404);
        }

        return response()->json([
            'id' => $vendorLevel->id,
            'level' => $vendorLevel->{'level_' . $lang},
            'level_description' => $vendorLevel->{'level_description_' . $lang},
            'benefits' => $vendorLevel->{'benefits_' . $lang},
        ]);
    }

    // Update a vendor level
    public function update(Request $request, $id)
    {
        $vendorLevel = VendorLevel::find($id);
    
        if (!$vendorLevel) {
            return response()->json(['message' => 'Vendor level not found'], 404);
        }
    
        $rules = [
            'level_en' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[A-Za-z0-9\s]+$/', $value)) {
                    $fail('The English level must contain only English letters, numbers, and spaces.');
                }
            }],
            'level_myan' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Myanmar}\s]+$/u', $value)) {
                    $fail('The Myanmar level must contain only Myanmar script.');
                }
            }],
            'level_thai' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Thai}\s]+$/u', $value)) {
                    $fail('The Thai level must contain only Thai script.');
                }
            }],
            'level_description_en' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[A-Za-z0-9\s]+$/', $value)) {
                    $fail('The English description must contain only English letters, numbers, and spaces.');
                }
            }],
            'level_description_myan' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Myanmar}\s]+$/u', $value)) {
                    $fail('The Myanmar description must contain only Myanmar script.');
                }
            }],
            'level_description_thai' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Thai}\s]+$/u', $value)) {
                    $fail('The Thai description must contain only Thai script.');
                }
            }],
            'benefits_en' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[A-Za-z0-9\s]+$/', $value)) {
                    $fail('The English benefits must contain only English letters, numbers, and spaces.');
                }
            }],
            'benefits_myan' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Myanmar}\s]+$/u', $value)) {
                    $fail('The Myanmar benefits must contain only Myanmar script.');
                }
            }],
            'benefits_thai' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!preg_match('/^[\p{Thai}\s]+$/u', $value)) {
                    $fail('The Thai benefits must contain only Thai script.');
                }
            }],
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $vendorLevel->update($request->all());
    
        return response()->json([
            'message' => 'Vendor level updated successfully!',
            'data' => $vendorLevel,
        ]);
    }
    

    // Delete a vendor level
    public function destroy($id)
    {
        $vendorLevel = VendorLevel::find($id);

        if (!$vendorLevel) {
            return response()->json(['message' => 'Vendor level not found'], 404);
        }

        $vendorLevel->delete();

        return response()->json(['message' => 'Vendor level deleted successfully!']);
    }
}
