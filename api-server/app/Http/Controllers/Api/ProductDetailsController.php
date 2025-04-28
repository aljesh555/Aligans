<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProductDetailsController extends Controller
{
    /**
     * Get the details of a product
     */
    public function getProductDetails($id)
    {
        $product = Product::with('category')->find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'details' => $product->details,
                'specifications' => $product->specifications,
                'price' => $product->price,
                'discount_price' => $product->discount_price,
                'discount_percent' => $product->discount_percent,
                'on_sale' => (bool) $product->on_sale,
                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                ] : null,
                'images' => [
                    'main' => $product->image,
                    'secondary' => $product->image_url,
                    'thumbnail' => $product->thumbnail,
                ],
                'featured' => (bool) $product->featured,
                'is_new_arrival' => (bool) $product->is_new_arrival,
                'stock' => $product->stock,
                'status' => $product->status,
                'variants' => $product->variants,
            ]
        ]);
    }
    
    /**
     * Get product specifications
     */
    public function getProductSpecifications($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'specifications' => $product->specifications,
            ]
        ]);
    }
    
    /**
     * Get product reviews
     */
    public function getProductReviews($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        $reviews = Review::where('product_id', $id)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $ratingData = [
            'average' => $reviews->avg('rating') ?: 0,
            'total' => $reviews->count(),
            'distribution' => [
                5 => $reviews->where('rating', 5)->count(),
                4 => $reviews->where('rating', 4)->count(),
                3 => $reviews->where('rating', 3)->count(),
                2 => $reviews->where('rating', 2)->count(),
                1 => $reviews->where('rating', 1)->count(),
            ]
        ];
        
        return response()->json([
            'success' => true,
            'data' => [
                'reviews' => $reviews->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'title' => $review->title,
                        'comment' => $review->comment,
                        'rating' => $review->rating,
                        'reviewer_name' => $review->user_id ? $review->user->name : $review->reviewer_name,
                        'is_verified_purchase' => (bool) $review->is_verified_purchase,
                        'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'rating_summary' => $ratingData
            ]
        ]);
    }
    
    /**
     * Submit a product review
     */
    public function submitReview(Request $request, $id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string',
            'reviewer_name' => 'required_without:user_id|string|max:255',
            'reviewer_email' => 'required_without:user_id|email|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $review = new Review();
        $review->product_id = $product->id;
        
        // If user is authenticated, associate the review with the user
        if (Auth::check()) {
            $review->user_id = Auth::id();
        } else {
            $review->reviewer_name = $request->reviewer_name;
            $review->reviewer_email = $request->reviewer_email;
        }
        
        $review->title = $request->title;
        $review->comment = $request->comment;
        $review->rating = $request->rating;
        
        // If the user has purchased this product, mark as verified
        // This would typically check order history
        $review->is_verified_purchase = false;
        
        $review->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Your review has been submitted successfully.',
            'data' => [
                'id' => $review->id,
                'rating' => $review->rating,
                'title' => $review->title,
            ]
        ]);
    }
}
