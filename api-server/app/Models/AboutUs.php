<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'about_us';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'banner_image',
        'content',
        'mission',
        'vision',
        'values',
        'team',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'team' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the active about us entry.
     *
     * @return \App\Models\AboutUs|null
     */
    public static function getActive()
    {
        return self::where('is_active', true)->latest()->first();
    }
} 