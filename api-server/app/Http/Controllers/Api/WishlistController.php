<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $wishlistItems = Wishlist::with('product')
            ->where('user_id', $user->id)
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $wishlistItems,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        
        // Check if already in wishlist
        $existing = Wishlist::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();
            
        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Product already in wishlist',
                'data' => $existing,
            ]);
        }
        
        // Add to wishlist
        $wishlistItem = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist',
            'data' => $wishlistItem,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $wishlistItem = Wishlist::with('product')
            ->where('user_id', $user->id)
            ->where('product_id', $id)
            ->first();
            
        if (!$wishlistItem) {
            return response()->json([
                'success' => false,
                'message' => 'Product not in wishlist',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $wishlistItem,
        ]);
    }

    /**
     * Check if a product is in the user's wishlist.
     */
    public function check(string $productId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();
            
        return response()->json([
            'success' => true,
            'in_wishlist' => $exists,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $productId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $deleted = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->delete();
            
        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in wishlist',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist',
        ]);
    }
    
    /**
     * For admin: Get all users with their wishlists
     */
    public function adminIndex()
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $wishlistItems = Wishlist::with(['user', 'product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return response()->json([
            'success' => true,
            'data' => $wishlistItems,
        ]);
    }
    
    /**
     * For admin: Get wishlist statistics
     */
    public function adminStats()
    {
        // Check if user is admin
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        // Most wishlisted products
        $topProducts = Product::withCount('wishlists')
            ->orderBy('wishlists_count', 'desc')
            ->take(10)
            ->get();
            
        // Total wishlist items
        $totalItems = Wishlist::count();
        
        // Users with wishlists
        $usersWithWishlists = Wishlist::select('user_id')
            ->distinct()
            ->count();
            
        return response()->json([
            'success' => true,
            'data' => [
                'top_products' => $topProducts,
                'total_items' => $totalItems,
                'users_with_wishlists' => $usersWithWishlists,
            ]
        ]);
    }
}
