<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'platform',
        'name',
        'url',
        'icon',
        'is_active',
        'sort_order'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];
    
    /**
     * Scope a query to only include active social media links.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope a query to order social media links by sort_order.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
} 