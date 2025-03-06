<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\Product;
use App\Models\ProductFile;
use App\Models\Inventory;
use App\Models\StockMovement;
use App\Models\ProductShipping;

class ProductController extends Controller
{
    //
    public function add(Request $req)
    {
        try {
            // Validate request
            $validatedData = $req->validate([
                'selectedStore' => 'required|exists:stores,id',
                'productName' => 'required|string|max:255',
                'slug' => 'required|string|unique:products,slug',
                'shortDescription' => 'required|string',
                'description' => 'required|string',
                'selectedTax' => 'required|exists:taxes,id',

                'sku' => 'required|string|unique:products,sku',
                'price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100',
                'salePrice' => 'required|numeric|min:0',

                'selectedCategory' => 'required|exists:categories,id',
                'unit' => 'required|string|max:10',
                'weight' => 'nullable|numeric|min:0',
                'dimension' => 'nullable|string|max:50',
                'selectedBrand' => 'nullable|exists:brands,id',
                'selectedVendor' => 'nullable|exists:vendors,id',

                'status' => 'required|boolean',
                'socialShare' => 'required|boolean',

                'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg|max:5120', // Max 5MB
                'imageFile.*' => 'required|file|mimes:jpeg,png,jpg|max:51200', // Max 50MB per image
                'videoFile.*' => 'required|file|mimes:mp4,avi|max:51200', // Max 50MB per video

                'selectedWarehouse' => 'required|exists:warehouses,id',
                'stockQty' => 'required|integer|min:0',

                'freeShipping' => 'required|boolean',
                'shippingCost' => 'nullable|numeric|min:0',
                'deliveryText' => 'nullable|string|max:255',
                'acceptReturn' => 'required|boolean',
                'returnPolicyText' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors
            Log::error('Validation failed', $e->errors());

            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Insert into products table
            $product = Product::create([
                'name' => $validatedData['productName'],
                'slug' => $validatedData['slug'],
                'sku' => $validatedData['sku'],
                'short_description' => $validatedData['shortDescription'],
                'description' => $validatedData['description'],
                'price' => $validatedData['price'],
                'discount' => $validatedData['discount'] ?? 0.00,
                'sale_price' => $validatedData['salePrice'],
                'category_id' => $validatedData['selectedCategory'],
                'unit' => $validatedData['unit'],
                'weight' => $validatedData['weight'] ?? null,
                'dimension' => $validatedData['dimension'] ?? null,
                'store_id' => $validatedData['selectedStore'],
                'brand_id' => $validatedData['selectedBrand'] ?? null,
                'vendor_id' => $validatedData['selectedVendor'] ?? null,
                'tax_id' => $validatedData['selectedTax'],
                'status' => $validatedData['status'],
                'social_share' => $validatedData['socialShare'],
            ]);

            // Define configurations for different file types
            $uploadConfigs = [
                'thumbnail' => ['folder' => 'products/thumbnails', 'type' => 'Thumbnail'],
                'imageFile' => ['folder' => 'products/images', 'type' => 'Image'],
                'videoFile' => ['folder' => 'products/videos', 'type' => 'Video'],
            ];

            // Iterate over each file type configuration
            foreach ($uploadConfigs as $field => $config) {
                // Check if the request contains files for this field
                if ($req->hasFile($field)) {
                    Log::info("Files detected for: $field");

                    // Get the files (handle both single and multiple uploads)
                    $files = is_array($req->file($field)) ? $req->file($field) : [$req->file($field)];

                    foreach ($files as $file) {
                        // Generate a unique filename using a unique ID and original extension
                        $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                        // Store the file in the respective folder within 'public/storage/products/...'
                        $filePath = $file->storeAs($config['folder'], $filename, 'public');
                        if (!$filePath) {
                            Log::error("File storage failed: $filename");
                        } else {
                            Log::info("File stored: $filePath");
                        }

                        // Save file details into the database
                        ProductFile::create([
                            'product_id' => $product->id, // Associate file with a product
                            'type' => $config['type'], // Store file type (Thumbnail, Image, or Video)
                            'file_path' => $filePath, // Save storage path of the file
                            'file_ext' => $file->getClientOriginalExtension() ?? 'unknown', // Store file extension
                        ]);
                    }
                } else {
                    Log::warning("No files detected for: $field");
                }
            }

            // Insert into inventories
            $inventory = Inventory::create([
                'product_id' => $product->id,
                'warehouse_id' => $validatedData['selectedWarehouse'],
                'stock' => $validatedData['stockQty'],
                'status' => $validatedData['stockQty'] > 0 ? 'In Stock' : 'Out of Stock',
            ]);

            // Insert into stock_movements
            StockMovement::create([
                'inventory_id' => $inventory->id,
                'sku' => $product->sku,
                'product_name' => $product->name,
                'user_id' => '1', // Get logged-in user ID: Auth::id()
                'user_name' => 'Myat Thu Kha', // Get logged-in user name: Auth::user()->name ?? 'System'
                'previous_stock' => 0,
                'change' => '+' . $validatedData['stockQty'], // Proper string concatenation
                'new_stock' => $validatedData['stockQty'],
                'type' => 'New',
            ]);

            // Insert into product_shipping
            ProductShipping::create([
                'product_id' => $product->id,
                'free_shipping' => $validatedData['freeShipping'],
                'shipping_cost' => $validatedData['shippingCost'] ?? 0,
                'estimated_delivery' => $validatedData['deliveryText'] ?? '',
                'return' => $validatedData['acceptReturn'],
                'return_policy' => $validatedData['returnPolicyText'] ?? '',
            ]);

            DB::commit();

            return response()->json(['message' => 'Product added successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function list()
    {
        $products = Product::with(['files', 'inventories', 'store'])->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'sku' => $product->sku,
                'name' => $product->name,
                'price' => $product->price,
                'status' => $product->status,
                'created_at' => $product->created_at,
                'store_name' => $product->store->name ?? null, // Fetch store name
                'stock_status' => optional($product->inventories->first())->status ?? 'Unknown', // Fetch stock status
                'thumbnail' => env('APP_URL') . optional($product->files->where('type', 'Thumbnail')->first())->file_path ?? null, // Get first thumbnail
            ];
        });

        return response()->json($products, 200);
    }

    public function show($id) {}

    public function update(Request $req, $id) {}

    public function delete($id) {}

    public function updateStatus(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->status = $request->status; // Toggle status
        $product->save();

        return response()->json(['message' => 'Status updated successfully'], 200);
    }
}
