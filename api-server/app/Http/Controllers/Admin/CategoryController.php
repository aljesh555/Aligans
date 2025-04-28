<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Category::query()->with('parent')->withCount('products');
        
        // Search functionality
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('slug', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
        }
        
        // Pagination
        $perPage = $request->input('per_page', 10);
        $categories = $query->latest()->paginate($perPage);
        
        return response()->json([
            'data' => $categories->items(),
            'meta' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total()
            ]
        ]);
    }

    /**
     * Store a newly created category in storage.
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
            'image_url' => 'nullable|string',
            'thumbnail_image' => 'nullable|string',
            'banner_image' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive'
        ]);
        
        // Generate slug if not provided
        if (empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        } else {
            $validatedData['slug'] = Str::slug($validatedData['slug']);
        }
        
        // Set default status if not provided
        if (!isset($validatedData['status'])) {
            $validatedData['status'] = 'active';
        }
        
        $category = Category::create($validatedData);
        
        // Load relationships for response
        $category->load('parent');
        $category->loadCount('products');
        
        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::with(['parent', 'subcategories'])
            ->withCount('products')
            ->findOrFail($id);
            
        return response()->json([
            'data' => $category
        ]);
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $id,
            'parent_id' => 'nullable|exists:categories,id',
            'image_url' => 'nullable|string',
            'thumbnail_image' => 'nullable|string',
            'banner_image' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive'
        ]);
        
        // Generate slug if not provided but name is changed
        if (empty($validatedData['slug']) && isset($validatedData['name'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        } elseif (isset($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['slug']);
        }
        
        // Prevent category from being its own parent
        if (isset($validatedData['parent_id']) && $validatedData['parent_id'] == $id) {
            return response()->json([
                'message' => 'Category cannot be its own parent',
                'errors' => [
                    'parent_id' => ['Category cannot be its own parent']
                ]
            ], 422);
        }
        
        // Check for circular reference
        if (isset($validatedData['parent_id'])) {
            $descendantIds = $this->getDescendantIds($id);
            if (in_array($validatedData['parent_id'], $descendantIds)) {
                return response()->json([
                    'message' => 'Cannot set a descendant category as parent',
                    'errors' => [
                        'parent_id' => ['Cannot set a descendant category as parent']
                    ]
                ], 422);
            }
        }
        
        $category->update($validatedData);
        
        // Load relationships for response
        $category->load('parent');
        $category->loadCount('products');
        
        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::withCount(['products', 'subcategories'])->findOrFail($id);
        
        // Check for associated products
        if ($category->products_count > 0) {
            return response()->json([
                'message' => 'Cannot delete a category with associated products',
                'errors' => [
                    'general' => ['This category has products associated with it. Please remove them first.']
                ]
            ], 422);
        }
        
        // Check for subcategories
        if ($category->subcategories_count > 0) {
            return response()->json([
                'message' => 'Cannot delete a category with subcategories',
                'errors' => [
                    'general' => ['This category has subcategories. Please remove or reassign them first.']
                ]
            ], 422);
        }
        
        $category->delete();
        
        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
    
    /**
     * Get all parent categories for dropdown selection.
     *
     * @return \Illuminate\Http\Response
     */
    public function getParentCategories()
    {
        $categories = Category::where('parent_id', null)
            ->orderBy('name')
            ->get(['id', 'name', 'image_url']);
            
        return response()->json([
            'data' => $categories
        ]);
    }
    
    /**
     * Get all subcategories for a specific category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSubcategories($id)
    {
        $category = Category::findOrFail($id);
        $subcategories = $category->subcategories()
            ->withCount('products')
            ->orderBy('name')
            ->get();
            
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
    private function getDescendantIds($categoryId)
    {
        $ids = [];
        $subcategories = Category::where('parent_id', $categoryId)->get();
        
        foreach ($subcategories as $subcategory) {
            $ids[] = $subcategory->id;
            $childIds = $this->getDescendantIds($subcategory->id);
            $ids = array_merge($ids, $childIds);
        }
        
        return $ids;
    }
} 