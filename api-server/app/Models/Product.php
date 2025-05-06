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
     * Get the brand that owns the product.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
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

    /**
     * Get the additional images for this product.
     */
    public function productImages()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }
    
    /**
     * Properly handle product image storage when setting the image_url attribute
     *
     * @param mixed $value
     * @return void
     */
    public function setImageUrlAttribute($value)
    {
        // Log what we're receiving for debugging
        \Illuminate\Support\Facades\Log::info('Setting product image_url', [
            'product_id' => $this->id ?? 'new',
            'value_type' => gettype($value),
            'is_array' => is_array($value),
            'value' => is_scalar($value) ? $value : json_encode($value)
        ]);
        
        // If value is null or empty, clear the image
        if (empty($value) || $value === 'null' || $value === '""') {
            $this->attributes['image_url'] = null;
            return;
        }
        
        // Handle Livewire/Filament temp uploads array
        if (is_array($value)) {
            // If it's a single file in an array, extract it
            if (count($value) === 1) {
                $this->attributes['image_url'] = reset($value);
            } else {
                // First value in the array
                $this->attributes['image_url'] = $value[0] ?? null;
            }
            return;
        }
        
        // For any other value, assign it directly (usually a string with the file path)
        $this->attributes['image_url'] = $value;
    }
}
