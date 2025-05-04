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
        // For image type, ensure the value is a JSON string
        if ($this->attributes['type'] === 'image' && is_string($value) && !$this->isJson($value)) {
            $this->attributes['value'] = json_encode($value);
        } 
        // For string type, ensure the value is a JSON string
        else if ($this->attributes['type'] === 'string' && is_string($value) && !$this->isJson($value)) {
            $this->attributes['value'] = json_encode($value);
        }
        // For repeater type or already JSON formatted, store as is
        else {
            $this->attributes['value'] = $value;
        }
    }

    /**
     * Check if a string is a valid JSON
     *
     * @param  string  $string
     * @return bool
     */
    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
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