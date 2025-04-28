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
        Schema::table('logos', function (Blueprint $table) {
            // Remove the image_path column
            $table->dropColumn('image_path');
            
            // Add a binary column for storing the actual image
            $table->longText('image')->after('name');
            
            // Add a column to store the image mime type
            $table->string('mime_type')->after('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logos', function (Blueprint $table) {
            // Revert changes
            $table->dropColumn(['image', 'mime_type']);
            $table->string('image_path')->after('name');
        });
    }
};
