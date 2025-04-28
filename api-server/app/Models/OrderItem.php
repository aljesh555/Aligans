<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'unit_price',
        'discounted_price',
        'product_name',
        'subtotal',
        'size',
        'color',
        'variant_details'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'variant_details' => 'array',
    ];

    /**
     * Get the order that owns the order item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product associated with the order item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Calculate the subtotal for this order item.
     */
    public function calculateSubtotal()
    {
        $price = $this->discounted_price ?? $this->price ?? $this->unit_price ?? 0;
        $this->subtotal = $price * $this->quantity;
        return $this->subtotal;
    }
}
