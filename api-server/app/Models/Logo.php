<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image_path',
        'is_active',
        'base64_image'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the full URL for the image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return url($this->image_path);
        }
        return null;
    }

    /**
     * Scope a query to only include active logos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * When activating a logo, deactivate all others
     */
    public static function setActive($id)
    {
        // First deactivate all logos
        self::query()->update(['is_active' => false]);
        
        // Then activate the specified one
        $logo = self::findOrFail($id);
        $logo->update(['is_active' => true]);
        
        return $logo;
    }
} 