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
        // First drop any existing hero_images or banners tables
        Schema::dropIfExists('hero_images');
        Schema::dropIfExists('banners');

        // Create the new banners table
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Name/description of the banner for admin reference');
            $table->string('image')->comment('Path/URL to the banner image');
            $table->string('logo')->nullable()->comment('Path/URL to the logo image');
            $table->boolean('status')->default(true)->comment('Control if banner is active/inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
