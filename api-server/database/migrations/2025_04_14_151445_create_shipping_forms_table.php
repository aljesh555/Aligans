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
        Schema::create('shipping_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->enum('province', [
                'Koshi Province',
                'Madhesh Province',
                'Bagmati Province',
                'Gandaki Province',
                'Lumbini Province',
                'Karnali Province',
                'Sudurpashchim Province'
            ]);
            $table->string('city');
            $table->string('area');
            $table->string('building_details')->comment('Building/House no./Floor/Street');
            $table->text('address')->comment('Complete address: house123, street, area, city');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_forms');
    }
};
