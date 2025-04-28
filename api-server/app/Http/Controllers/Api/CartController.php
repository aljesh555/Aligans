<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $cart = Cart::with(['cartItems.product'])
            ->where('user_id', $userId)
            ->first();

        if (!$cart) {
            $cart = Cart::create(['user_id' => $userId]);
        }

        return response()->json(['data' => $cart]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = $request->user()->id;
        
        // Check if the user already has a cart
        $cart = Cart::where('user_id', $userId)->first();
        
        if ($cart) {
            return response()->json([
                'message' => 'User already has a cart',
                'data' => $cart
            ], 422);
        }
        
        $cart = Cart::create(['user_id' => $userId]);
        
        return response()->json([
            'message' => 'Cart created successfully',
            'data' => $cart
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $userId = $request->user()->id;
        $cart = Cart::with(['cartItems.product'])
            ->where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
            
        return response()->json(['data' => $cart]);
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
        // Not needed for Cart as there are no direct properties to update
        return response()->json([
            'message' => 'This endpoint is not used. Use addItem or removeItem instead.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $userId = $request->user()->id;
        $cart = Cart::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
            
        // Delete all cart items first
        $cart->cartItems()->delete();
        $cart->delete();
        
        return response()->json([
            'message' => 'Cart deleted successfully'
        ]);
    }
    
    /**
     * Add an item to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addItem(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $userId = $request->user()->id;
        
        // Get or create the user's cart
        $cart = Cart::where('user_id', $userId)->first();
        if (!$cart) {
            $cart = Cart::create(['user_id' => $userId]);
        }
        
        // Check if the product already exists in the cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $validatedData['product_id'])
            ->first();
            
        if ($cartItem) {
            // Update quantity
            $cartItem->quantity += $validatedData['quantity'];
            $cartItem->save();
        } else {
            // Create new cart item
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $validatedData['product_id'],
                'quantity' => $validatedData['quantity']
            ]);
        }
        
        return response()->json([
            'message' => 'Item added to cart successfully',
            'data' => $cart->load('cartItems.product')
        ]);
    }
    
    /**
     * Remove an item from the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeItem(Request $request)
    {
        $validatedData = $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);
        
        $userId = $request->user()->id;
        
        // Get the user's cart
        $cart = Cart::where('user_id', $userId)->firstOrFail();
        
        // Find the cart item and ensure it belongs to this cart
        $cartItem = CartItem::where('id', $validatedData['cart_item_id'])
            ->where('cart_id', $cart->id)
            ->firstOrFail();
            
        $cartItem->delete();
        
        return response()->json([
            'message' => 'Item removed from cart successfully',
            'data' => $cart->load('cartItems.product')
        ]);
    }
}
