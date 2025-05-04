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
        Schema::table('categories', function (Blueprint $table) {
            // Drop the unused image columns
            if (Schema::hasColumn('categories', 'thumbnail_image')) {
                $table->dropColumn('thumbnail_image');
            }
            
            if (Schema::hasColumn('categories', 'banner_image')) {
                $table->dropColumn('banner_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Recreate the columns if migration is rolled back
            if (!Schema::hasColumn('categories', 'thumbnail_image')) {
                $table->string('thumbnail_image')->nullable();
            }
            
            if (!Schema::hasColumn('categories', 'banner_image')) {
                $table->string('banner_image')->nullable();
            }
        });
    }
};
