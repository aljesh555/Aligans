<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            // First add all new columns
            if (!Schema::hasColumn('banners', 'image')) {
                $table->string('image')->nullable();
            }
            if (!Schema::hasColumn('banners', 'title')) {
                $table->string('title')->nullable();
            }
            if (!Schema::hasColumn('banners', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('banners', 'button_text')) {
                $table->string('button_text')->nullable();
            }
            if (!Schema::hasColumn('banners', 'button_link')) {
                $table->string('button_link')->nullable();
            }
            if (!Schema::hasColumn('banners', 'order')) {
                $table->integer('order')->default(0);
            }
            if (!Schema::hasColumn('banners', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('banners', 'starts_at')) {
                $table->timestamp('starts_at')->nullable();
            }
            if (!Schema::hasColumn('banners', 'ends_at')) {
                $table->timestamp('ends_at')->nullable();
            }
        });

        // Then handle the data migration if needed
        if (Schema::hasColumn('banners', 'image_url')) {
            DB::statement('UPDATE banners SET image = image_url WHERE image IS NULL AND image_url IS NOT NULL');
            
            Schema::table('banners', function (Blueprint $table) {
                $table->dropColumn('image_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            if (!Schema::hasColumn('banners', 'image_url')) {
                $table->string('image_url')->nullable();
                
                // Copy data back if needed
                DB::statement('UPDATE banners SET image_url = image WHERE image_url IS NULL AND image IS NOT NULL');
            }
            
            $table->dropColumn([
                'image',
                'title',
                'description',
                'button_text',
                'button_link',
                'order',
                'is_active',
                'starts_at',
                'ends_at'
            ]);
        });
    }
}; 