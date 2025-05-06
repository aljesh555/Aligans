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
    
    /**
     * Properly handle brand logo storage when setting the logo_url attribute
     *
     * @param mixed $value
     * @return void
     */
    public function setLogoUrlAttribute($value)
    {
        // Log what we're receiving for debugging
        \Illuminate\Support\Facades\Log::info('Setting brand logo_url', [
            'brand_id' => $this->id ?? 'new',
            'value_type' => gettype($value),
            'is_array' => is_array($value),
            'value' => is_scalar($value) ? $value : json_encode($value)
        ]);
        
        // If value is null or empty, clear the logo
        if (empty($value) || $value === 'null' || $value === '""') {
            $this->attributes['logo_url'] = null;
            return;
        }
        
        // Handle Livewire/Filament temp uploads array
        if (is_array($value)) {
            // If it's a single file in an array, extract it
            if (count($value) === 1) {
                $this->attributes['logo_url'] = reset($value);
            } else {
                // First value in the array
                $this->attributes['logo_url'] = $value[0] ?? null;
            }
            return;
        }
        
        // For any other value, assign it directly (usually a string with the file path)
        $this->attributes['logo_url'] = $value;
    }
} 