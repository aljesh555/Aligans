<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        
        return response()->json([
            'data' => $products,
            'meta' => [
                'total' => $products->count()
            ]
        ]);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'sku' => 'required|string|max:50|unique:products,sku',
            'description' => 'required|string',
            'short_description' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'on_sale' => 'boolean',
            'discount_price' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'sale_ends_at' => 'nullable|date',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048', // 2MB max
            'variants' => 'nullable|array',
        ]);

        // Set default values for optional fields
        if (!isset($validatedData['discount_price'])) {
            $validatedData['discount_price'] = null;
        }
        if (!isset($validatedData['discount_percent'])) {
            $validatedData['discount_percent'] = null;
        }
        if (!isset($validatedData['sale_ends_at'])) {
            $validatedData['sale_ends_at'] = null;
        }

        // Create product first without images
        $product = new Product([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'sku' => $validatedData['sku'],
            'description' => $validatedData['description'],
            'short_description' => $validatedData['short_description'],
            'price' => $validatedData['price'],
            'category_id' => $validatedData['category_id'],
            'stock' => $validatedData['stock'],
            'status' => $validatedData['status'],
            'featured' => $request->input('featured', false),
            'is_new_arrival' => $request->input('is_new_arrival', false),
            'on_sale' => $request->input('on_sale', false),
            'discount_price' => $validatedData['discount_price'],
            'discount_percent' => $validatedData['discount_percent'],
            'sale_ends_at' => $validatedData['sale_ends_at'],
        ]);

        $product->save();

        // Handle image uploads
        if ($request->hasFile('images')) {
            $primaryImageIndex = $request->input('primary_image_index', 0);
            $images = $request->file('images');
            
            foreach ($images as $index => $image) {
                $imageName = Str::slug($product->name) . '-' . time() . '-' . $index . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/products', $imageName);
                $imageUrl = 'storage/products/' . $imageName;
                
                // If this is the primary image, update the product's main image
                if ($index == $primaryImageIndex) {
                    $product->image_url = $imageUrl;
                    $product->save();
                }
                
                // Save image in product_images table if you have one
                // $product->images()->create([
                //     'url' => $imageUrl,
                //     'is_primary' => ($index == $primaryImageIndex)
                // ]);
            }
        }

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        
        return response()->json([
            'data' => $product
        ]);
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:products,slug,' . $id,
            'sku' => 'sometimes|required|string|max:50|unique:products,sku,' . $id,
            'description' => 'sometimes|required|string',
            'short_description' => 'sometimes|required|string|max:500',
            'price' => 'sometimes|required|numeric|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
            'stock' => 'sometimes|required|integer|min:0',
            'status' => 'sometimes|required|in:active,inactive',
            'featured' => 'sometimes|boolean',
            'is_new_arrival' => 'sometimes|boolean',
            'on_sale' => 'sometimes|boolean',
            'discount_price' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'sale_ends_at' => 'nullable|date',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048', // 2MB max
            'variants' => 'nullable|array',
        ]);

        // Ensure nullable fields have default values
        if (!isset($validatedData['discount_price'])) {
            $validatedData['discount_price'] = null;
        }
        if (!isset($validatedData['discount_percent'])) {
            $validatedData['discount_percent'] = null;
        }
        if (!isset($validatedData['sale_ends_at'])) {
            $validatedData['sale_ends_at'] = null;
        }

        // Update product data first
        $product->update($validatedData);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $primaryImageIndex = $request->input('primary_image_index', 0);
            $images = $request->file('images');
            
            foreach ($images as $index => $image) {
                $imageName = Str::slug($product->name) . '-' . time() . '-' . $index . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/products', $imageName);
                $imageUrl = 'storage/products/' . $imageName;
                
                // If this is the primary image, update the product's main image
                if ($index == $primaryImageIndex) {
                    // Remove old primary image if it exists
                    if ($product->image_url && Storage::exists(str_replace('storage/', 'public/', $product->image_url))) {
                        Storage::delete(str_replace('storage/', 'public/', $product->image_url));
                    }
                    
                    $product->image_url = $imageUrl;
                    $product->save();
                }
                
                // Save image in product_images table if you have one
                // $product->images()->create([
                //     'url' => $imageUrl,
                //     'is_primary' => ($index == $primaryImageIndex)
                // ]);
            }
        }

        // Handle existing images (if you have product_images table)
        // if ($request->has('existing_images')) {
        //     // Update primary image if needed
        //     if ($request->has('primary_existing_image')) {
        //         $primaryImageId = $request->input('primary_existing_image');
        //         $product->images()->update(['is_primary' => false]);
        //         $product->images()->where('id', $primaryImageId)->update(['is_primary' => true]);
        //         
        //         // Also update the main product image_url
        //         $primaryImage = $product->images()->where('id', $primaryImageId)->first();
        //         if ($primaryImage) {
        //             $product->image_url = $primaryImage->url;
        //             $product->save();
        //         }
        //     }
        // }

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Delete product image if exists
        if ($product->image_url && Storage::exists(str_replace('storage/', 'public/', $product->image_url))) {
            Storage::delete(str_replace('storage/', 'public/', $product->image_url));
        }
        
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }
}
