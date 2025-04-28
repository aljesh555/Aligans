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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('on_sale')->default(false)->after('featured');
            $table->decimal('discount_price', 10, 2)->nullable()->after('on_sale');
            $table->integer('discount_percent')->nullable()->after('discount_price');
            $table->boolean('is_new_arrival')->default(false)->after('discount_percent');
            $table->date('sale_ends_at')->nullable()->after('is_new_arrival');
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
            $table->dropColumn('on_sale');
            $table->dropColumn('discount_price');
            $table->dropColumn('discount_percent');
            $table->dropColumn('is_new_arrival');
            $table->dropColumn('sale_ends_at');
        });
    }
};
