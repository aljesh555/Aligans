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
        // Drop the tables in reverse order of their dependencies
        Schema::dropIfExists('payment_details');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This would be a complex implementation to recreate the tables
        // In a real project, you would recreate the full structure of these tables here
        // For now, we'll leave it empty as we're intentionally dropping these tables
    }
};
