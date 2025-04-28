<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'button_text',
        'button_link',
        'image',
        'is_active',
        'priority',
        'location',
        'image_url',
        'image_link'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'date',
        'ends_at' => 'date'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $now = now()->format('Y-m-d');
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', $now);
            })
            ->where(function($q) {
                $now = now()->format('Y-m-d');
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', $now);
            });
    }
}
