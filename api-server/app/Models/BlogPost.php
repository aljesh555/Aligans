<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'status',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Scope a query to only include published posts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now())
                    ->orderBy('published_at', 'desc');
    }

    /**
     * Generate a slug from the title.
     *
     * @param  string  $title
     * @return string
     */
    public static function createSlug($title)
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $count = 1;

        // Check if the slug already exists
        while (static::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Properly handle featured image storage when setting the featured_image attribute
     *
     * @param mixed $value
     * @return void
     */
    public function setFeaturedImageAttribute($value)
    {
        // Log what we're receiving for debugging
        \Illuminate\Support\Facades\Log::info('Setting blog post featured_image', [
            'post_id' => $this->id ?? 'new',
            'value_type' => gettype($value),
            'is_array' => is_array($value),
            'value' => is_scalar($value) ? $value : json_encode($value)
        ]);
        
        // If value is null or empty, clear the featured image
        if (empty($value) || $value === 'null' || $value === '""') {
            $this->attributes['featured_image'] = null;
            return;
        }
        
        // Handle Livewire/Filament temp uploads array
        if (is_array($value)) {
            // If it's a single file in an array, extract it
            if (count($value) === 1) {
                $this->attributes['featured_image'] = reset($value);
            } else {
                // First value in the array
                $this->attributes['featured_image'] = $value[0] ?? null;
            }
            return;
        }
        
        // For any other value, assign it directly (usually a string with the file path)
        $this->attributes['featured_image'] = $value;
    }
} 