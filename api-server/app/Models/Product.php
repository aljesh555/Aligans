<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'short_description',
        'price', 
        'category_id', 
        'image_url', 
        'slug',
        'sku',
        'status',
        'stock',
        'featured',
        'on_sale',
        'discount_price',
        'discount_percent',
        'is_new_arrival',
        'sale_ends_at',
        'variants'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'price' => 'float',
        'stock' => 'integer',
        'on_sale' => 'boolean',
        'discount_price' => 'float',
        'discount_percent' => 'integer',
        'is_new_arrival' => 'boolean',
        'sale_ends_at' => 'datetime',
        'variants' => 'array'
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the product stocks for this product.
     */
    public function productStocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    /**
     * Get the cart items containing this product.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the order items containing this product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the reviews for this product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    /**
     * Get the wishlist entries for this product.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
