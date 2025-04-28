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
        Schema::dropIfExists('logos');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('logos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->binary('image');
            $table->string('mime_type')->nullable();
            $table->string('type')->default('header');
            $table->boolean('is_active')->default(true);
            $table->string('alt_text')->nullable();
            $table->timestamps();
        });
    }
};
