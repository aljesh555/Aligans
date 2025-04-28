<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'parent_id', 
        'image_url', 
        'slug',
        'thumbnail_image',
        'banner_image',
        'description',
        'status'
    ];

    /**
     * Get the products for this category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    
    /**
     * Get the subcategories of this category.
     */
    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    
    /**
     * Check if the category is a parent category (has subcategories).
     */
    public function isParent()
    {
        return $this->subcategories()->count() > 0;
    }
    
    /**
     * Check if the category is a subcategory (has a parent).
     */
    public function isSubcategory()
    {
        return $this->parent_id !== null;
    }
}
