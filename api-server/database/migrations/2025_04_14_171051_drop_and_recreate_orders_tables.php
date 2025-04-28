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
        // First, drop the order_items table since it has foreign key to orders
        Schema::dropIfExists('order_items');
        
        // Then drop the orders table
        Schema::dropIfExists('orders');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot truly reverse a drop operation with the original schema
        // The tables will be recreated in separate migrations
    }
};
