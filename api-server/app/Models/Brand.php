<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo_url',
        'description',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from name if not provided
        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Count products in this brand
    public function getProductCountAttribute()
    {
        return $this->products()->count();
    }
} 