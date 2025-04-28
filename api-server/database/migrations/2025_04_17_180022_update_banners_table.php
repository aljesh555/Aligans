<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            // Rename columns to match new structure
            if (Schema::hasColumn('banners', 'image_url')) {
                $table->renameColumn('image_url', 'image_url');
            } else if (Schema::hasColumn('banners', 'image')) {
                $table->renameColumn('image', 'image_url');
            } else {
                $table->string('image_url')->nullable();
            }

            // Add missing columns if they don't exist
            if (!Schema::hasColumn('banners', 'description')) {
                $table->text('description')->nullable();
            }
            
            if (!Schema::hasColumn('banners', 'button_text')) {
                $table->string('button_text')->nullable();
            }
            
            if (!Schema::hasColumn('banners', 'button_url')) {
                $table->string('button_url')->nullable();
            }
            
            if (!Schema::hasColumn('banners', 'order')) {
                $table->integer('order')->default(0);
            }
            
            if (!Schema::hasColumn('banners', 'starts_at')) {
                $table->date('starts_at')->nullable();
            }
            
            if (!Schema::hasColumn('banners', 'ends_at')) {
                $table->date('ends_at')->nullable();
            }
            
            if (!Schema::hasColumn('banners', 'priority')) {
                $table->integer('priority')->default(0);
            }
            
            // Rename status column to is_active if it exists
            if (Schema::hasColumn('banners', 'status')) {
                $table->renameColumn('status', 'is_active');
            } else if (!Schema::hasColumn('banners', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            
            // Remove logo column if it exists
            if (Schema::hasColumn('banners', 'logo')) {
                $table->dropColumn('logo');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            // This is a risky migration to revert as we may lose data
            // Only revert field names and maintain compatibility
            if (Schema::hasColumn('banners', 'is_active')) {
                $table->renameColumn('is_active', 'status');
            }
        });
    }
};
