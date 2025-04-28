<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AdminLogin extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Check if the provided email and password match an admin account.
     *
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public static function checkCredentials($email, $password)
    {
        $admin = self::where('email', $email)->where('is_active', true)->first();
        
        if (!$admin) {
            return false;
        }
        
        return Hash::check($password, $admin->password);
    }
} 