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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('thumbnail_image')->nullable()->after('image_url');
            $table->string('banner_image')->nullable()->after('thumbnail_image');
            $table->text('description')->nullable()->after('banner_image');
            $table->string('status')->default('active')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['thumbnail_image', 'banner_image', 'description', 'status']);
        });
    }
}; 