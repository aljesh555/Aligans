<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, add the columns without constraints
        Schema::table('products', function (Blueprint $table) {
            // Add the slug column if it doesn't exist
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->nullable();
            }
            
            // Add image fields if they don't exist
            if (!Schema::hasColumn('products', 'thumbnail')) {
                $table->string('thumbnail')->nullable();
            }
            
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable();
            }
            
            // Add other fields if needed
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable();
            }
            
            if (!Schema::hasColumn('products', 'status')) {
                $table->string('status')->default('active');
            }
            
            if (!Schema::hasColumn('products', 'short_description')) {
                $table->text('short_description')->nullable();
            }
        });
        
        // Generate slugs for existing products to avoid duplicates
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            if (empty($product->slug)) {
                $slug = Str::slug($product->name);
                $originalSlug = $slug;
                $count = 0;
                
                // Check for duplicate slugs
                while (DB::table('products')->where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $count++;
                    $slug = $originalSlug . '-' . $count;
                }
                
                DB::table('products')->where('id', $product->id)->update(['slug' => $slug]);
            }
        }
        
        // Now add the unique constraint after generating slugs
        Schema::table('products', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique(['slug']);
            
            // Drop the columns
            $table->dropColumn(['slug', 'thumbnail', 'image', 'sku', 'status', 'short_description']);
        });
    }
};
