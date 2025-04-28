<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_policies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert initial shipping policy
        DB::table('shipping_policies')->insert([
            'title' => 'Shipping Policy',
            'content' => '<h2 class="text-xl font-bold mt-8 mb-4">1. Shipping Methods and Timeframes</h2>
            <p>At Aligans, we offer several shipping options to meet your needs:</p>
            
            <h3 class="text-lg font-semibold mt-4 mb-2">Standard Shipping</h3>
            <ul class="list-disc pl-6 mb-4">
              <li>Delivery within 5-7 business days</li>
              <li>Cost: $5.99 for orders under $50</li>
              <li>FREE for orders over $50</li>
            </ul>
            
            <h3 class="text-lg font-semibold mt-4 mb-2">Express Shipping</h3>
            <ul class="list-disc pl-6 mb-4">
              <li>Delivery within 2-3 business days</li>
              <li>Cost: $12.99</li>
            </ul>',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_policies');
    }
}; 