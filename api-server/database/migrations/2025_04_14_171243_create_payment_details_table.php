<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->nullable();
            $table->string('payment_method');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('NPR');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->dateTime('payment_date')->nullable();
            
            // Payment method specific fields
            $table->string('payer_email')->nullable();
            $table->string('payer_id')->nullable(); // For eSewa, Khalti, etc.
            $table->string('payer_phone')->nullable();
            
            // Response data from payment gateway
            $table->json('gateway_response')->nullable();
            
            // For refunds tracking
            $table->boolean('is_refunded')->default(false);
            $table->dateTime('refund_date')->nullable();
            $table->string('refund_transaction_id')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_details');
    }
};
