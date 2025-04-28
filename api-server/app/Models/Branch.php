<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'contact_no'];

    /**
     * Get the users associated with this branch.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the product stocks for this branch.
     */
    public function productStocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    /**
     * Get the orders placed at this branch.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
