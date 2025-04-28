<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    /**
     * Process the checkout and create an order.
     */
    public function processCheckout(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string|in:cod,esewa,khalti,phonepay,credit_card,paypal',
            'shipping_form_id' => 'required|exists:shipping_forms,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.size' => 'nullable|string',
            'items.*.color' => 'nullable|string',
            'items.*.variant_details' => 'nullable|array',
            'total_amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the authenticated user
        $user = $request->user();
        if (!$user) {
            // TEMPORARY: For testing, use a default user instead of requiring authentication
            $user = User::find(1); // Use the first user in the database
            
            if (!$user) {
                Log::error('No default user found for guest checkout');
                return response()->json([
                    'success' => false,
                    'message' => 'Could not find a default user for guest checkout'
                ], 500);
            }
            
            Log::info('Using default user for guest checkout', ['user_id' => $user->id]);
        }

        try {
            DB::beginTransaction();

            // First, check if all products exist and verify their status
            $outOfStockProducts = [];
            $insufficientStockProducts = [];
            $inactiveProducts = [];

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                
                if (!$product) {
                    throw new \Exception("Product not found: {$item['product_id']}");
                }
                
                // Check if product is active
                if ($product->status !== 'active') {
                    $inactiveProducts[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'status' => $product->status
                    ];
                    continue;
                }

                // Check if product has any stock at all
                if ($product->stock <= 0) {
                    $outOfStockProducts[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'stock' => $product->stock
                    ];
                    continue;
                }
                
                // Check if sufficient stock is available
                if ($product->stock < $item['quantity']) {
                    $insufficientStockProducts[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'requested' => $item['quantity'],
                        'available' => $product->stock
                    ];
                }
            }

            // Return appropriate error messages for validation issues
            if (!empty($inactiveProducts)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart contains products that are no longer available',
                    'inactive_products' => $inactiveProducts
                ], 422);
            }

            if (!empty($outOfStockProducts)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart contains products that are out of stock',
                    'out_of_stock_products' => $outOfStockProducts
                ], 422);
            }

            if (!empty($insufficientStockProducts)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart contains products with insufficient stock',
                    'insufficient_stock_products' => $insufficientStockProducts
                ], 422);
            }

            // Create the simplified order
            $order = new Order();
            $order->user_id = $user->id;
            $order->shipping_form_id = $request->shipping_form_id;
            $order->payment_method = $request->payment_method;
            $order->status = 'pending';
            $order->total_amount = $request->total_amount;
            $order->save();
            
            // Create simplified order items
            $orderItems = [];
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                
                // Double-check stock one more time to handle race conditions
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock changed during checkout for product: {$product->name}");
                }
                
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->product_name = $product->name;
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $product->price;
                $orderItem->discounted_price = $product->sale_price ?? null;
                
                // Store variant information
                $orderItem->size = $item['size'] ?? null;
                $orderItem->color = $item['color'] ?? null;
                $orderItem->variant_details = $item['variant_details'] ?? null;
                
                $orderItem->save();
                $orderItems[] = $orderItem;
                
                // Update product stock - SUBTRACT the ordered quantity
                $product->stock -= $item['quantity'];
                
                // If stock is now 0, optionally update the status to out_of_stock
                if ($product->stock <= 0) {
                    $product->stock = 0; // Ensure it doesn't go negative
                    $product->status = 'out_of_stock';
                }
                
                $product->save();
                
                // Log the stock update
                Log::info('Product stock updated', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'previous_stock' => $product->stock + $item['quantity'],
                    'ordered_quantity' => $item['quantity'],
                    'new_stock' => $product->stock,
                    'order_id' => $order->id
                ]);
            }
            
            // Create simplified payment details
            $paymentDetail = new PaymentDetail();
            $paymentDetail->order_id = $order->id;
            $paymentDetail->payment_gateway = $request->payment_method;
            $paymentDetail->amount = $order->total_amount;
            $paymentDetail->payment_status = 'unpaid';
            $paymentDetail->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => [
                    'order' => $order,
                    'order_items' => $orderItems,
                ]
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            Log::error('Checkout failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'order_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Verify and process eSewa payment.
     */
    public function verifyEsewaPayment(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'transaction_id' => 'required|string',
            'refId' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            // Get the order
            $order = Order::findOrFail($request->order_id);
            
            // Get payment detail
            $paymentDetail = PaymentDetail::where('order_id', $order->id)
                ->where('payment_method', 'esewa')
                ->where('status', PaymentDetail::STATUS_UNPAID)
                ->first();
                
            if (!$paymentDetail) {
                throw new \Exception("No unpaid eSewa payment found for this order");
            }
            
            // Verify the payment with eSewa (in a real implementation)
            // This is a mock implementation - in a real system you'd make an API call to eSewa to verify
            
            // For this demo, we'll assume the payment was successful
            $paymentDetail->transaction_id = $request->transaction_id;
            $paymentDetail->gateway_response = [
                'refId' => $request->refId,
                'amount' => $request->amount,
                'status' => 'success',
                'verified_at' => now()->toIso8601String()
            ];
            
            // Mark the payment as completed
            $paymentDetail->markAsSuccessful();
            
            // Update the order status
            $order->status = Order::STATUS_PROCESSING;
            $order->payment_status = Order::PAYMENT_PAID;
            $order->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully',
                'data' => [
                    'order' => $order,
                    'payment' => $paymentDetail
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            Log::error('eSewa payment verification failed: ' . $e->getMessage(), [
                'order_id' => $request->order_id,
                'transaction_id' => $request->transaction_id,
                'refId' => $request->refId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify payment: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get order details.
     */
    public function getOrderDetails($orderId, Request $request)
    {
        try {
            $user = $request->user();
            
            $order = Order::with(['orderItems', 'paymentDetails'])
                ->where('id', $orderId)
                ->where('user_id', $user->id)
                ->firstOrFail();
                
            return response()->json([
                'success' => true,
                'data' => $order
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
    }
    
    /**
     * Get user's orders.
     */
    public function getUserOrders(Request $request)
    {
        $user = $request->user();
        
        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
}
