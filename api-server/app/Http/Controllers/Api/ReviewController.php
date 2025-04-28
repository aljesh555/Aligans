<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews for a product.
     */
    public function index($productId)
    {
        $reviews = Review::where('product_id', $productId)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($reviews);
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // Ensure user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'You must be logged in to submit a review'], 401);
        }

        $user = Auth::user();
        $review = new Review();
        $review->user_id = $user->id;
        $review->product_id = $request->product_id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        
        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reviews', 'public');
            $review->image_path = $path;
        }
        
        $review->save();
        
        // Load the user relationship for response
        $review->load('user:id,name');
        
        return response()->json($review, 201);
    }

    /**
     * Display the specified review.
     */
    public function show(string $id)
    {
        $review = Review::with('user:id,name')->findOrFail($id);
        return response()->json($review);
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, string $id)
    {
        $review = Review::findOrFail($id);
        
        // Check if user owns this review
        if (!Auth::check() || Auth::id() !== $review->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'sometimes|required|string|min:10',
            'image' => 'nullable|image|max:2048', // Max 2MB
        ]);
        
        // Update review data
        if ($request->has('rating')) {
            $review->rating = $request->rating;
        }
        
        if ($request->has('comment')) {
            $review->comment = $request->comment;
        }
        
        // Handle image update if provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($review->image_path) {
                Storage::disk('public')->delete($review->image_path);
            }
            
            // Store new image
            $path = $request->file('image')->store('reviews', 'public');
            $review->image_path = $path;
        }
        
        $review->save();
        
        return response()->json($review);
    }

    /**
     * Remove the specified review.
     */
    public function destroy(string $id)
    {
        $review = Review::findOrFail($id);
        
        // Check if user owns this review or is admin
        if (!Auth::check() || (Auth::id() !== $review->user_id && !Auth::user()->isAdmin())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Delete image if exists
        if ($review->image_path) {
            Storage::disk('public')->delete($review->image_path);
        }
        
        $review->delete();
        
        return response()->json(null, 204);
    }
    
    /**
     * Get reviews for the authenticated user.
     */
    public function userReviews()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $reviews = Review::where('user_id', Auth::id())
            ->with(['product:id,name,image_url'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($reviews);
    }
}
