<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('subcategories')->whereNull('parent_id')->get();
        return response()->json(['data' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'slug' => 'nullable|string|max:255|unique:categories',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive'
        ]);

        // Generate slug if not provided
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }

        $category = Category::create($validatedData);

        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::with(['products', 'subcategories'])->findOrFail($id);
        return response()->json(['data' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $id,
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive'
        ]);

        // Generate slug if not provided
        if (empty($validatedData['slug']) && isset($validatedData['name'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }

        // Prevent category from being its own parent or child
        if (isset($validatedData['parent_id'])) {
            if ($validatedData['parent_id'] == $id) {
                return response()->json([
                    'message' => 'Category cannot be its own parent'
                ], 422);
            }
            
            // Check if the new parent is not one of this category's descendants
            $descendants = $this->getAllDescendantIds($id);
            if (in_array($validatedData['parent_id'], $descendants)) {
                return response()->json([
                    'message' => 'Cannot set a descendant category as parent'
                ], 422);
            }
        }

        $category->update($validatedData);

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with associated products'
            ], 422);
        }
        
        // Check if category has subcategories
        if ($category->subcategories()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with subcategories'
            ], 422);
        }
        
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
    
    /**
     * Get all subcategories for a category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSubcategories($id)
    {
        $category = Category::findOrFail($id);
        $subcategories = $category->subcategories()->with('products')->get();
        
        return response()->json([
            'data' => $subcategories
        ]);
    }
    
    /**
     * Get all descendant category IDs for a given category.
     *
     * @param  int  $categoryId
     * @return array
     */
    private function getAllDescendantIds($categoryId)
    {
        $ids = [];
        $subcategories = Category::where('parent_id', $categoryId)->get();
        
        foreach ($subcategories as $subcategory) {
            $ids[] = $subcategory->id;
            $childIds = $this->getAllDescendantIds($subcategory->id);
            $ids = array_merge($ids, $childIds);
        }
        
        return $ids;
    }
}
