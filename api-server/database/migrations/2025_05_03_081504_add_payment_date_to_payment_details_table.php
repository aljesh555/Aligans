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
        Schema::table('payment_details', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_details', 'payment_date')) {
                $table->timestamp('payment_date')->nullable()->after('payment_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_details', function (Blueprint $table) {
            if (Schema::hasColumn('payment_details', 'payment_date')) {
                $table->dropColumn('payment_date');
            }
        });
    }
};
