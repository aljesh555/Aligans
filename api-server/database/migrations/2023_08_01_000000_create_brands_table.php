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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo_url')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Add brand_id to products table
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove foreign key from products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropColumn('brand_id');
        });

        Schema::dropIfExists('brands');
    }
}; 