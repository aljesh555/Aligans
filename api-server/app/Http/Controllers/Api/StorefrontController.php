<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Event;
use App\Models\Team;
use App\Models\Player;

class StorefrontController extends Controller
{
    /**
     * Get featured items for the storefront homepage
     *
     * @return \Illuminate\Http\Response
     */
    public function getHomepage()
    {
        return response()->json([
            'featuredProducts' => Product::where('featured', true)->orderBy('id', 'desc')->take(8)->get(),
            'categories' => Category::whereNull('parent_id')->take(6)->get(),
            'upcomingEvents' => Event::where('date', '>=', now())->orderBy('date')->take(3)->get(),
            'teams' => Team::with('players')->take(3)->get(),
        ]);
    }

    /**
     * Get all products with optional filtering
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProducts(Request $request)
    {
        $query = Product::query();
        
        if ($request->has('category_id')) {
            $categoryId = $request->category_id;
            $category = Category::findOrFail($categoryId);
            
            // If this is a parent category with subcategories, include products from subcategories
            $subcategoryIds = $category->subcategories()->pluck('id')->toArray();
            if (!empty($subcategoryIds)) {
                $query->whereIn('category_id', array_merge([$categoryId], $subcategoryIds));
            } else {
                $query->where('category_id', $categoryId);
            }
        }
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        $products = $query->paginate(12);
        
        return response()->json([
            'products' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ]
        ]);
    }

    /**
     * Get all product categories
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategories()
    {
        $categories = Category::with('subcategories')
            ->withCount('products')
            ->whereNull('parent_id')
            ->get();
            
        return response()->json([
            'data' => $categories
        ]);
    }

    /**
     * Get upcoming events
     *
     * @return \Illuminate\Http\Response
     */
    public function getEvents()
    {
        $events = Event::where('date', '>=', now())
                    ->orderBy('date')
                    ->paginate(10);
                    
        return response()->json([
            'events' => $events->items(),
            'pagination' => [
                'total' => $events->total(),
                'per_page' => $events->perPage(),
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
            ]
        ]);
    }

    /**
     * Get all teams with their players
     *
     * @return \Illuminate\Http\Response
     */
    public function getTeams()
    {
        return response()->json([
            'teams' => Team::with('players')->get()
        ]);
    }

    /**
     * Get single team with its players
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getTeam($id)
    {
        return response()->json([
            'team' => Team::with('players')->findOrFail($id)
        ]);
    }

    /**
     * Get featured products
     *
     * @return \Illuminate\Http\Response
     */
    public function getFeaturedProducts()
    {
        return response()->json([
            'featuredProducts' => Product::where('featured', true)
                ->orderBy('id', 'desc')
                ->get()
        ]);
    }

    /**
     * Get on-sale products
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSaleProducts(Request $request)
    {
        $query = Product::where('on_sale', true);
        
        if ($request->has('category_id')) {
            $categoryId = $request->category_id;
            $query->where('category_id', $categoryId);
        }
        
        // Sort by discount percentage in descending order by default
        $sortBy = $request->get('sort_by', 'discount_percent');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $query->orderBy($sortBy, $sortDirection);
        
        $products = $query->paginate(12);
        
        return response()->json([
            'products' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
            ]
        ]);
    }
} 