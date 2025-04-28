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
            $table->string('user_name');
            $table->string('email');
            $table->string('phone');
            $table->enum('province_name', [
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
            $table->text('address')->comment('Full detailed address');
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
