<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shipping_form_id',
        'payment_method',
        'status',
        'total_amount'
    ];

    /**
     * The possible order statuses.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    /**
     * The possible payment statuses.
     */
    const PAYMENT_UNPAID = 'unpaid';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_REFUNDED = 'refunded';
    const PAYMENT_FAILED = 'failed';

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shipping form for this order.
     */
    public function shippingForm()
    {
        return $this->belongsTo(ShippingForm::class);
    }

    /**
     * Get the items for the order.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the payment details for the order.
     */
    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }

    /**
     * Check if the order is paid.
     */
    public function isPaid()
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    /**
     * Calculate the order total from items.
     */
    public function calculateTotal()
    {
        $subtotal = $this->orderItems->sum('subtotal');
        $this->subtotal_amount = $subtotal;
        $this->total_amount = $subtotal + $this->shipping_amount + $this->tax_amount - $this->discount_amount;
        return $this->total_amount;
    }

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $timestamp = now()->format('YmdHis');
        $random = rand(100, 999);
        return $prefix . $timestamp . $random;
    }

    /**
     * Restore product stock when an order is canceled or refunded.
     * This returns the ordered quantities back to the product inventory.
     * 
     * @return bool
     */
    public function restoreProductStock()
    {
        try {
            foreach ($this->orderItems as $item) {
                $product = Product::find($item->product_id);
                
                if ($product) {
                    // Increase the stock by the ordered quantity
                    $product->stock += $item->quantity;
                    $product->save();
                    
                    // Log the stock restoration
                    Log::info('Product stock restored', [
                        'order_id' => $this->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'previous_stock' => $product->stock - $item->quantity,
                        'restored_quantity' => $item->quantity,
                        'new_stock' => $product->stock
                    ]);
                } else {
                    Log::warning('Cannot restore stock for missing product', [
                        'order_id' => $this->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity
                    ]);
                }
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to restore product stock', [
                'order_id' => $this->id,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
}
