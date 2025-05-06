<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'json',
    ];

    /**
     * Set the value attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setValueAttribute($value)
    {
        // For image type, ensure the value is properly handled
        if (isset($this->attributes['type']) && $this->attributes['type'] === 'image') {
            // If empty or null, store as null
            if (empty($value) || $value === 'null' || $value === '""') {
                $this->attributes['value'] = null;
                return;
            }
            
            // If already a JSON string, validate it and store as is
            if (is_string($value) && $this->isJson($value)) {
                $decoded = json_decode($value, true);
                // If valid decoded JSON that's not empty, store as is
                if (!empty($decoded)) {
                    $this->attributes['value'] = $value;
                } else {
                    // Invalid or empty JSON, store as null
                    $this->attributes['value'] = null;
                }
                return;
            }
            
            // Handle Livewire/Filament temp uploads with JSON encoding
            if (is_array($value) || is_object($value)) {
                // If it's a single file in an array, extract it
                if (is_array($value) && count($value) === 1) {
                    $this->attributes['value'] = json_encode(reset($value));
                } else {
                    $this->attributes['value'] = json_encode($value);
                }
                // Log what was saved for debugging
                \Illuminate\Support\Facades\Log::info('Saved setting image value from array/object', [
                    'key' => $this->attributes['key'] ?? 'unknown',
                    'saved_value' => $this->attributes['value']
                ]);
                return;
            }
            
            // Fallback for any other type
            $this->attributes['value'] = json_encode($value);
            \Illuminate\Support\Facades\Log::info('Saved setting image value with fallback', [
                'key' => $this->attributes['key'] ?? 'unknown',
                'value_type' => gettype($value)
            ]);
            return;
        }
        
        // For other types, handle normally
        if (is_array($value) || is_object($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }
    
    /**
     * Check if a string is valid JSON
     * 
     * @param string $string
     * @return bool
     */
    protected function isJson($string) 
    {
        if (!is_string($string)) {
            return false;
        }
        
        try {
            $result = json_decode($string);
            return (json_last_error() === JSON_ERROR_NONE);
        } catch (\Exception $e) {
            return false;
        }
    }

    // /**
    //  * Get a setting value by key
    //  *
    //  * @param string $key
    //  * @param mixed $default
    //  * @return mixed
    //  */
    // public static function get(string $key, $default = null)
    // {
    //     $setting = self::where('key', $key)->first();

    //     if (!$setting) {
    //         return $default;
    //     }

    //     return $setting->value;
    // }

    // /**
    //  * Set a setting value
    //  *
    //  * @param string $key
    //  * @param mixed $value
    //  * @param string $type
    //  * @param string $group
    //  * @param string $description
    //  * @return Setting
    //  */
    // public static function set(string $key, $value, string $type = 'string', string $group = 'general', string $description = '')
    // {
    //     $setting = self::firstOrNew(['key' => $key]);

    //     $setting->fill([
    //         'value' => $value,
    //         'type' => $type,
    //         'group' => $group,
    //         'description' => $description
    //     ]);

    //     $setting->save();

    //     return $setting;
    // }
}