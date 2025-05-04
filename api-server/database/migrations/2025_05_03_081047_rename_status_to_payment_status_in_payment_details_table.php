<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if status column exists and payment_status doesn't exist
        if (Schema::hasColumn('payment_details', 'status') && !Schema::hasColumn('payment_details', 'payment_status')) {
            Schema::table('payment_details', function (Blueprint $table) {
                $table->renameColumn('status', 'payment_status');
            });
        } 
        // If both don't exist or only payment_status exists, no action needed
        elseif (!Schema::hasColumn('payment_details', 'status') && !Schema::hasColumn('payment_details', 'payment_status')) {
            // Add payment_status column
            Schema::table('payment_details', function (Blueprint $table) {
                $table->string('payment_status')->default('pending')->after('amount');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('payment_details', 'payment_status') && !Schema::hasColumn('payment_details', 'status')) {
            Schema::table('payment_details', function (Blueprint $table) {
                $table->renameColumn('payment_status', 'status');
            });
        }
    }
};
