<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    use HasFactory;

    // Payment status constants
    const STATUS_UNPAID = 'unpaid';
    const STATUS_PAID = 'paid';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'order_id',
        'payment_gateway',
        'transaction_id',
        'amount',
        'payment_status'
    ];

    /**
     * Get the order that owns the payment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
