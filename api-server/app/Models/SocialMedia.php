<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;

    protected $table = 'social_media';
    
    protected $fillable = [
        'platform',
        'name',
        'url',
        'icon',
        'is_active',
        'sort_order'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];
    
    // Scope to get only active social media links
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // Scope to get links ordered by sort_order
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
} 