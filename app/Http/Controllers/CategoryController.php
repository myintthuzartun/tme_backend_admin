<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\Category;

class CategoryController extends Controller
{
    //
    function add(Request $req)
    {
        // Validate request
        $req->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'description' => 'nullable|string',
            'commission_rate' => 'nullable|numeric',
            'parent' => 'nullable|exists:categories,id',
            'status' => 'nullable|boolean'
        ]);

        try {
            $category = new Category();
            $category->name = $req->input("name");
            $category->slug = $req->input("slug");
            $category->description = $req->input("description");
            $category->commission_rate = $req->input("commission_rate");
            $category->parent_id = $req->filled("parent") ? $req->input("parent") : null;

            $category->image = $req->hasFile('image') ?
                $req->file('image')->store('categories', 'public') : null;
            $category->icon = $req->hasFile('icon') ?
                $req->file('icon')->store('categories', 'public') : null;

            $category->status = $req->input("status");

            $category->save();

            return response()->json([
                'message' => 'Category added successfully!',
                'category' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error inserting category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    function list()
    {
        $categories = Category::all()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'commission_rate' => $category->commission_rate,
                'parent_id' => $category->parent_id,
                'image' => $category->image ? env('APP_URL') . $category->image : null,
                'icon' => $category->icon ? env('APP_URL') . $category->icon : null,
                'status' => $category->status
            ];
        });

        return response()->json($categories, 200);
    }

    function show($id)
    {
        $category = Category::find($id);

        if ($category) {
            return response()->json([
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'commission_rate' => $category->commission_rate,
                'parent_id' => $category->parent_id,
                'image' => $category->image ? env('APP_URL') . $category->image : null,
                'icon' => $category->icon ? env('APP_URL') . $category->icon : null,
                'status' => $category->status
            ], 200);
        } else {
            return response()->json(['message' => 'Category not found!'], 404);
        }
    }

    function update(Request $req, $id)
    {
        // Validate request
        $req->validate([
            'name' => 'required|string',
            'slug' => 'required|string',
            'description' => 'nullable|string',
            'commission_rate' => 'nullable|numeric',
            'parent' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|boolean'
        ]);

        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json(['message' => 'Category not found!'], 404);
            }

            $category->name = $req->input("name");
            $category->slug = $req->input("slug");
            $category->description = $req->input("description");
            $category->commission_rate = $req->input("commission_rate");
            $category->parent_id = $req->filled("parent") ? $req->input("parent") : null;

            // Handle image upload safely
            if ($req->hasFile('image')) {
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $category->image = $req->file('image')->store('categories', 'public');
            }

            // Handle icon upload safely
            if ($req->hasFile('icon')) {
                if ($category->icon) {
                    Storage::disk('public')->delete($category->icon);
                }
                $category->icon = $req->file('icon')->store('categories', 'public');
            }

            $category->status = $req->input("status");
            $category->save();

            return response()->json([
                'message' => 'Category updated successfully!',
                'category' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction(); // Start transaction

        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json(['message' => 'Category not found!'], 404);
            }

            // Check if category has child categories
            if ($category->children()->exists()) {
                return response()->json(['message' => 'Cannot delete category with subcategories!'], 400);
            }

            // Check if category is referenced in other tables (example: products)
            // if ($category->products()->exists()) {
            //     return response()->json(['message' => 'Cannot delete category with assigned products!'], 400);
            // }

            // Delete image if exists in both DB and storage
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            // Delete icon if exists in both DB and storage
            if ($category->icon && Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }

            $category->delete(); // Delete category from database

            DB::commit(); // Commit transaction

            return response()->json(['message' => 'Category deleted successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback if something fails
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
