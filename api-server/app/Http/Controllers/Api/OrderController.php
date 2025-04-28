<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            // Log the user and request
            \Log::info('Orders index accessed', [
                'user_id' => $request->user() ? $request->user()->id : 'unauthenticated',
                'user_role' => $request->user() ? $request->user()->role : 'none',
                'query_params' => $request->all()
            ]);
            
            // Get all orders for admins/managers, or just the user's orders for others
            if ($request->user() && ($request->user()->role === 'admin' || $request->user()->role === 'manager')) {
                // Admin/manager can see all orders
                $orders = Order::with(['orderItems', 'orderItems.product', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                \Log::info('Returning all orders for admin/manager', [
                    'count' => $orders->count()
                ]);
            } else {
                // Regular users can only see their own orders
                $orders = Order::where('user_id', $request->user()->id)
                    ->with(['orderItems', 'orderItems.product'])
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                \Log::info('Returning user orders', [
                    'count' => $orders->count()
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to get orders', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get orders: ' . $e->getMessage()
            ], 500);
        }
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
            'branch_id' => 'required|exists:branches,id',
        ]);
        
        $user = $request->user();
        
        // Get the user's cart
        $cart = Cart::with('cartItems.product')
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        // Check if cart has items
        if ($cart->cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Cannot create order with empty cart'
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            // Calculate total amount
            $totalAmount = 0;
            foreach ($cart->cartItems as $item) {
                $totalAmount += $item->product->price * $item->quantity;
            }
            
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'branch_id' => $validatedData['branch_id'],
                'total_amount' => $totalAmount,
                'status' => Order::STATUS_PENDING
            ]);
            
            // Create order items
            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price
                ]);
                
                // Update product stock
                $stock = ProductStock::where('product_id', $item->product_id)
                    ->where('branch_id', $validatedData['branch_id'])
                    ->first();
                    
                if ($stock) {
                    if ($stock->quantity < $item->quantity) {
                        throw new \Exception("Insufficient stock for product ID: {$item->product_id}");
                    }
                    
                    $stock->quantity -= $item->quantity;
                    $stock->save();
                } else {
                    throw new \Exception("No stock found for product ID: {$item->product_id} at branch ID: {$validatedData['branch_id']}");
                }
            }
            
            // Clear the cart
            $cart->cartItems()->delete();
            
            DB::commit();
            
            return response()->json([
                'message' => 'Order created successfully',
                'data' => $order->load('orderItems.product')
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $user = $request->user();
        
        // Admin or manager can see any order, other users can only see their own
        if ($user->role === 'admin' || $user->role === 'manager') {
            $order = Order::with(['user', 'branch', 'orderItems.product'])->findOrFail($id);
        } else {
            $order = Order::with(['branch', 'orderItems.product'])
                ->where('id', $id)
                ->where('user_id', $user->id)
                ->firstOrFail();
        }
        
        return response()->json(['data' => $order]);
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
        // Not used for direct order updates, using updateStatus instead
        return response()->json([
            'message' => 'This endpoint is not used. Use updateStatus instead.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Orders should not be deleted for record-keeping purposes
        return response()->json([
            'message' => 'Order deletion is not allowed. Update the status to cancelled instead.'
        ], 422);
    }
    
    /**
     * Update the status of an order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();
        
        // Only admin or manager can update order status
        if (!($user->role === 'admin' || $user->role === 'manager')) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $validatedData = $request->validate([
            'status' => [
                'required', 
                Rule::in([
                    Order::STATUS_PENDING, 
                    Order::STATUS_PROCESSING, 
                    Order::STATUS_COMPLETED, 
                    Order::STATUS_CANCELLED,
                    Order::STATUS_REFUNDED
                ])
            ],
            'send_notification' => 'boolean',
            'cancellation_reason' => 'string|nullable',
            'custom_reason' => 'string|nullable'
        ]);
        
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $order->status = $validatedData['status'];
        
        // Add notes if cancellation reason provided
        if ($validatedData['status'] === Order::STATUS_CANCELLED && isset($validatedData['cancellation_reason'])) {
            $reason = $validatedData['cancellation_reason'];
            if ($reason === 'other' && isset($validatedData['custom_reason'])) {
                $reason = $validatedData['custom_reason'];
            }
            
            $order->cancellation_reason = $reason;
        }
        
        // If order is being canceled or refunded, restore product stock
        if (($validatedData['status'] === Order::STATUS_CANCELLED || $validatedData['status'] === Order::STATUS_REFUNDED) 
            && ($oldStatus !== Order::STATUS_CANCELLED && $oldStatus !== Order::STATUS_REFUNDED)) {
            
            DB::beginTransaction();
            try {
                // Restore the product stock
                $stockRestored = $order->restoreProductStock();
                
                if (!$stockRestored) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Failed to restore product stock'
                    ], 500);
                }
                
                $order->save();
                DB::commit();
                
                // Log the order status change and stock restoration
                \Log::info('Order status updated and stock restored', [
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $order->status,
                    'updated_by' => $user->id
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                
                \Log::error('Failed to update order status with stock restoration', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
                
                return response()->json([
                    'message' => 'Failed to update order status: ' . $e->getMessage()
                ], 500);
            }
        } else {
            // Regular status update without stock restoration
            $order->save();
            
            // Log the order status change
            \Log::info('Order status updated', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $order->status,
                'updated_by' => $user->id
            ]);
        }
        
        if (isset($validatedData['send_notification']) && $validatedData['send_notification']) {
            // Send notification logic here (email, push, etc.)
            // Currently not implemented
        }
        
        return response()->json([
            'message' => 'Order status updated successfully',
            'data' => $order->fresh()
        ]);
    }
    
    /**
     * Send a confirmation email for an order.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendConfirmation($id, Request $request)
    {
        $user = $request->user();
        
        // Only admin or manager can manually send emails
        if (!($user->role === 'admin' || $user->role === 'manager')) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $order = Order::with(['user', 'orderItems.product', 'shippingForm'])
            ->findOrFail($id);
            
        if (!$order->user || !$order->user->email) {
            return response()->json([
                'message' => 'Order has no associated user email'
            ], 422);
        }
        
        // Here you would typically dispatch an email job
        // For example: Mail::to($order->user->email)->send(new OrderConfirmation($order));
        
        // For now we'll just log it
        \Log::info('Manual order confirmation email triggered for order #' . $order->id . ' to ' . $order->user->email);
        
        return response()->json([
            'message' => 'Confirmation email sent successfully'
        ]);
    }
}
