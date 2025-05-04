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
        'payment_method',
        'transaction_id',
        'amount',
        'payment_status',
        'currency',
        'payment_date',
        'payer_email',
        'payer_id',
        'payer_phone',
        'gateway_response',
        'is_refunded',
        'refund_date',
        'refund_transaction_id',
        'refund_amount'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'gateway_response' => 'array',
        'payment_date' => 'datetime',
        'refund_date' => 'datetime',
        'is_refunded' => 'boolean',
    ];

    /**
     * Get the order that owns the payment.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mark payment as successful
     */
    public function markAsSuccessful()
    {
        $this->payment_status = 'completed';
        $this->payment_date = now();
        $this->save();
        
        return $this;
    }
}
