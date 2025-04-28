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