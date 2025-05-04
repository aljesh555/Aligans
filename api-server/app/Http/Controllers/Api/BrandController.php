<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of all brands.
     */
    public function index()
    {
        $brands = Brand::orderBy('sort_order')->orderBy('name')->get();
        
        // Log the response for debugging
        \Log::info('API: Returning all brands', ['count' => $brands->count()]);
        
        return response()->json([
            'status' => 'success',
            'data' => $brands
        ]);
    }

    /**
     * Display only featured brands.
     */
    public function featured()
    {
        $brands = Brand::where('is_featured', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        // Log the response for debugging
        \Log::info('API: Returning featured brands', ['count' => $brands->count()]);
        
        return response()->json([
            'status' => 'success',
            'data' => $brands
        ]);
    }

    /**
     * Display the specified brand and its products.
     */
    public function show($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();
        
        // Get products paginated
        $products = Product::where('brand_id', $brand->id)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        return response()->json([
            'status' => 'success',
            'brand' => $brand,
            'products' => $products
        ]);
    }
} 